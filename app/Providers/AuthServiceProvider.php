<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\Register;
use App\Mail\ForgotPassword;
use Illuminate\Support\Str;

class AuthServiceProvider extends ServiceProvider
{

}
