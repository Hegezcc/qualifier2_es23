<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Concert;
use App\Models\Location;
use App\Models\Show;
use App\Models\Row;
use App\Models\Seat;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Concert::create(['artist' => 'Opus']);
        Location::create(['name' => 'Oper Graz', 'concert_id' => 1]);
        Show::create(['start' => '2022-09-12T20:00:00Z', 'end' => '2022-09-12T23:00:00Z', 'concert_id' => 1]);
        Concert::create(['artist' => 'Wanda']);
        Location::create(['name' => 'Merkur Arena', 'concert_id' => 2]);
        Show::create(['start' => '2022-09-12T20:00:00Z', 'end' => '2022-09-12T23:00:00Z', 'concert_id' => 2]);
        Concert::create(['artist' => 'Volbeat']);
        Location::create(['name' => 'Merkur Arena', 'concert_id' => 3]);
        Show::create(['start' => '2022-09-10T20:00:00Z', 'end' => '2022-09-10T23:00:00Z', 'concert_id' => 3]);
        Concert::create(['artist' => 'Opus']);
        Location::create(['name' => 'Merkur Arena', 'concert_id' => 4]);
        Show::create(['start' => '2022-09-10T20:00:00Z', 'end' => '2022-09-10T23:00:00Z', 'concert_id' => 4]);
        Concert::create(['artist' => 'Volbeat']);
        Location::create(['name' => 'Merkur Arena', 'concert_id' => 5]);
        Show::create(['start' => '2022-09-15T20:00:00Z', 'end' => '2022-09-15T23:00:00Z', 'concert_id' => 5]);
        Concert::create(['artist' => 'Wanda']);
        Location::create(['name' => 'Oper Graz', 'concert_id' => 6]);
        Show::create(['start' => '2022-09-15T20:00:00Z', 'end' => '2022-09-15T23:00:00Z', 'concert_id' => 6]);
        Concert::create(['artist' => 'Bilderbuch']);
        Location::create(['name' => 'Hauptplatz', 'concert_id' => 7]);
        Show::create(['start' => '2022-09-15T20:00:00Z', 'end' => '2022-09-15T23:00:00Z', 'concert_id' => 7]);

        $rows = [];
        $seats = [];
        $shows = Show::all();

        foreach ($shows as $show) {
            $max = rand(2,6) * 5;

            for ($i=1; $i < rand(3,8); $i++) { 
                $rows[] = ['name' => "Floor $i", 'total' => $max, 'show_id' => $show->id];

                $selected = [];
                for ($j=0; $j < rand(0, 5); $j++) { 
                    $seat = rand(1, $max);
                    if (in_array($seat, $selected)) {
                        continue;
                    }
                    $selected[] = $seat;
                    $seats[] = ['row_id' => count($rows), 'seat' => $seat];
                }
            }
        }

        Row::insert($rows);
        Seat::insert($seats);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
