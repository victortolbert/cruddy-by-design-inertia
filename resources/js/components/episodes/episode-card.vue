<template>
  <div class="flex flex-col gap-3 rounded-xl border border-default bg-default p-4">
    <div class="flex items-start gap-3">
      <PodcastCover
        v-if="showPodcast && episode.podcast"
        :podcast="episode.podcast"
        size="md"
      />

      <div class="min-w-0 flex-1">
        <p
          v-if="showPodcast && episode.podcast"
          class="truncate text-sm text-muted"
        >
          {{ episode.podcast.title }}
        </p>

        <Link
          v-if="episode.podcast"
          :href="show([episode.podcast, episode])"
          class="block"
        >
          <p class="line-clamp-2 font-medium hover:underline">
            {{ episode.title }}
          </p>
        </Link>

        <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-muted">
          <span v-if="episode.is_published">{{ episode.published_at_for_humans }}</span>
          <UBadge
            v-else
            color="warning"
            variant="subtle"
            size="sm"
          >
            Draft
          </UBadge>
          <span v-if="episode.duration_for_humans">{{ episode.duration_for_humans }}</span>
        </div>
      </div>
    </div>

    <p
      v-if="episode.description"
      class="line-clamp-2 text-sm text-muted"
    >
      {{ episode.description }}
    </p>

    <div
      v-if="progress"
      class="flex items-center gap-3"
    >
      <UProgress
        :model-value="progress.percent_complete"
        :color="progress.is_completed ? 'success' : 'primary'"
        size="sm"
        class="flex-1"
      />
      <span class="text-sm text-muted tabular-nums">
        {{ progress.is_completed ? 'Played' : `${progress.percent_complete}%` }}
      </span>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Episode, PlaybackProgress } from '@/types'
import { Link } from '@inertiajs/vue3'
import { show } from '@/actions/App/Http/Controllers/EpisodesController'

withDefaults(defineProps<{
  episode: Episode
  progress?: Pick<PlaybackProgress, 'percent_complete' | 'is_completed'> | null
  showPodcast?: boolean
}>(), { progress: null, showPodcast: true })
</script>
