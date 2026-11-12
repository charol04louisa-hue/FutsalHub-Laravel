
<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Periksa booking yang melewati batas pembayaran setiap menit.
Schedule::command('bookings:expire-unpaid')
    ->everyMinute()
    ->withoutOverlapping();
