<?php

namespace App\Models;

use Database\Factories\EpisodeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $podcast_id
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property string|null $show_notes
 * @property string $audio_url
 * @property int|null $duration_seconds
 * @property Carbon|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Podcast $podcast
 */
#[Fillable(['title', 'slug', 'description', 'show_notes', 'audio_url', 'duration_seconds', 'published_at'])]
class Episode extends Model
{
    /** @use HasFactory<EpisodeFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Podcast, $this>
     */
    public function podcast(): BelongsTo
    {
        return $this->belongsTo(Podcast::class);
    }

    /**
     * @return HasMany<PlaybackProgress, $this>
     */
    public function playbackProgress(): HasMany
    {
        return $this->hasMany(PlaybackProgress::class);
    }

    /**
     * @param  Builder<Episode>  $query
     * @return Builder<Episode>
     */
    #[Scope]
    protected function published(Builder $query): Builder
    {
        return $query->whereNotNull('published_at');
    }

    /**
     * @param  Builder<Episode>  $query
     * @return Builder<Episode>
     */
    #[Scope]
    protected function draft(Builder $query): Builder
    {
        return $query->whereNull('published_at');
    }

    /**
     * Newest first; drafts have no published_at, so fall back to creation order.
     *
     * @param  Builder<Episode>  $query
     * @return Builder<Episode>
     */
    #[Scope]
    protected function recent(Builder $query): Builder
    {
        return $query->orderByDesc('published_at')->orderByDesc('created_at');
    }

    public function publish(): void
    {
        $this->update(['published_at' => $this->freshTimestamp()]);
    }

    public function unpublish(): void
    {
        $this->update(['published_at' => null]);
    }

    public function isPublished(): bool
    {
        return $this->published_at !== null;
    }

    public function isVisibleTo(User $user): bool
    {
        return $this->isPublished() || $this->podcast->isOwnedBy($user);
    }

    public function durationForHumans(): ?string
    {
        if ($this->duration_seconds === null) {
            return null;
        }

        $hours = intdiv($this->duration_seconds, 3600);
        $minutes = intdiv($this->duration_seconds % 3600, 60);

        return collect([[$hours, 'hr'], [$minutes, 'min']])
            ->reject(fn (array $part) => $part[0] === 0)
            ->map(fn (array $part) => implode(' ', $part))
            ->implode(' ') ?: '0 min';
    }

    /**
     * Derive a slug from the title, unique within the podcast.
     */
    public static function uniqueSlugFor(Podcast $podcast, string $title): string
    {
        $base = Str::slug($title) ?: 'episode';
        $slug = $base;

        for ($suffix = 2; $podcast->episodes()->where('slug', $slug)->exists(); $suffix++) {
            $slug = "{$base}-{$suffix}";
        }

        return $slug;
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }
}
