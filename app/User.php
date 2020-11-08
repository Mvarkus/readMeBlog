<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function isAdmin()
    {
        return $this->role->title === 'admin';
    }

    public function votes()
    {
        return $this->hasMany('App\Vote');
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->second_name}";
    }

    public function deleteAvatarFile()
    {
        $storage = env('APP_ENV') === 'testing' ? 'test' : 'public';

        if ($exists = Storage::disk($storage)->exists($this->avatar) && $this->avatar !== 'images/avatars/default.png') {
            return Storage::disk($storage)->delete($this->avatar);
        }

        return false;
    }

}
