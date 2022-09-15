<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    public $guarded = ['id'];
    public $hidden = ['concert_id'];

    public $timestamps = false;

    public function concert()
    {
        return $this->belongsTo(Concert::class);
    }
}
