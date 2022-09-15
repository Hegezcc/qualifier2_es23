<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Row extends Model
{
    use HasFactory;

    public $guarded = ['id'];
    public $hidden = ['show_id', 'total'];
    public $appends = ['seats'];

    public $timestamps = false;

    public function show()
    {
        return $this->belongsTo(Show::class);
    }

    public function seats() : Attribute
    {
        return new Attribute(
            get: fn () => [
                'total' => $this->total,
                'unavailable' => Seat::where('row_id', $this->id)->pluck('seat')->toArray(),
            ]
        );
    }
}
