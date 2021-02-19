<?php

use App\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name' => 'pakaian'
        ]);

        Category::create([
            'name' => 'makanan pokok'
        ]);

        Category::create([
            'name' => 'snack dan minuman'
        ]);

        Category::create([
            'name' => 'kebutuhan rumah tangga'
        ]);

        Category::create([
            'name' => 'alat tulis dan kantor'
        ]);
    }
}
