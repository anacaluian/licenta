<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientToProject extends Model
{
    use SoftDeletes;
    protected $table = 'clients_to_projects';
    protected $primaryKey = 'id';
    protected $fillable = [
        'client_id','project_id'
    ];

    public function member(){
        return $this->hasOne('App\User', 'id', 'client_id');
    }
}
