<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class LogServiceProvider
{
    protected $filename;

    protected $content;

    public function __construct()
    {
        $this->filename = 'logs.txt';
        $this->content = '';
    }

    public function log($log)
    {
        $time = sprintf("[%d-%d-%d %d:%d:%d]", Carbon::now()->year, Carbon::now()->month, Carbon::now()->day,Carbon::now()->hour, Carbon::now()->minute, Carbon::now()->second);
        $this->content = sprintf("app.LOG%s %s Trace: %s \n", $time, $log, json_encode(debug_backtrace()));
    }

    public function warning($log)
    {
        $time = sprintf("[%d-%d-%d %d:%d:%d]", Carbon::now()->year, Carbon::now()->month, Carbon::now()->day,Carbon::now()->hour, Carbon::now()->minute, Carbon::now()->second);
        $this->content = sprintf("app.WARNING%s %s Trace: %s \n", $time, $log, json_encode(debug_backtrace()));
    }

    public function info($log)
    {
        $time = sprintf("[%d-%d-%d %d:%d:%d]", Carbon::now()->year, Carbon::now()->month, Carbon::now()->day,Carbon::now()->hour, Carbon::now()->minute, Carbon::now()->second);
        $this->content = sprintf("app.INFO%s %s \n",$time, $log);
    }

    public function error($log)
    {
        $time = sprintf("[%d-%d-%d %d:%d:%d]", Carbon::now()->year, Carbon::now()->month, Carbon::now()->day,Carbon::now()->hour, Carbon::now()->minute, Carbon::now()->second);
        $this->content = sprintf("app.ERROR%s %s Trace: %s \n",$time, $log, json_encode(debug_backtrace()));
    }

    public function __destruct()
    {
        file_put_contents(Storage::disk('logs')->path('') . $this->filename, $this->content, FILE_APPEND);
    }
}
