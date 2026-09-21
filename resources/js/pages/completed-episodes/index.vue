<template>
  <Head title="Played" />

  <AppLayout title="Played">
    <div class="space-y-8">
      <div>
        <ULink
          :to="indexPodcasts().url"
          class="text-sm text-muted"
        >
          Podcasts
        </ULink>
        <h1 class="text-xl font-semibold">
          Played
        </h1>
        <p class="mt-1 text-muted">
          Episodes you have listened to the end.
        </p>
      </div>

      <UEmpty
        v-if="!completed.length"
        icon="i-lucide-circle-check"
        title="Nothing played yet"
      />

      <div
        v-else
        class="grid gap-4 md:grid-cols-2 xl:grid-cols-3"
      >
        <EpisodeCard
          v-for="progress in completed"
          :key="progress.id"
          :episode="progress.episode!"
          :progress="progress"
        />
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import type { PlaybackProgress } from '@/types'
import { Head } from '@inertiajs/vue3'
import { index as indexPodcasts } from '@/actions/App/Http/Controllers/PodcastsController'
import AppLayout from '@/layouts/app-layout.vue'

defineProps<{ completed: PlaybackProgress[] }>()
</script>
