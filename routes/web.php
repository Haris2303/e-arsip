<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/dashboard', function () {
    return redirect('/admin');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::redirect('/admin/login', '/login')->name('filament.admin.auth.login');

require __DIR__ . '/auth.php';
