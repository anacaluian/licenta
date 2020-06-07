<?php

namespace App\Providers;

use App\Time;
use Illuminate\Support\ServiceProvider;

class TimeServiceProvider extends ServiceProvider
{
    protected $timeModel;

    public function __construct(Time $time)
    {
        $this->timeModel = $time;
    }
}
