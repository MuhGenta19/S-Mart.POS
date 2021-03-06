<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);

        Role::create([
            'name' => 'pimpinan',
            'guard_name' => 'web'
        ]);

        Role::create([
            'name' => 'kasir',
            'guard_name' => 'web'
        ]);

        Role::create([
            'name' => 'staff',
            'guard_name' => 'web'
        ]);

        Role::create([
            'name' => 'member',
            'guard_name' => 'web'
        ]);
    }
}
