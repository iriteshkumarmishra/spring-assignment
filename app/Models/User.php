<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'spr_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'age',
        'points',
        'address_id',
        'qr_code_path',
        'created_at',
        'updated_at',
    ];

    public function address()
    {
        return $this->belongsTo(UserAddress::class, 'address_id');
    }

    public function winners()
    {
        return $this->hasMany(Winner::class, 'user_id');
    }
}
