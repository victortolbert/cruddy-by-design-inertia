<?php

namespace Database\Factories;

use App\Models\Podcast;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Podcast>
 */
class PodcastFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = rtrim(fake()->unique()->sentence(3), '.');

        return [
            'user_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => fake()->paragraph(),
            'author' => fake()->name(),
            'website' => fake()->url(),
            'feed_url' => fake()->url(),
            'cover_path' => null,
        ];
    }

    public function withCoverImage(): static
    {
        return $this->state(fn (array $attributes) => [
            'cover_path' => 'podcast-covers/'.Str::random(20).'.jpg',
        ]);
    }
}
