<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Pustakawan pertama
        User::create([
            'name'     => 'Ahmad Fauzi',
            'email'    => 'pustakawan@alqalam.sch.id',
            'password' => Hash::make('password'),
            'role'     => 'pustakawan',
            'status'   => 'aktif',
        ]);

        // Pustakawan kedua
        User::create([
            'name'     => 'Siti Rahayu',
            'email'    => 'siti.rahayu@alqalam.sch.id',
            'password' => Hash::make('password'),
            'role'     => 'pustakawan',
            'status'   => 'aktif',
        ]);

        // Anggota demo utama
        User::create([
            'name'     => 'Budi Santoso',
            'email'    => 'budi.santoso@siswa.alqalam.sch.id',
            'password' => Hash::make('password'),
            'role'     => 'anggota',
            'status'   => 'aktif',
        ]);

        // Anggota demo kedua
        User::create([
            'name'     => 'Dewi Anggraini',
            'email'    => 'dewi.anggraini@siswa.alqalam.sch.id',
            'password' => Hash::make('password'),
            'role'     => 'anggota',
            'status'   => 'aktif',
        ]);

        // Anggota demo ketiga (status suspended untuk testing)
        User::create([
            'name'     => 'Rizky Pratama',
            'email'    => 'rizky.pratama@siswa.alqalam.sch.id',
            'password' => Hash::make('password'),
            'role'     => 'anggota',
            'status'   => 'suspended',
        ]);

        // Generate 25 anggota tambahan dengan Factory
        User::factory()->count(25)->anggota()->create();

        $this->command->info('Seeder User selesai: 2 pustakawan + 30 anggota ditambahkan.');
    }
}