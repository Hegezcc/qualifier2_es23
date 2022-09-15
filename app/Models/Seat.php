<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    public $guarded = ['id'];
    public $hidden = ['row_id', 'reservation_id'];

    public $timestamps = false;

    public function row()
    {
        return $this->belongsTo(Row::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
