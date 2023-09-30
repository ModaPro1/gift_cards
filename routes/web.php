<?php

use App\Http\Controllers\Admin\CardsController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CardController::class, 'index'])
->middleware('admin_guest')
->name('dashboard');

Route::get('/cards', [CardController::class, 'allCards'])->name('cards');
Route::get('/cards/{type}', [CardController::class, 'singleCard']);
Route::get('/contact', [UserController::class, 'showContact'])->name('contact');
Route::post('/contact', [UserController::class, 'contact']);

Route::middleware('auth')->group(function() {
  Route::post('/checkout', [PaymentController::class, 'checkout']);
  Route::get('/success', [PaymentController::class, 'success'])->name('paymentsuccess');
  Route::get('/orders', [CardController::class, 'showOrders'])->name('orders');
});

Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login']);

Route::middleware('check_admin')->prefix('/admin')->group(function() {
  // CRUD
  Route::get('/', [AdminController::class, 'index'])->name('admin.index');
  Route::get('/user/{id}', [AdminController::class, 'showUser'])->name('admin.user');
  Route::get('/user/{id}/edit', [AdminController::class, 'showEditUser'])->name('admin.edit');
  Route::post('/user/{id}/edit', [AdminController::class, 'editUser']);
  Route::post('/user/{id}/delete', [AdminController::class, 'deleteUser'])->name('admin.delete');
  
  // Cards
  Route::get('/cards', [CardsController::class, 'showCards'])->name('admin.cards');
  Route::post('/cards/changeCategory', [CardsController::class, 'changeCategoryName'])->name('admin.changeCategoryName');
  Route::get('/card/{id}', [CardsController::class, 'showEditCard'])->name('admin.editCard');
  Route::post('/card/{id}', [CardsController::class, 'editCard']);
  Route::get('/cards/add', [CardsController::class, 'showAddCard'])->name('admin.addCard');
  Route::post('/cards/add', [CardsController::class, 'addCard']);
  Route::post('/cards/delete', [CardsController::class, 'deleteCard'])->name('admin.deleteCard');
  Route::get('/category/add', [CardsController::class, 'showAddCat'])->name('admin.showAddCat');
  Route::post('/category/add', [CardsController::class, 'addCat'])->name('admin.addCat');
  Route::post('/category/delete', [CardsController::class, 'deleteCat'])->name('admin.deleteCat');

  // Orders
  Route::get('/orders', [AdminController::class, 'showOrders'])->name('admin.orders');
  Route::get('/orders/{notificationId}', [AdminController::class, 'showOrder'])->name('admin.order');
  Route::post('/orders/refuse', [AdminController::class, 'refuseOrder'])->name('admin.refuseCard');
  Route::post('/orders/send', [AdminController::class, 'sendCard'])->name('admin.sendCard');

  // Contacts
  Route::get('/contacts', [AdminController::class, 'showContacts'])->name('admin.contacts');
  Route::get('/contacts/{id}', [AdminController::class, 'showContact'])->name('admin.contact');

  // Notifications
  Route::get('/notifications', [AdminController::class, 'showNotifications'])->name('admin.notifications');

  // Others
  Route::get('/search', [AdminController::class, 'search'])->name('admin.search');
  Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
  Route::get('/add-user', [AdminController::class, 'showAddUser'])->name('admin.addUser');
  Route::post('/add-user', [AdminController::class, 'addUser']);

});