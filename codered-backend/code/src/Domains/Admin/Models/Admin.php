<?php

namespace App\Domains\Admin\Models;

use App\Domains\Uploads\Models\Upload;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Domains\Admin\Traits\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Admin extends Authenticatable
{
    use Notifiable;

    const EXPIRATION_TIME = 15; // minutes


    public $incrementing = false;

    protected $keyType = "string";

    protected $fillable = [
        "name", 'email', 'password', 'image_id', 'is_super_admin', 'activation', 'role_id',
        'phone'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = ['created_at', 'updated_at', 'otp_created_at'];

    protected $attributes = [
        'activation'     => 0,
        'is_super_admin' => 0
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function($model) {
            $model->id = Str::uuid();
        });

    }

    protected function setPasswordAttribute(?string $password)
    {
        if(!empty($password))
            $this->attributes['password'] = bcrypt($password);
    }

    public function setImageIdAttribute(?string $image_id)
    {
        if(!empty($image_id)) {
            return $this->attributes['image_id'] = $image_id;
        }

        return $this->attributes['image_id'] = null;
    }

    public function image()
    {
        return $this->hasOne(Upload::class, "id", "image_id")
            ->withDefault([
                'id'        => 1,
                "path"      => 'assets/imgs/default-profile.png',
                "size"      => 1,
                "mime_type" => 'image/png',
                'in_use'    => 1
            ]);
    }

    public function role()
    {
        return $this->hasOne(Role::class, "id", "role_id");
    }

    public function abilities()
    {
        return $this->role->abilities()->pluck("ability");
    }

    /**
     * User tokens relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tokens()
    {
        return $this->hasMany(Admin2fToken::class);
    }
}
