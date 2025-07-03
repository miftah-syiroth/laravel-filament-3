<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// ubah jadi setiap menit
Schedule::call(function () {
    Artisan::call('delete:unused-files');
})->everyMinute();
