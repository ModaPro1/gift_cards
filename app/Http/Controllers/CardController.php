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
    return Inertia::render('Cards/Cards', [
      'cards' => Card::all()
    ]);
  }

  public function singleCard($type) {
    $supportedCardsTypes = Card::select('type')->get();
    $supportedCards = [];
    foreach($supportedCardsTypes as $card) {
      array_push($supportedCards, $card->type);
    }

    if(in_array($type, $supportedCards)) {
      return Inertia::render('Cards/Cards', [
        'cards' => Card::where('type', $type)->get()
      ]);
    }else {
      abort(404);
    }

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
