<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $table = 'spr_user_addresses';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'address',
        'city',
        'state',
        'zip',
        'country',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'address_id');
    }

    public function fullAddress(): string
    {
        return $this->address
        . ', ' . $this->city
        . ', ' . $this->state
        . ' ' . (strpos($this->zip, "-") != false ? substr($this->zip, 0, strpos($this->zip, "-")) : $this->zip);
    }

}
