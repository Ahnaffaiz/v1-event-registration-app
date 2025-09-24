<?php

use App\Livewire\ActivityCheckin;
use App\Livewire\CameraCheckin;
use App\Livewire\Dashboard;
use App\Livewire\Event;
use App\Livewire\MultipleCheckin;
use App\Livewire\QRCodeCheckin;
use App\Livewire\SingleCheckin;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\RoleMiddleware;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    RoleMiddleware::class . ':admin'
])->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/event', Event::class)->name('event');
    Route::get('/checkin/single', SingleCheckin::class)->name('single-checkin');
    Route::get('/checkin/multiple', MultipleCheckin::class)->name('multiple-checkin');
    Route::get('/checkin/activity', ActivityCheckin::class)->name('activity-checkin');

    //display
    Route::get('display/event', App\Livewire\Display\EventCheckin::class)->name('display-event-checkin');
    Route::get('display/activity/{activity}', App\Livewire\Display\ActivityCheckin::class)->name('display-activity-checkin');

    Route::get('/camera', CameraCheckin::class);
    Route::get('/qr-code', QRCodeCheckin::class);
});
