<?php

namespace App\Models;

use Database\Factories\SubscriptionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * A pivot between a user and a podcast, modeled as its own resource so
 * subscribing is `store` and unsubscribing is `destroy` on this model.
 *
 * @property int $id
 * @property int $user_id
 * @property int $podcast_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['user_id', 'podcast_id'])]
class Subscription extends Model
{
    /** @use HasFactory<SubscriptionFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Podcast, $this>
     */
    public function podcast(): BelongsTo
    {
        return $this->belongsTo(Podcast::class);
    }
}
