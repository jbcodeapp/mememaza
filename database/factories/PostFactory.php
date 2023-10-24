<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $title = fake()->sentence;
        $imageNumber = random_int(1, 99);
        $this->wait();
        return [
            'title' => $title,
            'category_id' => fake()->numberBetween(1, 20),
            'slug' => Str::slug($title),
            'image' => "faker/images/posts/image_$imageNumber.jpg",
            'meta_title' => fake()->text,
            'meta_keyword' => implode(', ', fake()->words(3)),
            'meta_desc' => fake()->text,
            'desc' => fake()->paragraph,
            'status' => fake()->boolean,
            'download' => fake()->numberBetween(0, 100),
        ];
    }

    public function wait()
    {
        sleep(1);
    }
}
