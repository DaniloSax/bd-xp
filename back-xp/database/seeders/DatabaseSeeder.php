<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Modules\User\Domain\Models\Address;
use App\Modules\User\Domain\Models\People;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory()->create([
        //     'name' => 'Danilo Veloso',
        //     'email' => 'danilovsdanilo@gmail.com',
        // ]);

        User::factory(10)->has(
            People::factory()->has(Address::factory())
        )->create();
    }
}
