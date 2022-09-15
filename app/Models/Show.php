<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Show extends Model
{
    use HasFactory;

    public $guarded = ['id'];
    public $hidden = ['concert_id'];

    public $timestamps = false;
    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    public function concert()
    {
        return $this->belongsTo(Concert::class);
    }

    public function rows()
    {
        return $this->hasMany(Row::class);
    }
}
