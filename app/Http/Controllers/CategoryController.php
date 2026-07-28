<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('cars')->orderBy('id', 'desc')->get();
        return view('admin.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        if ($request->cat_id) {
            $category = Category::find($request->cat_id);
        } else {
            $category = new Category();
        }

        $category->name = $request->name;
        $category->icon = $request->icon ?? 'fa-car-side';
        $category->description = $request->description;
        $category->save();

        Alert::success('Success', 'Category saved successfully!');
        return redirect('/admin/categories');
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->delete();
            Alert::success('Deleted', 'Category deleted successfully!');
        }
        return redirect('/admin/categories');
    }
}
