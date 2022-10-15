<?php

namespace Database\Seeders\Landlord;

use App\Models\Landlord\LandlordUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LandlordUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // php artisan db:seed --class="Database\Seeders\Landlord\LandlordUserSeeder"
        LandlordUser::create([
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
        ]);
    }
}
