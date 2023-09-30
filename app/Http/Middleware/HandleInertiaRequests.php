<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
  /**
   * The root template that's loaded on the first page visit.
   *
   * @see https://inertiajs.com/server-side-setup#root-template
   * @var string
   */
  protected $rootView = 'app';

  /**
   * Determines the current asset version.
   *
   * @see https://inertiajs.com/asset-versioning
   * @param  \Illuminate\Http\Request  $request
   * @return string|null
   */
  public function version(Request $request): ?string
  {
    return parent::version($request);
  }

  /**
   * Defines the props that are shared by default.
   *
   * @see https://inertiajs.com/shared-data
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function share(Request $request): array
  {
    $user = auth()->user();

    $notifications = [];
    $notificationsData = [];
    $unreadNotifications = [];

    if ($user) {
      $orders = $user->orders->where('status', '!=', 'unpaid');
      $hasOrders = count($orders) > 0;
    } else {
      // Handle the case where the user is not authenticated
      $orders = [];
      $hasOrders = false;
    }

    if(auth('admin')->check()) {
      $notifications = Admin::where('id', auth('admin')->id())->first()->notifications;
      $unreadNotifications = Admin::where('id', auth('admin')->id())->first()->unreadNotifications;
      $notificationsData = [];

      foreach($notifications as $notification) {
        $notificationDate = Carbon::parse($notification->created_at);
        $now = Carbon::now();
        $daysAgo = $notificationDate->diffInDays($now);
        $hoursAgo = $notificationDate->diffInHours($now);
        $minutesAgo = $notificationDate->diffInMinutes($now);

        if($notification->data['order_id'] == null) {
          // Contact Notification
          
          $notificationUser = User::find($notification->data['user_id']);

          array_push($notificationsData, [
            'user' => $notificationUser,
            'id' => $notification->id,
            'seen' => $notification->read_at,
            'daysAgo' => $daysAgo,
            'hoursAgo' => $hoursAgo,
            'minutesAgo' => $minutesAgo,
            'type' => 'contact'
          ]);
        } else {
          // Order Notification
          $notificationUser = User::find($notification->data['user_id']);
          $notificationOrder = Order::find($notification->data['order_id']);
          $notificationCard = $notificationOrder->card;

          array_push($notificationsData, [
            'user' => $notificationUser,
            'order' => $notificationOrder,
            'card' => $notificationCard,
            'id' => $notification->id,
            'seen' => $notification->read_at,
            'daysAgo' => $daysAgo,
            'hoursAgo' => $hoursAgo,
            'minutesAgo' => $minutesAgo,
            'type' => 'order'
          ]);
        }
      }
    }

    return array_merge(parent::share($request), [
      'auth' => [
        'user' => $user ? $user : null,
        'hasOrders' => $hasOrders,
      ],
      'notifications' => $notifications,
      'notificationsData' => $notificationsData,
      'notificationsCount' => count($unreadNotifications)
    ]);
  }
}
