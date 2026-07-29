<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Category;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CarController extends Controller
{
    /**
     * Admin: List all cars
     */
    public function index()
    {
        $cars = Car::with('category')->orderBy('id', 'desc')->get();
        $categories = Category::all();
        return view('admin.cars', compact('cars', 'categories'));
    }

    /**
     * Admin: Show form to add a car
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.add-car', compact('categories'));
    }

    /**
     * Admin: Store a new car
     */
    public function store(Request $request)
    {
        $request->validate([
            'brand_name'   => 'required|string|max:255',
            'model_name'   => 'required|string|max:255',
            'rate_per_day' => 'required|numeric',
            'category_id'  => 'required',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $car = new Car();
        $car->brand_name   = $request->brand_name;
        $car->model_name   = $request->model_name;
        $car->year         = $request->year ?? '2024';
        $car->category_id  = $request->category_id;
        $car->rate_per_day = $request->rate_per_day;
        $car->location     = $request->location ?? 'Mumbai';
        $car->seats        = $request->seats ?? 5;
        $car->fuel_type    = $request->fuel_type ?? 'Petrol';
        $car->transmission = $request->transmission ?? 'Automatic';
        $car->status       = 'Available';

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/cars'), $fileName);
            $car->image = 'upload/cars/' . $fileName;
        } else {
            $car->image = 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=600&q=80';
        }

        $car->save();

        Alert::success('Success', 'Car has been added successfully!');
        return redirect('/admin/cars');
    }

    /**
     * Admin: Delete car
     */
    public function destroy($id)
    {
        $car = Car::find($id);
        if ($car) {
            $car->delete();
            Alert::success('Deleted', 'Car deleted successfully!');
        }
        return redirect('/admin/cars');
    }

    /**
     * Website: Browse Cars page
     */
    public function websiteCars(Request $request)
    {
        try {
            $query = Car::with('category')->where('status', 'Available');

            if ($request->has('category') && $request->category != 'all') {
                $query->where('category_id', $request->category);
            }

            $cars = $query->orderBy('id', 'desc')->get();
            $categories = Category::withCount('cars')->get();
        } catch (\Throwable $e) {
            $cars = collect();
            $categories = collect();
        }

        return view('website.cars', compact('cars', 'categories'));
    }

    /**
     * Website: Homepage
     */
    public function websiteIndex()
    {
        try {
            $featuredCars = Car::with('category')->where('status', 'Available')->latest()->take(6)->get();
            $categories   = Category::withCount('cars')->get();
        } catch (\Throwable $e) {
            $featuredCars = collect();
            $categories   = collect();
        }

        return view('website.index', compact('featuredCars', 'categories'));
    }
}
