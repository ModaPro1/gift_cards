<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Category;
use App\Models\ContactRequest;
use App\Models\Notification;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
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

    // Cards
    public function showCards() {
      $categories = Category::all();
      $finalOutput = [];

      foreach($categories as $category) {
        array_push($finalOutput, [
          'id' => $category->id,
          'category_name' => $category->name,
          'cards' => $category->cards
        ]);
      }

      return Inertia::render('Admin/Cards/Cards', [
        'cats' => $finalOutput,
        'success' => session('success') ? session('success') : false
      ]);
    }

    public function changeCategoryName(Request $request) {
      $cat = Category::find($request->id);
      $cat->update([
        'name' => $request->value
      ]);
    }

    public function showEditCard($id) {
      $card = Card::findOrFail($id);
      $success = false;

      return Inertia::render('Admin/Cards/EditCard', compact('card', 'success'));
    }

    public function editCard(Request $request, $id) {
      $request->validate([
        'name' => 'required',
        'price' => 'required',
      ]);

      $card = Card::find($id);

      if(!$card) {
        return redirect()->back()->withErrors('There was an error');
      }

      // Update Image
      if($request->image) {
        $request->image->move(public_path('images/small'), "$card->id.webp");
        $card->update([
          'image' => "$card->id.webp"
        ]);
      }

      // Update Data
      $card->update([
        'name' => $request->name,
        'price' => $request->price
      ]);

      return redirect()->route('admin.cards')->with('success', 'Successfully Edited.');

    }

    public function showAddCard() {
      $cats = Category::all();
      return Inertia::render('Admin/Cards/AddCard', compact('cats'));
    }

    public function addCard(Request $request) {
      $request->validate([
        'category' => 'required',
        'name' => 'required',
        'price' => 'required',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
      ]);

      $cat = Category::where('name', $request->category)->first();
      
      if(!$cat) {
        return;
      }
      
      if(!count($cat->cards) > 0 && !$request->image) { // there is no cards for this category and no image added
        return redirect()->back()->withErrors(['image' => 'Please add image as this category has no images yet.']);
      }

      $card = Card::create([
        'name' => $request->name,
        'price' => $request->price,
        'image' => count($cat->cards) > 0 ?  $cat->cards[0]->image : $request->image, // get image of first card using this category
        'category_id' => $cat->id,
        'type' => str_replace('/', '', $cat->url),
      ]);

      // Add Image if exists
      if($request->image) {
        $request->image->move(public_path('images/small'), "$card->id.webp");
        $card->update([
          'image' => "$card->id.webp"
        ]);
      }

      return redirect()->route('admin.cards')->with('success', 'Card Added Successfully.');
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
