<?php

namespace App\Domains\OpenApi\Models;

use App\Domains\UserActivity\Traits\Loggable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use INTCore\OneARTFoundation\Model;
use Laravel\Passport\HasApiTokens;
use Framework\Traits\SelectColumnTrait;

class IntegrationUser extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use HasApiTokens, Notifiable, Loggable, SelectColumnTrait;
    use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;

    protected $fillable = ["email", "password"];

    protected $hidden = [
        'password'
    ];
}
