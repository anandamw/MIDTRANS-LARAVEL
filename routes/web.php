<?php

use Illuminate\Support\Facades\Route;

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

use App\Http\Controllers\PaymentController;


Route::post('/checkout/token', [PaymentController::class, 'getSnapToken'])->name('checkout.token');
Route::post('/checkout/process', [PaymentController::class, 'processPayment'])->name('checkout.process');


Route::get('/', function () {
    return view('payment-page');
})->name('home');
