<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $userDirektur = User::create([
            'name' => 'Direktur',
            'email' => 'direktur@kasirpintar.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123456'),
            'nip' => '1234',
            'jabatan' => 'DIREKTUR',
            'remember_token' => Str::random(10)
        ]);

        $userFinance = User::create([
            'name' => 'Finance',
            'email' => 'finance@kasirpintar.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123456'),
            'nip' => '1235',
            'jabatan' => 'FINANCE',
            'remember_token' => Str::random(10)
        ]);

        $userStaff = User::create([
            'name' => 'Staff',
            'email' => 'staff@kasirpintar.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123456'),
            'nip' => '1236',
            'jabatan' => 'STAFF',
            'remember_token' => Str::random(10)
        ]);

        $this->call([
            ShieldSeeder::class,
        ]);

        $userDirektur->assignRole('Direktur');
        $userFinance->assignRole('Finance');
        $userStaff->assignRole('Staff');

        $userDirektur->assignRole('super_admin');
    }
}
