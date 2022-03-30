<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'description' => $this->faker->paragraph(4),
            'due_date' => Carbon::now()->addMonths(random_int(1, 4)),
            'budget' => $this->faker->numberBetween(1500, 10000),
            'website' => $this->faker->url(),
            'created_by' => 1,
            'agent_id' => 3
        ];
    }
}
