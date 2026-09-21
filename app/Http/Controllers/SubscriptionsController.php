<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Tip 3: the pivot is its own resource. Subscribing is `store`, unsubscribing
 * is `destroy` on a Subscription — not `subscribe`/`unsubscribe` on Podcast.
 */
class SubscriptionsController extends Controller
{
    public function index(Request $request): Response
    {
        $subscriptions = $request->user()->subscriptions()
            ->with('podcast')
            ->latest()
            ->get();

        return Inertia::render('subscriptions/index', [
            'subscriptions' => SubscriptionResource::collection($subscriptions)->resolve($request),
        ]);
    }

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
