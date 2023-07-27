<?php

namespace Database\Factories;

use App\Modules\User\Domain\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AddressFactory extends Factory
{
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'street' => fake()->streetName(),
            'number' => fake()->buildingNumber(),
            'complement' => fake()->sentence,
            'neighborhoods' => 'centro',
            'country' => fake()->country(),
            'state' => fake()->state(),
            'cep' => substr(fake()->postcode(), 0, 7),
        ];
    }
}
