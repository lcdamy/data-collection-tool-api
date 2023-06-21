<?php

namespace Database\Seeders;
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
        DB::table('hospitals')->insert((['name' => 'Nyarugenge DH', 'location' => 'Nyarugenge', 'created_at' => $dt->format('Y-m-d H:i:s'), 'updated_at' => $dt->format('Y-m-d H:i:s')]));
        DB::table('hospitals')->insert((['name' => 'Muhima DH', 'location' => 'Nyarugenge', 'created_at' => $dt->format('Y-m-d H:i:s'), 'updated_at' => $dt->format('Y-m-d H:i:s')]));
        DB::table('hospitals')->insert((['name' => 'Nemba DH', 'location' => 'Gakenke', 'created_at' => $dt->format('Y-m-d H:i:s'), 'updated_at' => $dt->format('Y-m-d H:i:s')]));
        DB::table('hospitals')->insert((['name' => 'Ruli DH', 'location' => 'Gakenke', 'created_at' => $dt->format('Y-m-d H:i:s'), 'updated_at' => $dt->format('Y-m-d H:i:s')]));
        DB::table('hospitals')->insert((['name' => 'Gatonde DH', 'location' => 'Gakenke', 'created_at' => $dt->format('Y-m-d H:i:s'), 'updated_at' => $dt->format('Y-m-d H:i:s')]));
        DB::table('hospitals')->insert((['name' => 'Rutongo DH', 'location' => 'Rulindo', 'created_at' => $dt->format('Y-m-d H:i:s'), 'updated_at' => $dt->format('Y-m-d H:i:s')]));
        DB::table('hospitals')->insert((['name' => 'Kinihira DH', 'location' => 'Rulindo', 'created_at' => $dt->format('Y-m-d H:i:s'), 'updated_at' => $dt->format('Y-m-d H:i:s')]));
        DB::table('hospitals')->insert((['name' => 'Kibilizi DH', 'location' => 'Gisagara', 'created_at' => $dt->format('Y-m-d H:i:s'), 'updated_at' => $dt->format('Y-m-d H:i:s')]));
        DB::table('hospitals')->insert((['name' => 'Gakoma DH', 'location' => 'Gisagara', 'created_at' => $dt->format('Y-m-d H:i:s'), 'updated_at' => $dt->format('Y-m-d H:i:s')]));
        DB::table('hospitals')->insert((['name' => 'Kibuye RH', 'location' => 'Karongi', 'created_at' => $dt->format('Y-m-d H:i:s'), 'updated_at' => $dt->format('Y-m-d H:i:s')]));
        DB::table('hospitals')->insert((['name' => 'Kirinda DH', 'location' => 'Karongi', 'created_at' => $dt->format('Y-m-d H:i:s'), 'updated_at' => $dt->format('Y-m-d H:i:s')]));
        DB::table('hospitals')->insert((['name' => 'Mugonera DH', 'location' => 'Karongi', 'created_at' => $dt->format('Y-m-d H:i:s'), 'updated_at' => $dt->format('Y-m-d H:i:s')]));
        DB::table('hospitals')->insert((['name' => 'Bushenge DH', 'location' => 'Nyamasheke', 'created_at' => $dt->format('Y-m-d H:i:s'), 'updated_at' => $dt->format('Y-m-d H:i:s')]));
        DB::table('hospitals')->insert((['name' => 'Kibogora DH', 'location' => 'Nyamasheke', 'created_at' => $dt->format('Y-m-d H:i:s'), 'updated_at' => $dt->format('Y-m-d H:i:s')]));
        DB::table('hospitals')->insert((['name' => 'Mibilizi DH', 'location' => 'Rusizi', 'created_at' => $dt->format('Y-m-d H:i:s'), 'updated_at' => $dt->format('Y-m-d H:i:s')]));
        DB::table('hospitals')->insert((['name' => 'Gihundwe DH', 'location' => 'Rusizi', 'created_at' => $dt->format('Y-m-d H:i:s'), 'updated_at' => $dt->format('Y-m-d H:i:s')]));
    }
}
