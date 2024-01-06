<?php

namespace Database\Factories;

use App\Utils\SlugGenerator;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Main',
            'slug' => SlugGenerator::generate('Main'),
            'private' => true,
            'password' => Hash::make('12345678'),
        ];
    }

    
}
