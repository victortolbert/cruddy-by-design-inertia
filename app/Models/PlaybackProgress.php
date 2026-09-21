<?php

namespace App\Models;

use Database\Factories\PlaybackProgressFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Where a user is in an episode. Independent of the episode itself, so it is
 * its own resource: `update` records a position, `destroy` resets it.
 *
 * @property int $id
 * @property int $user_id
 * @property int $episode_id
 * @property int $position_seconds
 * @property Carbon|null $completed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Episode $episode
 */
#[Fillable(['user_id', 'episode_id', 'position_seconds', 'completed_at'])]
class PlaybackProgress extends Model
{
    /** @use HasFactory<PlaybackProgressFactory> */
    use HasFactory;

    protected $table = 'playback_progress';

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Episode, $this>
     */
    public function episode(): BelongsTo
    {
        return $this->belongsTo(Episode::class);
    }

    /**
     * @param  Builder<PlaybackProgress>  $query
     * @return Builder<PlaybackProgress>
     */
    #[Scope]
    protected function inProgress(Builder $query): Builder
    {
        return $query->where('position_seconds', '>', 0)->whereNull('completed_at');
    }

    /**
     * @param  Builder<PlaybackProgress>  $query
     * @return Builder<PlaybackProgress>
     */
    #[Scope]
    protected function completed(Builder $query): Builder
    {
        return $query->whereNotNull('completed_at');
    }

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    public function percentComplete(): int
    {
        $duration = $this->episode->duration_seconds;

        if ($this->isCompleted()) {
            return 100;
        }

        if (! $duration) {
            return 0;
        }

        return (int) min(100, round($this->position_seconds / $duration * 100));
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'position_seconds' => 'integer',
            'completed_at' => 'datetime',
        ];
    }
}
