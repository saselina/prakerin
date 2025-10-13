<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Building;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $office = Building::where('name', 'Gedung Office')->first();
        $store  = Building::where('name', 'Gedung Store')->first();
        $sc     = Building::where('name', 'Gedung SC')->first();

        $rooms = [
            // Office
            ['name' => 'R. HRD', 'building_id' => $office->id],
            ['name' => 'R. Purchasing', 'building_id' => $office->id],
            ['name' => 'R. Direktur', 'building_id' => $office->id],
            ['name' => 'R. Finance', 'building_id' => $office->id],
            ['name' => 'R. Accounting', 'building_id' => $office->id],
            ['name' => 'R. Meeting', 'building_id' => $office->id],
            ['name' => 'R. Sales', 'building_id' => $office->id],
            ['name' => 'Receptionist', 'building_id' => $office->id],
            ['name' => 'R. CSU', 'building_id' => $office->id],

            // Store
            ['name' => 'BOD', 'building_id' => $store->id],
            ['name' => 'Store', 'building_id' => $store->id],

            // SC
            ['name' => 'R. Meeting', 'building_id' => $sc->id],
            ['name' => 'R. Service Manager', 'building_id' => $sc->id],
            ['name' => 'R. Warehouse', 'building_id' => $sc->id],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}
