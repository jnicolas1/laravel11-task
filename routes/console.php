<?php

use App\Console\Commands\DeleteTaskPending;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


//Schedule::command(DeleteTaskPending::class)->at('10:00')->daily();//se ejecuta a las 10 del servidor

Schedule::command(DeleteTaskPending::class)->timezone('America/Lima')->everyMinute();//se ejecuta a las 10 del servidor
