<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriptionRequest;
use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Tip 3: the pivot is its own resource. Subscribing is `store`, unsubscribing
 * is `destroy` on a Subscription — not `subscribe`/`unsubscribe` on Podcast.
 */
class SubscriptionsController extends Controller
{
    public function store(StoreSubscriptionRequest $request): RedirectResponse
    {
        Subscription::query()->firstOrCreate([
            'user_id' => $request->user()->id,
            'podcast_id' => $request->integer('podcast_id'),
        ]);

        return back()->with('success', __('Subscribed.'));
    }

    public function destroy(Request $request, Subscription $subscription): RedirectResponse
    {
        abort_unless($subscription->user_id === $request->user()->id, 404);

        $subscription->delete();

        return back()->with('success', __('Unsubscribed.'));
    }
}
