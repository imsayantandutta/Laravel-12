<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;

// --------------------
// Basic static pages
// --------------------
Route::get('/', function () {
    return view('home');
});

Route::get('/checkout', function () {
    return view('checkout');
});

Route::get('/thankyou', function () {
    return view('thankyou');
})->name('thankyou');

// --------------------
// Quiz related routes
// --------------------

// Show one question at a time for a specific product
Route::get('/quiz/{productId}/{questionIndex?}', [QuizController::class, 'show'])
    ->name('quiz.show');

// Save user’s selected answer and move to next question
Route::post('/quiz/{productId}/{questionIndex}', [QuizController::class, 'store'])
    ->name('quiz.store');


Route::get('/', [ProductController::class, 'index'])->name('home');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout/submit', [CheckoutController::class, 'submit'])->name('checkout.submit');