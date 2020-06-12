<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id';
    protected static $logName = 'file';
    protected $fillable = [
        'comment_id','file_path','project_id','owner','file_name'
    ];

    public function owner(){
       return $this->belongsTo('App\User','owner','id');
    }

}
