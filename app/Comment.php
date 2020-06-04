<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id','project_id','task_id','comment', 'files'
    ];

    public function member()
    {
        return $this->belongsTo('App\User','user_id','id');
    }
}
