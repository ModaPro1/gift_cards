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
    function generateCats() {
      $catsData = [
        [
          'name' => 'Pubg',
          'url' => '/pubg',
          'image' => 'pubg.webp'
        ],
        [
          'name' => 'Roblox',
          'url' => '/roblox',
          'image' => 'roblox.webp'
        ],
        [
          'name' => 'Razer',
          'url' => '/razer',
          'image' => 'razer.webp'
        ],
        [
          'name' => 'Google Play',
          'url' => '/googleplay',
          'image' => 'google-play.webp'
        ],
        [
          'name' => 'Steam',
          'url' => '/steam',
          'image' => 'steam.webp'
        ],
        [
          'name' => 'Amazon',
          'url' => '/amazon',
          'image' => 'amazon.webp'
        ],
      ];

      foreach($catsData as $cat) {
        Category::create([
          'name' => $cat['name'],
          'image' => $cat['image'],
          'url' => $cat['url']
        ]);
      }
    }
    // generateCats();

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
    function generateCards() {
      $cardsData = [
        [
          'name' => 'Google Play',
          'type' => 'googleplay',
          'image' => 'google-play.webp',
          'price' => 5
        ],
        [
          'name' => 'Google Play',
          'type' => 'googleplay',
          'image' => 'google-play.webp',
          'price' => 10
        ],
        [
          'name' => 'Google Play',
          'type' => 'googleplay',
          'image' => 'google-play.webp',
          'price' => 15
        ],
        [
          'name' => 'Google Play',
          'type' => 'googleplay',
          'image' => 'google-play.webp',
          'price' => 20
        ],
        [
          'name' => 'Pubg Mobile',
          'type' => 'pubg',
          'image' => 'pubg.webp',
          'price' => 5
        ],
        [
          'name' => 'Pubg Mobile',
          'type' => 'pubg',
          'image' => 'pubg.webp',
          'price' => 10
        ],
        [
          'name' => 'Pubg Mobile',
          'type' => 'pubg',
          'image' => 'pubg.webp',
          'price' => 15
        ],
        [
          'name' => 'Pubg Mobile',
          'type' => 'pubg',
          'image' => 'pubg.webp',
          'price' => 20
        ],
        [
          'name' => 'Roblox',
          'type' => 'roblox',
          'image' => 'roblox.webp',
          'price' => 1
        ],
        [
          'name' => 'Roblox',
          'type' => 'roblox',
          'image' => 'roblox.webp',
          'price' => 5
        ],
        [
          'name' => 'Roblox',
          'type' => 'roblox',
          'image' => 'roblox.webp',
          'price' => 10
        ],
        [
          'name' => 'Roblox',
          'type' => 'roblox',
          'image' => 'roblox.webp',
          'price' => 15
        ],
        [
          'name' => 'Roblox',
          'type' => 'roblox',
          'image' => 'roblox.webp',
          'price' => 20
        ],
      ];
      foreach($cardsData as $card) {
        Card::create([
          'name' => $card['name'],
          'type' => $card['type'],
          'image' => $card['image'],
          'price' => $card['price']
        ]);
      }
    }
    // generateCards();

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
        "order_date" => date_format($order->created_at, 'Y-m-d h:i')
      ]);
    }

    return Inertia::render('Cards/Orders', [
      'orders' => $finalOutput
    ]);
  }
}
