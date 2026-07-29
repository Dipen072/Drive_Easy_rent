<?php

namespace App\Http\Controllers;

use App\Mail\BookingStatusUpdatedMail;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class AdminBookingController extends Controller
{
    /**
     * Admin Bookings Management Page
     */
    public function index(Request $request)
    {
        if (!session()->has('admin_id')) {
            Alert::warning('Login Required', 'Please log in to access Admin Panel.');
            return redirect('/admin/login');
        }

        $query = Booking::with(['car', 'customer', 'pickupLocation', 'dropoffLocation', 'payment'])
            ->orderBy('id', 'desc');

        if ($request->has('status') && $request->status !== 'all' && $request->status !== '') {
            $query->where('booking_status', $request->status);
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('booking_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  })
                  ->orWhereHas('car', function ($carq) use ($search) {
                      $carq->where('brand_name', 'like', "%{$search}%")
                           ->orWhere('model_name', 'like', "%{$search}%");
                  });
            });
        }

        $bookings = $query->get();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'  => true,
                'bookings' => $bookings,
            ]);
        }

        return view('admin.bookings', compact('bookings'));
    }

    /**
     * Get Detailed Booking Information for Modal (JSON)
     */
    public function show($id)
    {
        $booking = Booking::with(['car.category', 'customer', 'pickupLocation', 'dropoffLocation', 'extraServices', 'payment'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'booking' => $booking,
        ]);
    }

    /**
     * Update Booking Status (Pending -> Confirmed -> Active -> Completed / Cancelled)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Confirmed,Active,Completed,Cancelled',
            'reason' => 'nullable|string',
        ]);

        $booking = Booking::with('customer')->findOrFail($id);
        $oldStatus = $booking->booking_status;
        $newStatus = $request->status;

        $updateData = ['booking_status' => $newStatus];

        if ($newStatus === 'Confirmed' && !$booking->confirmed_at) {
            $updateData['confirmed_at'] = now();
        } elseif ($newStatus === 'Completed') {
            $updateData['completed_at'] = now();
            if ($booking->payment) {
                $booking->payment->update(['payment_status' => 'Paid']);
                $updateData['payment_status'] = 'Paid';
            }
        } elseif ($newStatus === 'Cancelled') {
            $updateData['cancelled_at']        = now();
            $updateData['cancellation_reason'] = $request->reason ?? 'Cancelled by Admin';
            if ($booking->payment && $booking->payment->payment_status === 'Paid') {
                $booking->payment->update(['payment_status' => 'Refunded']);
                $updateData['payment_status'] = 'Refunded';
            }
        }

        $booking->update($updateData);

        // Dispatch Status Update Email to Customer
        try {
            if ($booking->customer && $booking->customer->email && $oldStatus !== $newStatus) {
                Mail::to($booking->customer->email)->send(new BookingStatusUpdatedMail($booking, $oldStatus, $newStatus));
            }
        } catch (\Throwable $e) {
            // Silent fallback
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Booking {$booking->booking_number} status updated to {$newStatus}.",
                'booking' => $booking->fresh(['payment']),
            ]);
        }

        Alert::success('Status Updated', "Booking status changed from {$oldStatus} to {$newStatus}.");
        return back();
    }

    /**
     * Approve Cash Booking
     */
    public function approveCash($id)
    {
        $booking = Booking::with('customer')->findOrFail($id);
        $oldStatus = $booking->booking_status;

        $booking->update([
            'booking_status' => 'Confirmed',
            'payment_status' => 'Paid',
            'confirmed_at'   => now(),
        ]);

        if ($booking->payment) {
            $booking->payment->update([
                'payment_status' => 'Paid',
                'paid_at'        => now(),
            ]);
        }

        // Dispatch Status Update Email to Customer
        try {
            if ($booking->customer && $booking->customer->email) {
                Mail::to($booking->customer->email)->send(new BookingStatusUpdatedMail($booking, $oldStatus, 'Confirmed'));
            }
        } catch (\Throwable $e) {
            // Silent fallback
        }

        return response()->json([
            'success' => true,
            'message' => "Cash payment for {$booking->booking_number} approved and confirmed!",
        ]);
    }

    /**
     * Admin Payments List Page (Dynamic from DB)
     */
    public function payments(Request $request)
    {
        if (!session()->has('admin_id')) {
            Alert::warning('Login Required', 'Please log in to access Admin Panel.');
            return redirect('/admin/login');
        }

        $query = Payment::with(['booking.customer', 'customer'])->orderBy('id', 'desc');

        if ($request->has('status') && $request->status !== 'all' && !empty($request->status)) {
            $query->where('payment_status', $request->status);
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhere('payment_method', 'like', "%{$search}%")
                  ->orWhereHas('booking', function ($bq) use ($search) {
                      $bq->where('booking_number', 'like', "%{$search}%");
                  })
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $payments = $query->get();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'  => true,
                'payments' => $payments,
            ]);
        }

        return view('admin.payments', compact('payments'));
    }
}
