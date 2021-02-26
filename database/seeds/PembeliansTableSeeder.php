<?php

use App\Pembelian;
use App\Product;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PembeliansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tanggal = Carbon::now('Asia/Jakarta');

        Pembelian::create([
            'supplier_id' => 1,
            'product_id' => 3,
            'total_barang' => 5,
            'total_biaya' => 100000,
            'created_at' => $tanggal->subDay(3),
            'updated_at' => $tanggal->subDay(3)
        ]);

        Pembelian::create([
            'supplier_id' => 1,
            'product_id' => 2,
            'total_barang' => 5,
            'total_biaya' => 95000,
            'created_at' => $tanggal->subDay(2),
            'updated_at' => $tanggal->subDay(2)
        ]);

        Pembelian::create([
            'supplier_id' => 1,
            'product_id' => 1,
            'total_barang' => 5,
            'total_biaya' => 55000,
            'created_at' => $tanggal->subDay(1),
            'updated_at' => $tanggal->subDay(1)
        ]);
    }
}
