<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'user_id' => 1,
            'is_folder' => $this->faker->numberBetween(0, 1),
            'size' => $this->faker->numberBetween(),
            'is_favourite' => $this->faker->numberBetween(0, 1),
            'parent_id' => 0,
            'created_at' => $this->faker->dateTimeBetween(),
            'updated_at' => $this->faker->dateTimeBetween(),
        ];
    }
}
