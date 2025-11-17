<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::create([
            'name' => 'Gerald',
            'last_name' => 'Manzanilla',
            'document' => 'V23594392',
            'phone' => '+584129765723',
            'email' => 'gm@gmail.com',
            'password' => Hash::make('1234'),
            'email_verified_at' => now(),
        ]);

        $superAdmin->assignRole('Super Admin');

        $admin = User::create([
            'name' => 'Nexa',
            'last_name' => '2.0',
            'document' => 'J-284844/884848',
            'phone' => '+584126713413',
            'email' => 'gruponexa2.0@gmail.com',
            'password' => Hash::make('1234'),
            'email_verified_at' => now(),
        ]);

        $admin->assignRole('Super Admin');
    }
}
