<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $guarded = ['id'];
    public $casts = [
        'expires_at' => 'datetime',
    ];

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
}
