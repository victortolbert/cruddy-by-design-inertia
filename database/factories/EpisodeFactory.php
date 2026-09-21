<?php

namespace Database\Factories;

use App\Models\Episode;
use App\Models\Podcast;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Episode>
 */
class EpisodeFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = rtrim(fake()->unique()->sentence(4), '.');

        return [
            'podcast_id' => Podcast::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => fake()->paragraph(),
            'show_notes' => fake()->paragraphs(2, true),
            'audio_url' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3',
            'duration_seconds' => fake()->numberBetween(20, 90) * 60,
            'published_at' => fake()->dateTimeBetween('-2 years', '-1 day'),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'published_at' => null,
        ]);
    }
}
