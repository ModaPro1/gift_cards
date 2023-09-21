<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Card;
use App\Models\Order;
use App\Models\User;
use App\Notifications\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Inertia\Inertia;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\Stripe;

class PaymentController extends Controller
{
  
  public function checkout(Request $request) {
    if(!Auth::user()) {
      return abort(404);
    }

    Stripe::setApiKey(env('STRIPE_SECRET'));

    // getting the card
    $selectedCard = Card::findOrFail($request->cardId);

    // Calculate Stripe fees (replace these values with the actual fees)
    $stripePercentageFee = 3.9; // 3.9% processing fee
    $stripeFixedFee = 0.30; // $0.30 fixed fee
    $originalAmount = $selectedCard->price; // card price

    // Calculate total Stripe fees
    $totalStripeFees = ($originalAmount * ($stripePercentageFee / 100)) + $stripeFixedFee;
    
    // creating session
    $session = Session::create([
      'line_items' => [
        [
          'price_data' => [
            'currency' => 'usd',
            'product_data' => [
              'name' => $selectedCard->name . ' Card',
            ],
            'unit_amount' => intval(($originalAmount + $totalStripeFees) * 100),
          ],
          'quantity' => 1,
        ]
      ],
      'mode' => 'payment',
      'success_url' => route('paymentsuccess', [], true).'?session_id={CHECKOUT_SESSION_ID}',
      'cancel_url' => route('cards', [], true),
    ]);

    // Creating unpaid Order in Database
    Order::create([
      'session_id' => $session->id,
      'card_id' => $selectedCard->id,
      'user_id' => Auth::id(),
      'status' => 'unpaid'
    ]);

    return response()->json(['redirect' => $session->url]);
  }

  public function success(Request $request) {
    if(!$request->get('session_id')) {
      abort(404);
    }
    Stripe::setApiKey(env('STRIPE_SECRET'));
    
    $session = Session::retrieve($request->get('session_id'));
    $order = Order::where('session_id', $session->id)->first();
    $admins = Admin::all();

    if($order && $order->status == 'paid') {
      // order success page seen once and its status is paid
      return abort(404);
    } else {
      $order->status = 'paid'; // update order status
      $order->save();
      // send notification
      Notification::send($admins, new AdminNotification(auth()->id(), $order->id, null));
      return Inertia::render('PaymentSuccess');
    }
  }

}
