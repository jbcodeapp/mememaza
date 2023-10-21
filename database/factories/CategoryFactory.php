<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $imageNumber = random_int(1, 60);
        $name = fake()->unique()->word;
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'image' => "/faker/images/image_$imageNumber.jpg",
            'banner_image' => "/faker/images/image_$imageNumber.jpg",
            'meta_title' => "$name - MemeMaza",
            'meta_keyword' => implode(', ', fake()->words(3)),
            'meta_desc' => fake()->text,
            'status' => 1,
        ];
    }
}
