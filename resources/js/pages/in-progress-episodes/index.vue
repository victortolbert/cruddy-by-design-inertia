<template>
  <Head title="In progress" />

  <AppLayout title="In progress">
    <div class="space-y-8">
      <div>
        <ULink
          :to="indexPodcasts().url"
          class="text-sm text-muted"
        >
          Podcasts
        </ULink>
        <h1 class="text-xl font-semibold">
          In progress
        </h1>
        <p class="mt-1 text-muted">
          Episodes you have started but not finished.
        </p>
      </div>

      <UEmpty
        v-if="!inProgress.length"
        icon="i-lucide-clock"
        title="Nothing in progress"
        description="Start an episode and it will show up here."
      />

      <div
        v-else
        class="grid gap-4 md:grid-cols-2 xl:grid-cols-3"
      >
        <EpisodeCard
          v-for="progress in inProgress"
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

defineProps<{ inProgress: PlaybackProgress[] }>()
</script>
