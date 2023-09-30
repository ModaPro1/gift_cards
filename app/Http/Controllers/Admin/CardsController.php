<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Category;
use App\Models\Notification;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CardsController extends Controller
{
  // Cards
  public function showCards()
  {
    $categories = Category::all();
    $finalOutput = [];

    foreach ($categories as $category) {
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

  public function changeCategoryName(Request $request)
  {
    $cat = Category::find($request->id);
    $cat->update([
      'name' => $request->value
    ]);
    return redirect()->back();
  }
  
  public function deleteCat(Request $request)
  {
    // 1 - delete category
    // 2 - delete cards
    // 3 - delete notifications
    // 4 - delete orders

    $cat = Category::where('id', $request->id)->first();
    if(!$cat) {
      return redirect()->back()->withErrors(['error' => 'An Error Occured']);
    }
    $cat->delete(); // step 1
    $cards = $cat->cards;
    foreach($cards as $card) {
      $card->delete(); // step 2
      $orders = Order::where('card_id', $card->id)->get();
      foreach($orders as $order) {
        $notifications = Notification::where('data->order_id', $order->id)->get();
        foreach($notifications as $notification) {
          $notification->delete(); // step 3
        }
        $order->delete(); // step 4
      }
    }
  }

  public function showEditCard($id)
  {
    $card = Card::findOrFail($id);
    $success = false;

    return Inertia::render('Admin/Cards/EditCard', compact('card', 'success'));
  }

  public function editCard(Request $request, $id)
  {
    $request->validate([
      'name' => 'required',
      'price' => 'required',
    ]);

    $card = Card::find($id);

    if (!$card) {
      return redirect()->back()->withErrors('There was an error');
    }

    // Update Image
    if ($request->image) {
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

  public function showAddCard()
  {
    $cats = Category::all();
    return Inertia::render('Admin/Cards/AddCard', compact('cats'));
  }

  public function addCard(Request $request)
  {
    $request->validate([
      'category' => 'required',
      'name' => 'required',
      'price' => 'required',
      'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
    ]);

    $cat = Category::where('name', $request->category)->first();

    if (!$cat) {
      return;
    }

    $card = Card::create([
      'name' => $request->name,
      'price' => $request->price,
      'image' => $request->image ? $request->image : $cat->image, // get image of first card using this category
      'category_id' => $cat->id,
      'type' => str_replace('/', '', $cat->url), // Ex: /steam => steam
    ]);

    // Add Image if exists
    if ($request->image) {
      $request->image->move(public_path('images/small'), "$card->id.webp");
      $card->update([
        'image' => "$card->id.webp"
      ]);
    }

    return redirect()->route('admin.cards')->with('success', 'Card Added Successfully.');
  }

  public function deleteCard(Request $request)
  {
    $card = Card::findOrFail($request->id);
    $orders = Order::where('card_id', $card->id)->get();

    if ($orders) {
      foreach ($orders as $order) {
        $notification = Notification::where('data->order_id', $order->id)->first();
        if ($notification) {
          $notification->delete();
        }
        $order->delete();
      }
    }
    $card->delete();
    return redirect()->route('admin.cards')->with('success', 'Card Deleted Successfully.');
  }

  public function showAddCat()
  {
    return Inertia::render('Admin/Cards/AddCat');
  }

  public function addCat(Request $request)
  {
    $request->validate([
      'name' => 'required',
      'url' => 'required',
      'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
    ]);

    if (!preg_match('/^[^\s\/$%]+$/', $request->url)) {
      return redirect()->back()->withErrors(['url' => 'The url cannot contain spaces, /, $, or %.']);
    }

    $request->image->move(public_path('images/small'), strtolower($request->url) . ".webp");

    Category::create([
      'name' => $request->name,
      'image' => strtolower($request->url) . ".webp",
      'url' => "/" . strtolower($request->url)
    ]);

    return redirect()->route('admin.cards')->with('success', 'Category Successfully Added.');

  }

}
