<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id';
    protected static $logName = 'note';
    protected $fillable = [
        'title','content','project_id','created_by'
    ];

    public function author(){
        return $this->belongsTo('App\User', 'created_by', 'id');
    }
}
