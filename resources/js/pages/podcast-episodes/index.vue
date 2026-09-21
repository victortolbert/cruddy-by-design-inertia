<template>
  <Head :title="`${podcast.title} — Episodes`" />

  <AppLayout title="Episodes">
    <div class="space-y-8">
      <div class="flex flex-wrap items-start justify-between gap-4">
        <div class="flex items-center gap-4">
          <PodcastCover
            :podcast="podcast"
            size="lg"
          />
          <div>
            <ULink
              :to="showPodcasts(podcast).url"
              class="text-sm text-muted"
            >
              {{ podcast.title }}
            </ULink>
            <h1 class="text-xl font-semibold">
              Episodes
            </h1>
          </div>
        </div>

        <UButton
          v-if="podcast.is_owner"
          :to="createPodcastEpisodes(podcast).url"
          label="Add episode"
          icon="i-lucide-plus"
          size="sm"
        />
      </div>

      <UEmpty
        v-if="!episodes.data.length"
        icon="i-lucide-music"
        title="No episodes yet"
      />

      <template v-else>
        <div class="space-y-3">
          <div
            v-for="episode in episodes.data"
            :key="episode.id"
            class="flex flex-col gap-3 sm:flex-row sm:items-start"
          >
            <EpisodeCard
              :episode="{ ...episode, podcast }"
              :show-podcast="false"
              class="flex-1"
            />

            <div
              v-if="podcast.is_owner"
              class="flex shrink-0 items-center gap-2 sm:pt-4"
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

        <UPagination
          v-if="episodes.meta.last_page > 1"
          :page="episodes.meta.current_page"
          :total="episodes.meta.total"
          :items-per-page="episodes.meta.per_page"
          :to="paginationLink"
        />
      </template>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import type { Episode, Paginated, Podcast } from '@/types'
import { Head } from '@inertiajs/vue3'
import { edit as editEpisodes } from '@/actions/App/Http/Controllers/EpisodesController'
import { create as createPodcastEpisodes, index as indexPodcastEpisodes } from '@/actions/App/Http/Controllers/PodcastEpisodesController'
import { show as showPodcasts } from '@/actions/App/Http/Controllers/PodcastsController'
import AppLayout from '@/layouts/app-layout.vue'

const props = defineProps<{
  podcast: Podcast
  episodes: Paginated<Episode>
}>()

function paginationLink(page: number) {
  return { href: indexPodcastEpisodes(props.podcast, { query: { page } }).url }
}
</script>
