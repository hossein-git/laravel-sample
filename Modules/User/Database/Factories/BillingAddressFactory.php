<?php

namespace Modules\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\Models\BillingAddress;

class BillingAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = BillingAddress::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['private', 'business']),
            'name' => $this->faker->firstName,
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'state' => $this->faker->city,
            'country' => $this->faker->country,
            'zip' => $this->faker->postcode,
            'tax_type' => $this->faker->optional()->word,
            'tax_id' => $this->faker->optional()->uuid,
        ];
    }
}
