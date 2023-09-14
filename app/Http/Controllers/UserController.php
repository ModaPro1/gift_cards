<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Category;
use App\Models\ContactRequest;
use Illuminate\Http\Request;
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

      ContactRequest::create([
        'user_name' => $request->name,
        'user_email' => $request->email,
        'message' => $request->message,
      ]);

      return redirect('/')->with('success', 'Message Successfully Sent !');
    }
}
