<?php

use App\Penjualan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PenjualansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tanggal = Carbon::now('Asia/Jakarta');

        Penjualan::create([
            'product_id' => 1,
            'jumlah_barang' => 3,
            'total_harga' => 150000,
            'dibayar' => 170000,
            'kembalian' => 20000,
            'member_id' => null,
            'user_id' => 3,
            'created_at' => $tanggal->subDay(3),
            'updated_at' => $tanggal->subDay(3)
        ]);

        Penjualan::create([
            'product_id' => 2,
            'jumlah_barang' => 3,
            'total_harga' => 150000,
            'dibayar' => 170000,
            'kembalian' => 20000,
            'member_id' => 1,
            'user_id' => 3,
            'created_at' => $tanggal->subDay(2),
            'updated_at' => $tanggal->subDay(2)
        ]);

        Penjualan::create([
            'product_id' => 3,
            'jumlah_barang' => 3,
            'total_harga' => 225000,
            'dibayar' => 0,
            'kembalian' => 0,
            'member_id' => null,
            'user_id' => 0,
            'created_at' => $tanggal->subDay(1),
            'updated_at' => $tanggal->subDay(1)
        ]);
    }
}
