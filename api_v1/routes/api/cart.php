<?php
//:::::::::::::>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> Project

use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'cart', 'middleware' => ['authorization:1,2']], function () {
    // View all cart items
    Route::get('/', [CartController::class, 'viewCart']);
    
    // Add item to cart
    Route::post('/', [CartController::class, 'addToCart']);
    
    // Remove item from cart
    Route::delete('/{id}', [CartController::class, 'removeFromCart']);
});
