<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Card;
use App\Models\ContactRequest;
use App\Models\Notification;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AdminController extends Controller
{
    // Authentication
    public function showLogin() {
      return Inertia::render('Admin/Login');
    }
    public function login(Request $request) {
      $request->validate([
        'email' => 'required|email',
        'password' => 'required'
      ]);

      if(Auth::guard('admin')->attempt($request->only('email', 'password'), true)) {
        return redirect('/admin');
      }
      return redirect()->back()->withErrors(['email' => 'Credentials does not match.']);
    }
    public function logout() {
      Auth::guard('admin')->logout();
      return redirect()->route('admin.login');
    }

    // Main Page
    public function index(Request $request) {
      $users = User::select('id', 'name', 'email')->paginate(10);
      $usersCount = User::count();
      $ordersCount = Order::count();
      $contactCount = ContactRequest::count();
      $searchVal = $request->query('search');

      if($request->query('search')) {
        $name = $request->query('search');
        $users = User::where('name', 'like', '%'.$name.'%')
        ->select('id', 'name', 'email')
        ->paginate(10)
        ->withQueryString();
      }

      return Inertia::render(
        'Admin/Index',
        compact('users', 'usersCount', 'ordersCount', 'contactCount', 'searchVal'));
    }

    // CRUD
    public function showUser($id) {
      $user = User::findOrFail($id);
      $ordersCount = $user->orders->count();
      $successMessage = session('success') ? session('success') : '';

      return Inertia::render('Admin/ShowUser', compact('user', 'ordersCount', 'successMessage'));
    }

    public function showEditUser($id) {
      $user = User::findOrFail($id);
      $ordersCount = $user->orders->count();
      return Inertia::render('Admin/EditUser', compact('user', 'ordersCount'));
    }

    public function editUser(Request $request, $id) {
      $request->validate([
        'name' => 'required|max:25',
        'email' => 'required|email'
      ]);

      $user = User::findOrFail($id);

      $user->update([
        'name' => $request->name,
        'email' => $request->email
      ]);

      return redirect()->route('admin.user', $user->id)->with('success', 'User Edited Successfully.');
    }

    public function deleteUser($id) {
      $user = User::findOrFail($id);
      $user->delete();
      return redirect()->route('admin.index')->with('success', 'User Deleted Successfully.');
    }

    // Orders
    public function showOrders() {
      $orders = Order::where('status', '!=', 'unpaid')->get();

      $finalOutput = [];
      foreach($orders as $order) {
        $card = $order->card;
        $user = $order->user;
        array_push($finalOutput, [
          "card_details" => $card,
          "order_id" => $order->id,
          "card_code" => $order->card_code,
          "order_status" => $order->status,
          "order_date" => date_format($order->created_at, 'Y-m-d h:i'),
          'user' => $user
        ]);
      }

      return Inertia::render('Admin/Orders', [
        'orders' => $finalOutput,
      ]);
    }

    public function showOrder($notificationId) {
      $notification = Notification::findOrFail($notificationId);

      $data = json_decode($notification->data, true);

      $order = Order::find($data['order_id']);

      if($order == null) {
        // order not found
        return abort(404);
      }

      $notification->update([
        'read_at' => now()
      ]);

      $card = $order->card;
      $user = $order->user;
      $finalOutput = [];
      array_push($finalOutput, [
        "card_details" => $card,
        "order_id" => $order->id,
        "card_code" => $order->card_code,
        "order_status" => $order->status,
        "order_date" => date_format($order->created_at, 'Y-m-d h:i'),
        'user' => $user
      ]);

      return Inertia::render('Admin/Orders', [
        'orders' => $finalOutput
      ]);
    }

    public function refuseOrder(Request $request) {
      $order = Order::findOrFail($request->order_id);
      $user = $order->user;

      $order->update([
        'status' => 'refused',
      ]);
    }

    public function sendCard(Request $request) {
      $order = Order::findOrFail($request->order_id);
      $user = $order->user;

      $order->update([
        'status' => 'recieved',
        'card_code' => $request->card_code
      ]);
    }

    // Contacts
    public function showContacts() {
      $contacts = ContactRequest::all();
      return Inertia::render('Admin/Contacts', compact('contacts'));
    }

    public function showContact($notificationId) {
      $notification = Notification::findOrFail($notificationId);
      $data = json_decode($notification->data, true);
      $contacts = [ContactRequest::find($data['contact_id'])];

      if($contacts[0] == null) {
        // the contact request is not found
        return abort(404);
      }

      $notification->update([
        'read_at' => now()
      ]);
      return Inertia::render('Admin/Contacts', compact('contacts'));
    }

    // Notifications
    public function showNotifications() {
      return Inertia::render('Admin/Notifications');
    }
}
