<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@yakiin.sch.id'],
            [
                'name' => 'Admin YAKIIN',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Create Bendahara User
        $bendahara = User::updateOrCreate(
            ['email' => 'bendahara@yakiin.sch.id'],
            [
                'name' => 'Bendahara YAKIIN',
                'password' => Hash::make('bendahara123image.png'),
                'role' => 'bendahara',
                'is_active' => true,
            ]
        );

        // Create Sample Teacher User
        $guruUser = User::updateOrCreate(
            ['email' => 'guru@yakiin.sch.id'],
            [
                'name' => 'Muhaimin Iskandar, S.Pd',
                'password' => Hash::make('guru123'),
                'role' => 'guru',
                'is_active' => true,
            ]
        );

        // Create Teacher Data only if not exists
        if (!Teacher::where('user_id', $guruUser->id)->exists()) {
            Teacher::create([
                'user_id' => $guruUser->id,
                'nip' => '12345678901234567',
                'alamat' => 'Jl. Kemerdekaan No. 10, Jakarta',
                'no_telepon' => '081234567890',
                'jenis_kelamin' => 'laki-laki',
                'tanggal_lahir' => '1985-01-15',
                'tempat_lahir' => 'Jakarta',
                'pendidikan_terakhir' => 'S1 Pendidikan',
                'tanggal_masuk' => '2020-07-01',
                'nominal' => 4500000,
                'is_active' => true,
            ]);
        }
    }
}