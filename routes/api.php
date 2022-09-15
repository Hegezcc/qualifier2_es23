<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Models\Reservation;
use App\Models\Concert;
use App\Models\Show;
use App\Models\Row;
use App\Models\Seat;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/concerts', function () {
    return ['concerts' => Concert::with('location', 'shows')->get()];
});

Route::get('/concerts/{id}', function (int $id) {
    $concert = Concert::with('location', 'shows')->find($id);
    if (!$concert) {
        return response()->json(["error" => "A concert with this ID does not exist"], 404);
    }

    return ['concert' => $concert];
});

Route::get('/concerts/{concert}/shows/{show}/seating', function (Concert $concert, Show $show) {
    if ($show->concert_id !== $concert->id) {
        return response()->json(['error' => 'A concert or show with this ID does not exist'], 404);    
    }

    return ['rows' => $show->rows];
})->missing(function () {
    return response()->json(['error' => 'A concert or show with this ID does not exist'], 404);
});

Route::post('/concerts/{concert}/shows/{show}/reservation', function (Concert $concert, Show $show) {
    if ($show->concert_id !== $concert->id) {
        return response()->json(['error' => 'A concert or show with this ID does not exist'], 404);    
    }
    
    $rules = [
        'reservation_token' => 'required|string|exists:reservations,token|bail',
        'reservations' => ['sometimes', 'array', function ($attr, $val, $fail) use ($concert) {
            foreach ($val as $s) {
                $row = Row::find($s['row']);

                if (!$row 
                    || $s['seat'] < 1 
                    || $s['seat'] > $row->total 
                    || $row->show->concert_id !== $concert->id 
                    ) {

                    $fail("Seat {$s['seat']} in row {$s['row']} is invalid.");
                    return null;
                }

                if (Seat::where('row_id', $s['row'])->pluck('seat')->contains($s['seat'])) {
                    $fail("Seat {$s['seat']} in row {$s['row']} is already taken.");
                    return null;
                }
            }
        }],
        'duration' => 'sometimes|integer|min:1|max:300',
    ];

    $validator = Validator::make(request()->all(), $rules);

    if ($validator->fails()) {
        $err = $validator->errors();
        if ($err->has('reservation_token')) {
            return response()->json(['error' => 'Invalid reservation token'], 403);
        }

        return response()->json(['error' => 'Validation failed', 'fields' => $err], 422);
    }

    $data = $validator->validated();

    if (!in_array('duration', $data)) {
        $data['duration'] = 300;
    }

    $until = Carbon::now()->addSeconds($data['duration']);

    if (!array_key_exists('reservation_token', $data)) {
        $data['reservation_token'] = strtoupper(\Illuminate\Support\Str::random(10));

        $res = Reservation::create([
            'token' => $data['reservation_token'],
            'expires_at' => $until,
        ]);
    } else {
        $res = Reservation::where('token', $data['reservation_token'])->first();
        $res->expires_at = $until;
        $res->save();
    }

    if (empty($data['reservations'])) {
        $res->seats()->delete();
    } else {
        $seats = [];
        foreach ($data['reservations'] as $s) {
            $seats[] = [
                'row_id' => $s['row'], 
                'seat' => $s['seat'], 
                'reservation_id' => $res->id
            ];
        }

        Seat::insert($seats);
    }

    return response()->json([
        'reserved' => true,
        'reservation_token' => $data['reservation_token'],
        'reserved_until' => $until,
    ], 201);


})->missing(function () {
    return response()->json(['error' => 'A concert or show with this ID does not exist'], 404);
});