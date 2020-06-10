<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id';
    protected $fillable = [
        'name','state','owner','support_email','tasks_list'
    ];

    public function members()
    {
        return $this->hasMany('App\MemberToProject','project_id','id');
    }

    public function tasks()
    {
        return $this->hasMany('App\Task','project_id','id');
    }
    public function clients()
    {
        return $this->hasMany('App\ClientToProject','project_id','id');
    }

    public function clients_project()
    {
        return $this->belongsToMany('App\User', 'clients_to_projects', 'project_id', 'client_id')->whereNull('clients_to_projects.deleted_at');
    }

    public function members_project()
    {
        return $this->belongsToMany('App\User', 'members_to_projects', 'project_id', 'member_id')->whereNull('members_to_projects.deleted_at');
    }

}
