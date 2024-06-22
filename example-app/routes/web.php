<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NoticeController;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Home Route
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/', [HomeController::class, 'getNotices'])->name('home');


// Notices CRUD Routes (Admin)

Route::get('/admin/notices', [NoticeController::class, 'index'])->name('admin.notices.index');
Route::get('/admin/notices/create', [NoticeController::class, 'create'])->name('admin.notices.create');
Route::post('/admin/notices', [NoticeController::class, 'store'])->name('admin.notices.store');
Route::get('/admin/notices/{notice}/edit', [NoticeController::class, 'edit'])->name('admin.notices.edit');
Route::put('/admin/notices/{notice}', [NoticeController::class, 'update'])->name('admin.notices.update');
Route::delete('/admin/notices/{notice}', [NoticeController::class, 'destroy'])->name('admin.notices.destroy');