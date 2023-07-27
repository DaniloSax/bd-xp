<?php

namespace Database\Factories;

use App\Modules\User\Domain\Models\People;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PeopleFactory extends Factory
{
    protected $model = People::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'cpf' => fake()->cpf(),
            'rg' => fake()->rg(),
            'birthday' => fake()->dateTimeBetween('-50 years', '-18 years')->format('Y-m-d')
        ];
    }
}
