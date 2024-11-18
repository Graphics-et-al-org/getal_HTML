<?php

namespace App\Models;

use App\Models\Traits\Method\RoleMethod;
use App\Models\Traits\Method\UserMethod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class LocalUser extends Authenticatable
{
    use HasFactory, Notifiable, UserMethod, RoleMethod;

    public const TYPE_ADMIN = 'admin';
    public const TYPE_USER = 'user';



    // explicitly define tables
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
