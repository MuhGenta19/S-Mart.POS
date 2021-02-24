<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(SuppliersTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        $this->call(PembeliansTableSeeder::class);
        $this->call(PenjualansTableSeeder::class);
        $this->call(PengeluaransTableSeeder::class);
        $this->call(AbsentsTableSeeder::class);
    }
}
