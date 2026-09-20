<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// spatie/laravel-backup, config/backup.php. Ships to the default `local`
// disk (storage/app/Laravel), fine for development; point it at an
// off-server disk (S3, ...) before relying on this in production, since a
// backup living next to the data it protects defends against nothing.
Schedule::command('backup:run')->daily();
Schedule::command('backup:clean')->daily();
