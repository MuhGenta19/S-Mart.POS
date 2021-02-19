<?php

use App\Supplier;
use Illuminate\Database\Seeder;

class SuppliersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Supplier::create([
            'name' => 'Supplier',
            'alamat' => 'Pondok Programmer, Kretek, Bantul, Yogyakarta',
            'telepon' => '0888888888'
        ]);
    }
}
