<?php

use App\Product;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'name' => 'Kaos Oblong',
            'uid' => rand(1000,9999999999),
            'harga_beli' => 30000,
            'harga_jual' => 50000,
            'category_id' => 1,
            'merek' => 'Uniqlo',
            'stok' => 100,
            'diskon' => 5000
        ]);

        Product::create([
            'name' => 'Celana Pendek',
            'uid' => rand(1000,9999999999),
            'harga_beli' => 30000,
            'harga_jual' => 50000,
            'category_id' => 1,
            'merek' => 'Uniqlo',
            'stok' => 100,
            'diskon' => 5000
        ]);

        Product::create([
            'name' => 'Celana Panjang',
            'uid' => rand(1000,9999999999),
            'harga_beli' => 50000,
            'harga_jual' => 75000,
            'category_id' => 1,
            'merek' => 'Uniqlo',
            'stok' => 100,
            'diskon' => 5000
        ]);

        Product::create([
            'name' => 'Jaket',
            'uid' => rand(1000,9999999999),
            'harga_beli' => 50000,
            'harga_jual' => 75000,
            'category_id' => 1,
            'merek' => 'Uniqlo',
            'stok' => 100,
            'diskon' => 5000
        ]);

        Product::create([
            'name' => 'Beras Curah 1kg',
            'uid' => rand(1000,9999999999),
            'harga_beli' => 10000,
            'harga_jual' => 15000,
            'category_id' => 2,
            'merek' => 'Curah',
            'stok' => 100,
            'diskon' => 500
        ]);

        Product::create([
            'name' => 'Aqua Air Mineral 220ml',
            'uid' => rand(1000,9999999999),
            'harga_beli' => 20000,
            'harga_jual' => 24000,
            'category_id' => 3,
            'merek' => 'Aqua',
            'stok' => 100,
            'diskon' => 100,
        ]);

        Product::create([
            'name' => 'Roma Biskuit Kelapa',
            'uid' => rand(1000,9999999999),
            'harga_beli' => 19000,
            'harga_jual' => 23000,
            'category_id' => 3,
            'merek' => 'Roma',
            'stok' => 100,
            'diskon' => 100,
        ]);

        Product::create([
            'name' => 'Momogi Cokelat Snack 200g',
            'uid' => rand(1000,9999999999),
            'harga_beli' => 14000,
            'harga_jual' => 17000,
            'category_id' => 3,
            'merek' => 'Juara Snack',
            'stok' => 100,
            'diskon' => 500,
        ]);

        Product::create([
            'name' => 'Lifebuoy Cool Fresh Sabun batang 75Gr',
            'uid' => rand(1000,9999999999),
            'harga_beli' => 1000,
            'harga_jual' => 3000,
            'category_id' => 4,
            'merek' => 'Lifebuoy',
            'stok' => 100,
            'diskon' => 500,
        ]);

        Product::create([
            'name' => 'Rinso Matic Deterjen Bubuk',
            'uid' => rand(1000,9999999999),
            'harga_beli' => 15000,
            'harga_jual' => 18000,
            'category_id' => 4,
            'merek' => 'Rinso',
            'stok' => 100,
            'diskon' => 500,
        ]);

        Product::create([
            'name' => 'Stella Air Freshener Refill Green 225ml',
            'uid' => rand(1000,9999999999),
            'harga_beli' => 27000,
            'harga_jual' => 32000,
            'category_id' => 4,
            'merk' => 'Stella',
            'stok' => 100,
            'diskon' => 500,
        ]);

        Product::create([
            'name' => 'Buku Tulis Sinar Dunia 38 Lembar',
            'uid' => rand(1000,9999999999),
            'harga_beli' => 21000,
            'harga_jual' => 35000,
            'category_id' => 5,
            'merk' => 'Sinar Dunia',
            'stok' => 100,
            'diskon' => 500,
        ]);

        Product::create([
            'name' => 'Pensil Faber-Castell 2B',
            'uid' => rand(1000,9999999999),
            'harga_beli' => 500,
            'harga_jual' => 1000,
            'category_id' => 5,
            'merek' => "Faber-Castell",
            'stok' => 100,
            'diskon' => 0,
        ]);
    }
}
