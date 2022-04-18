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

    public function addLog($log)
    {
        $this->content = $log;

    }

    public function warning($log)
    {
        $this->content = sprintf("app.WARNING %s Trace: %s \n", $log, json_encode(debug_backtrace()));
    }

    public function info($log)
    {
        $this->content = sprintf("app.INFO %s \n", $log);
    }

    public function error($log)
    {
        $this->content = sprintf("app.ERROR %s Trace: %s \n", $log, json_encode(debug_backtrace()));
    }

    public function __destruct()
    {
        file_put_contents(Storage::disk('logs')->path('') . $this->filename, $this->content, FILE_APPEND);
    }
}
