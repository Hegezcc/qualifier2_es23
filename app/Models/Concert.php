<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concert extends Model
{
    use HasFactory;

    public $guarded = ['id'];
    public $hidden = ['rows'];

    public $timestamps = false;

    public function location()
    {
        return $this->hasOne(Location::class);
    }

    public function shows()
    {
        return $this->hasMany(Show::class);
    }
}
