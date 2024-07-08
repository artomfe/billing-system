<?php

namespace Database\Factories;

use App\Models\Billing;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillingFactory extends Factory
{
    protected $model = Billing::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'governmentId' => $this->faker->randomNumber(7),
            'email' => $this->faker->unique()->safeEmail,
            'debtAmount' => $this->faker->randomFloat(2, 10, 1000),
            'debtDueDate' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'debtID' => $this->faker->uuid,
            'status' => Billing::STATUS_PENDING,
        ];
    }
}
