<?php

use App\Pengeluaran;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PengeluaransTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tanggal = Carbon::now('Asia/Jakarta');

        Pengeluaran::create([
            'jenis_pengeluaran' => 'Beli Lampu',
            'nominal' => 25000,
            'created_at' => $tanggal->subDay(3),
            'updated_at' => $tanggal->subDay(3)
        ]);

        Pengeluaran::create([
            'jenis_pengeluaran' => 'Beli Nota dll',
            'nominal' => 25000,
            'created_at' => $tanggal->subDay(2),
            'updated_at' => $tanggal->subDay(2)
        ]);

        Pengeluaran::create([
            'jenis_pengeluaran' => 'Bayar Tagihan Air',
            'nominal' => 50000,
            'created_at' => $tanggal->subDay(1),
            'updated_at' => $tanggal->subDay(1)
        ]);
    }
}
