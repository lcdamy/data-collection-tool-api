<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HospitalsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dt = new DateTime();
        DB::table('hospitals')->insert((['name' => 'CHUCK', 'location' => 'Nyarugenge', 'created_at' => $dt->format('Y-m-d H:i:s'), 'updated_at' => $dt->format('Y-m-d H:i:s')]));
        DB::table('hospitals')->insert((['name' => 'Biryogo CS', 'location' => 'Nyarugenge', 'created_at' => $dt->format('Y-m-d H:i:s'), 'updated_at' => $dt->format('Y-m-d H:i:s')]));
        DB::table('hospitals')->insert((['name' => 'Nyakabanda', 'location' => 'Kicukiro', 'created_at' => $dt->format('Y-m-d H:i:s'), 'updated_at' => $dt->format('Y-m-d H:i:s')]));
        DB::table('hospitals')->insert((['name' => 'Kinyoni', 'location' => 'Gasabo', 'created_at' => $dt->format('Y-m-d H:i:s'), 'updated_at' => $dt->format('Y-m-d H:i:s')]));
    }
}
