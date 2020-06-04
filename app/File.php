<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id';
    protected $fillable = [
        'comment_id','file_path','project_id'
    ];

}
