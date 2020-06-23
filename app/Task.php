<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id';
    protected static $logName = 'task';
    protected $fillable = [
        'name','project_id','assignee_id','label', 'due_on','task_list','details'
    ];

    public function assignee()
    {
        return $this->belongsTo('App\User','assignee_id','id');
    }

}
