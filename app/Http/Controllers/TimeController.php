<?php

namespace App\Http\Controllers;

use App\Providers\TimeServiceProvider;
use Illuminate\Http\Request;

class TimeController extends Controller
{
    protected $timeService;

    public function __construct(TimeServiceProvider $timeService)
    {
        $this->timeService = $timeService;
    }
}
