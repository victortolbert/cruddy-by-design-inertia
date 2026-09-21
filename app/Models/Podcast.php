<?php

namespace App\Models;

use Database\Factories\PodcastFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property string|null $author
 * @property string|null $website
 * @property string|null $feed_url
 * @property string|null $cover_path
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['title', 'slug', 'description', 'author', 'website', 'feed_url', 'cover_path'])]
class Podcast extends Model
{
    /** @use HasFactory<PodcastFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<User, $this>
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return HasMany<Episode, $this>
     */
    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class);
    }

    /**
     * @return HasMany<Subscription, $this>
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'subscriptions')->withTimestamps();
    }

    /**
     * Episodes the given user may see: published ones for everyone, drafts for the owner.
     *
     * @return Collection<int, Episode>
     */
    public function recentEpisodesVisibleTo(User $user, int $count = 5): Collection
    {
        return $this->episodes()
            ->when(! $this->isOwnedBy($user), fn (Builder $query): Builder => $query->published())
            ->recent()
            ->take($count)
            ->get();
    }

    public function isOwnedBy(User $user): bool
    {
        return $this->user_id === $user->getKey();
    }

    public function hasCoverImage(): bool
    {
        return $this->cover_path !== null;
    }

    public function coverImageUrl(): ?string
    {
        return $this->hasCoverImage()
            ? Storage::disk(config('podcasts.cover_disk'))->url($this->cover_path)
            : null;
    }

    public function websiteHost(): ?string
    {
        $host = $this->website ? parse_url($this->website, PHP_URL_HOST) : null;

        return is_string($host) ? $host : null;
    }

    /**
     * Derive a slug from the title, suffixing a counter until it is unique.
     */
    public static function uniqueSlugFor(string $title): string
    {
        $base = Str::slug($title) ?: 'podcast';
        $slug = $base;

        for ($suffix = 2; static::query()->where('slug', $slug)->exists(); $suffix++) {
            $slug = "{$base}-{$suffix}";
        }

        return $slug;
    }
}
