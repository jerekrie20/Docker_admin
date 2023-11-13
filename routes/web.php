<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Redirect;
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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/admin', function(){
        // Assuming you have a method to get the current user's token and admin URL
        $customResponse = auth()->user()->getAdminDetails();

        // Set the cookies
        $tokenCookie = cookie('token', $customResponse['token'], 60, '/', null, false, false, 'Lax');
        $tokenTypeCookie = cookie('token_type', 'Bearer', 60, '/', null, false, false, 'Lax');

        return redirect($customResponse['admin_url'])
            ->withCookie($tokenCookie)
            ->withCookie($tokenTypeCookie);
    })->name('admin');



});

require __DIR__.'/auth.php';
