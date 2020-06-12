<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Email extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id';
    protected $fillable = [
        'email_is','from','project_email','received_at','subject','message','task'
    ];

    public function project(){
        return $this->belongsTo('App\Project', 'project_email', 'support_email');
    }

}
