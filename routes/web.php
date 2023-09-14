<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [CardController::class, 'index'])->name('dashboard');

Route::get('/cards', [CardController::class, 'allCards'])->name('cards');
Route::get('/cards/{type}', [CardController::class, 'singleCard']);
Route::get('/contact', [UserController::class, 'showContact'])->name('contact');
Route::post('/contact', [UserController::class, 'contact']);

Route::middleware('auth')->group(function() {
  Route::post('/checkout', [PaymentController::class, 'checkout']);
  Route::get('/success', [PaymentController::class, 'success'])->name('paymentsuccess');
  Route::get('/orders', [CardController::class, 'showOrders'])->name('orders');
});

Route::get('/admin/login', [AdminController::class, 'showLogin'])
->middleware('guest')
->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login']);

Route::middleware('check_admin')->group(function() {
  Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
  Route::get('/admin/user/{id}', [AdminController::class, 'showUser'])->name('admin.user');
  Route::get('/admin/user/{id}/edit', [AdminController::class, 'showEditUser'])->name('admin.editUser');
  Route::post('/admin/user/{id}/edit', [AdminController::class, 'editUser']);

  Route::get('/admin/orders', [AdminController::class, 'showOrders'])->name('admin.orders');
});