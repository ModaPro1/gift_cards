<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\ContactRequest;
use App\Notifications\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Inertia\Inertia;

class UserController extends Controller
{

    public function showContact() {
      return Inertia::render('Contact', [
        'name' => auth()->user() ? auth()->user()->name : '',
        'email' => auth()->user() ? auth()->user()->email : ''
      ]);
    }

    public function contact(Request $request) {
      $request->validate([
        'name' => 'required|max:25',
        'email' => 'required',
        'message' => 'required|min:10|max:255'
      ]);

      $contact = ContactRequest::create([
        'user_name' => $request->name,
        'user_email' => $request->email,
        'message' => $request->message,
      ]);

      $admins = Admin::all();
      Notification::send($admins, new AdminNotification(auth()->id(), null, $contact->id));

      return redirect('/')->with('success', 'Message Successfully Sent !');
    }
}
