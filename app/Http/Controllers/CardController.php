<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CardController extends Controller
{
  public function index() {
    $successMessage = '';
    if(session('success')) {
      $successMessage = session('success');
    }

    if(auth()->user()) {
      if(auth()->user()->role == 'admin') {
        return redirect('/admin');
      }
    }

    return Inertia::render('Dashboard', [
      'categories' => Category::all(),
      'successMessage' => $successMessage
    ]);
  }

  public function allCards() {
    $categories = Category::all();
    $finalOutput = [];

    foreach($categories as $category) {
      array_push($finalOutput, [
        'id' => $category->id,
        'category_name' => $category->name,
        'cards' => $category->cards
      ]);
    }

    return Inertia::render('Cards/Cards', [
      'cards' => $finalOutput
    ]);
  }

  public function singleCard($type) {
    $category = Category::where('url', "/$type")->first();
    $cards = [
      'id' => $category->id,
      'category_name' => $category->name,
      'cards' => $category->cards
    ];

    return Inertia::render('Cards/Cards', [
      'cards' => $cards
    ]);

  }

  public function showOrders() {
    $user = User::where('id', Auth::id())->first();
    $orders = $user->orders->where('status', '!=', 'unpaid');
    $finalOutput = [];
    foreach($orders as $order) {
      $card = Card::where('id', $order->card_id)->select('name', 'type', 'image', 'price')->first();
      array_push($finalOutput, [
        "card_details" => $card,
        "order_status" => $order->status,
        "card_code" => $order->card_code,
        "order_date" => date_format($order->created_at, 'Y-m-d h:i')
      ]);
    }

    return Inertia::render('Cards/Orders', [
      'orders' => $finalOutput
    ]);
  }
}
