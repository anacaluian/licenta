<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Time extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id';
    protected static $logName = 'time';
    protected $fillable = [
        'time','project_id','task_id','description', 'member_id','date'
    ];

    public function task(){
        return $this->belongsTo('App\Task','task_id','id');
    }
}
