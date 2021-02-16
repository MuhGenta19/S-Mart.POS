<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'photo' => 'https://i.ibb.co/cFZfrYC/administrator.png',
            'telepon' => '0812345678',
            'umur' => 30,
            'alamat' => 'Pondok Programmer, Kretek, Bantul, Yogyakarta'
        ]);
        $admin->assignRole('admin');

        $pimpinan = User::create([
            'name' => 'Pimpinan',
            'email' => 'pimpinan@gmail.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'photo' => 'https://i.ibb.co/cFZfrYC/administrator.png',
            'telepon' => '0887654321',
            'umur' => 30,
            'alamat' => 'Pondok Programmer, Kretek, Bantul, Yogyakarta'
        ]);
        $pimpinan->assignRole('pimpinan');

        $kasir = User::create([
            'name' => 'Kasir',
            'email' => 'kasir@gmail.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'photo' => 'https://i.ibb.co/cFZfrYC/administrator.png',
            'telepon' => '0867854329',
            'umur' => 30,
            'alamat' => 'Pondok Programmer, Kretek, Bantul, Yogyakarta',
        ]);
        $kasir->assignRole('kasir');

        $staff = User::create([
            'name' => 'Staff',
            'email' => 'staff@gmail.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'photo' => 'https://i.ibb.co/cFZfrYC/administrator.png',
            'telepon' => '0823498765',
            'umur' => 30,
            'alamat' => 'Pondok Programmer, Kretek, Bantul, Yogyakarta'
        ]);
        $staff->assignRole('staff');

        $member = User::create([
            'name' => 'Member',
            'email' => 'member@gmail.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'photo' => 'https://i.ibb.co/cFZfrYC/administrator.png',
            'kode_member' => rand(10000,999999999),
            'telepon' => '0829384756',
            'umur' => 30,
            'alamat' => 'Pondok Programmer, Kretek, Bantul, Yogyakarta'
        ]);
        $member->assignRole('member');
    }
}
