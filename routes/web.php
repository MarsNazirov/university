<?php

use App\Http\Controllers\RoomController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/students', [StudentController::class, 'index'])->name('students');
Route::patch('/students/{student}/evict', [StudentController::class, 'evict'])->name('students.evict');

Route::get('/rooms', [RoomController::class, 'index'])->name('rooms');
Route::get('/rooms/{room}/cheсkin', [RoomController::class, 'create'])->name('rooms.create');
Route::patch('/rooms/{room}/checkin', [RoomController::class, 'update'])->name('rooms.update');
