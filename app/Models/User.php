<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

   public function threads()
{
    return $this->hasMany(Thread::class);
}

public function posts()
{
    return $this->hasMany(Post::class);
}

public function likes()
{
    return $this->belongsToMany(Post::class, 'likes');
}

public function getAvatarUrlAttribute()
{
    if ($this->avatar) {
        return asset('storage/avatars/' . $this->avatar);
    }
    return 'https://ui-avatars.com/api/?background=0D8F81&color=fff&name=' . urlencode($this->name);
}
}