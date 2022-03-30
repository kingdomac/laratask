<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Issues>
 */
class IssueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'title' => $this->faker->sentence(3),
            'details' => $this->faker->paragraph(3),
            'created_by' => 1,
            'project_id' => random_int(1, 3),
            'label_id' => random_int(1, 4),
            'priority_id' => random_int(1, 4),
        ];
    }
}
