<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\ContactRequest;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function showLogin() {
      return Inertia::render('Admin/Login');
    }
    public function login(Request $request) {
      $request->validate([
        'email' => 'required|email',
        'password' => 'required'
      ]);

      if(Auth::attempt($request->only('email', 'password'), true)) {
        $user = User::where('email', $request->email)->first();
        if($user->role == 'admin') {
          return redirect('/admin');
        } else {
          Auth::logout();
          return redirect()->back()->withErrors(['email' => 'Credentials does not match.']);
        }
      }
      return redirect()->back()->withErrors(['email' => 'Credentials does not match.']);
    }

    public function index() {
      $users = User::select('id', 'name', 'email')->paginate(10);
      $usersCount = User::count();
      $ordersCount = Order::count();
      $contactCount = ContactRequest::count();

      return Inertia::render('Admin/Index', compact('users', 'usersCount', 'ordersCount', 'contactCount'));
    }

    public function showUser($id) {
      $user = User::findOrFail($id);
      $ordersCount = $user->orders->count();
      return Inertia::render('Admin/ShowUser', compact('user', 'ordersCount'));
    }

    public function showEditUser($id) {
      return $id;
    }

    public function showOrders() {
      $orders = Order::all();
      $finalOutput = [];
      foreach($orders as $order) {
        $card = Card::where('id', $order->card_id)->select('name', 'type', 'image', 'price')->first();
        $user = User::where('id', $order->user_id)->select('id', 'name')->first();
        array_push($finalOutput, [
          "card_details" => $card,
          "order_id" => $order->id,
          "order_status" => $order->status,
          "order_date" => date_format($order->created_at, 'Y-m-d h:i'),
          'user' => $user
        ]);
      }

      return Inertia::render('Admin/Orders', [
        'orders' => $finalOutput
      ]);
    }

}
