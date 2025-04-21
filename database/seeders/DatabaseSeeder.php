<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            "avatar" => 'https://picsum.photos/id/1/200/300',
            'name' => 'Toni Sahani',
            'role' => 'admin',
            'bio' => 'Ini adalah akun Admin.',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
        ]);

        $this->call(CountrySeeder::class);
        $this->call(LanguageSeeder::class);
        User::factory(1)->create();
    }
}
