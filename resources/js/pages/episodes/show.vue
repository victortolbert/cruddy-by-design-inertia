<template>
  <Head :title="episode.title" />

  <AppLayout :title="episode.title">
    <div class="mx-auto w-full max-w-3xl space-y-8">
      <div class="flex items-start gap-4">
        <Link
          :href="showPodcasts(podcast)"
          class="shrink-0"
        >
          <PodcastCover
            :podcast="podcast"
            size="xl"
          />
        </Link>

        <div class="min-w-0 flex-1 space-y-2">
          <ULink
            :to="showPodcasts(podcast).url"
            class="text-sm text-muted"
          >
            {{ podcast.title }}
          </ULink>

          <h1 class="text-xl font-semibold">
            {{ episode.title }}
          </h1>

          <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-muted">
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

          <div
            v-if="podcast.is_owner"
            class="flex flex-wrap items-center gap-2 pt-1"
          >
            <PublishedEpisodeToggle :episode="episode" />
            <UButton
              :to="editEpisodes([podcast, episode]).url"
              label="Edit"
              icon="i-lucide-pencil"
              color="neutral"
              variant="ghost"
              size="sm"
            />
          </div>
        </div>
      </div>

      <PlaybackProgress
        :episode="episode"
        :progress="progress"
      />

      <p
        v-if="episode.description"
        class="text-muted"
      >
        {{ episode.description }}
      </p>

      <section
        v-if="episode.show_notes"
        class="space-y-2"
      >
        <h2 class="text-lg font-medium">
          Show notes
        </h2>
        <p class="whitespace-pre-line text-muted">
          {{ episode.show_notes }}
        </p>
      </section>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import type { Episode, Podcast, PlaybackProgress as Progress } from '@/types'
import { Head, Link } from '@inertiajs/vue3'
import { edit as editEpisodes } from '@/actions/App/Http/Controllers/EpisodesController'
import { show as showPodcasts } from '@/actions/App/Http/Controllers/PodcastsController'
import AppLayout from '@/layouts/app-layout.vue'

defineProps<{
  podcast: Podcast
  episode: Episode
  progress: Progress | null
}>()
</script>
