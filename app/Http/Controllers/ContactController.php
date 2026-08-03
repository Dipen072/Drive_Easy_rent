<?php

namespace App\Http\Controllers;

use App\Mail\ContactInquiryMail;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $messages = Contact::orderBy('id', 'desc')->get();
        return view('admin.contact-messages', compact('messages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('website.contact');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'required|string|max:20',
            'subject' => 'required|string',
            'message' => 'required|string|min:5',
        ], [
            'name.required'    => 'Please enter your full name.',
            'email.required'   => 'Please enter your email address.',
            'email.email'      => 'Please enter a valid email address.',
            'phone.required'   => 'Please enter your phone number.',
            'subject.required' => 'Please select an inquiry subject.',
            'message.required' => 'Please enter your message.',
            'message.min'      => 'Message must be at least 5 characters long.',
        ]);

        $table = new Contact();
        $table->name = $request->name;
        $table->email = $request->email;
        $table->phone = $request->phone;
        $table->subject = $request->subject;
        $table->message = $request->message;
        $table->save();

        try {
            Mail::to($table->email)->send(new ContactInquiryMail($table));
        } catch (\Throwable $e) {
            Log::error('Failed to send contact inquiry email: ' . $e->getMessage());
        }

        Alert::success('Success', 'Your message has been sent successfully! A copy of your inquiry details has been emailed to you.');
        return redirect('/contact');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Contact::find($id);
        if($data) {
            $data->delete();
            Alert::success('Deleted', 'Contact message deleted successfully!');
        }
        return redirect('/admin/contact-messages');
    }
}
