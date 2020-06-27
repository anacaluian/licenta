<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberToProject extends Model
{
    use SoftDeletes;
    protected $table = 'members_to_projects';
    protected $primaryKey = 'id';
    protected $fillable = [
        'member_id','project_id'
    ];

    public function member(){
        return $this->hasOne('App\User', 'id', 'member_id');
    }

    public function project(){
        return $this->hasOne('App\Project', 'id', 'project_id');
    }

}
