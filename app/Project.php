<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name','state','owner', 'members', 'clients',
    ];

    public function members(){
        return $this->hasMany('App\User', 'id', 'members');
    }

}
