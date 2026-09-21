<template>
  <Head title="Subscriptions" />

  <AppLayout title="Subscriptions">
    <div class="space-y-8">
      <div>
        <ULink
          :to="indexPodcasts().url"
          class="text-sm text-muted"
        >
          Podcasts
        </ULink>
        <h1 class="text-xl font-semibold">
          My subscriptions
        </h1>
      </div>

      <UEmpty
        v-if="!subscriptions.length"
        icon="i-lucide-bookmark"
        title="You are not subscribed to any podcasts"
        description="Open a podcast and hit Subscribe to see it here."
      />

      <div
        v-else
        class="space-y-3"
      >
        <div
          v-for="subscription in subscriptions"
          :key="subscription.id"
          class="flex flex-col gap-3 sm:flex-row sm:items-start"
        >
          <PodcastCard
            :podcast="subscription.podcast"
            class="flex-1"
          />
          <div class="shrink-0 sm:pt-4">
            <SubscriptionToggle
              :podcast="subscription.podcast"
              :subscription="subscription"
            />
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import type { Subscription } from '@/types'
import { Head } from '@inertiajs/vue3'
import { index as indexPodcasts } from '@/actions/App/Http/Controllers/PodcastsController'
import AppLayout from '@/layouts/app-layout.vue'

defineProps<{ subscriptions: Subscription[] }>()
</script>
