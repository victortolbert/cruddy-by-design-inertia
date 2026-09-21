<template>
  <Head :title="podcast.title" />

  <AppLayout :title="podcast.title">
    <div class="space-y-10">
      <div class="flex flex-col gap-6 sm:flex-row sm:items-start">
        <PodcastCover
          :podcast="podcast"
          size="2xl"
        />

        <div class="min-w-0 flex-1 space-y-3">
          <div>
            <h1 class="text-xl font-semibold">
              {{ podcast.title }}
            </h1>
            <p
              v-if="podcast.author"
              class="mt-1 text-muted"
            >
              {{ podcast.author }}
            </p>
          </div>

          <p
            v-if="podcast.description"
            class="text-muted"
          >
            {{ podcast.description }}
          </p>

          <div class="flex flex-wrap items-center gap-2">
            <SubscriptionToggle
              :podcast="podcast"
              :subscription="subscription"
            />

            <UButton
              v-if="podcast.website"
              :to="podcast.website"
              target="_blank"
              rel="noopener"
              :label="podcast.website_host ?? podcast.website"
              icon="i-lucide-external-link"
              color="neutral"
              variant="ghost"
              size="sm"
            />

            <template v-if="podcast.is_owner">
              <UButton
                :to="createPodcastEpisodes(podcast).url"
                label="Add episode"
                icon="i-lucide-plus"
                color="neutral"
                variant="soft"
                size="sm"
              />
              <UButton
                :to="editPodcasts(podcast).url"
                label="Edit"
                icon="i-lucide-pencil"
                color="neutral"
                variant="ghost"
                size="sm"
              />
            </template>
          </div>
        </div>
      </div>

      <section class="space-y-4">
        <div class="flex items-center justify-between gap-4">
          <h2 class="text-lg font-medium">
            Recent episodes
          </h2>
          <ULink
            :to="indexPodcastEpisodes(podcast).url"
            class="text-sm underline"
          >
            All episodes
          </ULink>
        </div>

        <UEmpty
          v-if="!episodes.length"
          icon="i-lucide-music"
          title="No episodes yet"
          :description="podcast.is_owner ? 'Add an episode, then publish it when it is ready.' : undefined"
        />

        <div
          v-else
          class="grid gap-4 md:grid-cols-2"
        >
          <EpisodeCard
            v-for="episode in episodes"
            :key="episode.id"
            :episode="{ ...episode, podcast }"
            :show-podcast="false"
          />
        </div>
      </section>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import type { Episode, Podcast } from '@/types'
import { Head } from '@inertiajs/vue3'
import { create as createPodcastEpisodes, index as indexPodcastEpisodes } from '@/actions/App/Http/Controllers/PodcastEpisodesController'
import { edit as editPodcasts } from '@/actions/App/Http/Controllers/PodcastsController'
import AppLayout from '@/layouts/app-layout.vue'

defineProps<{
  podcast: Podcast
  episodes: Episode[]
  subscription: { id: number } | null
}>()
</script>
