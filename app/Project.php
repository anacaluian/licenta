<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id';
    protected $fillable = [
        'name','state','owner','support_email', 'members', 'clients','tasks_list'
    ];

    public function members()
    {
        return $this->hasMany('App\MemberToProject','project_id','id');
    }
}
