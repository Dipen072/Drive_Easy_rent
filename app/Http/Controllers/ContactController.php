<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
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
        $validated=$request->validate([
            'name'=>'required',
            'email'=>'required',
            'phone'=>'required',
            'subject'=>'required',
            'message'=>'required',
        ]);

        $table = new Contact();
        $table->name = $request->name;
        $table->email = $request->email;
        $table->phone = $request->phone;
        $table->subject = $request->subject;
        $table->message = $request->message;
        $table->save();
        Alert::success('Success', 'Your message has been sent successfully!');
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
