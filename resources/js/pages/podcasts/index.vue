<template>
  <Head title="Podcasts" />

  <AppLayout title="Podcasts">
    <div class="space-y-10">
      <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
          <h1 class="text-xl font-semibold">
            Podcasts
          </h1>
          <p class="mt-1 text-muted">
            Discover shows, follow episodes, pick up where you left off.
          </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
          <UButton
            :to="subscriptionsIndex().url"
            label="Subscriptions"
            icon="i-lucide-bookmark"
            color="neutral"
            variant="ghost"
            size="sm"
          />
          <UButton
            :to="inProgressIndex().url"
            label="In progress"
            icon="i-lucide-clock"
            color="neutral"
            variant="ghost"
            size="sm"
          />
          <UButton
            :to="completedIndex().url"
            label="Played"
            icon="i-lucide-circle-check"
            color="neutral"
            variant="ghost"
            size="sm"
          />
          <UButton
            :to="createPodcasts().url"
            label="New podcast"
            icon="i-lucide-plus"
            size="sm"
          />
        </div>
      </div>

      <section
        v-if="inProgress.length"
        class="space-y-4"
      >
        <h2 class="text-lg font-medium">
          Continue listening
        </h2>
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
          <EpisodeCard
            v-for="progress in inProgress"
            :key="progress.id"
            :episode="progress.episode"
            :progress="progress"
          />
        </div>
      </section>

      <section
        v-if="subscribedPodcasts.length"
        class="space-y-4"
      >
        <h2 class="text-lg font-medium">
          My subscriptions
        </h2>
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
          <PodcastCard
            v-for="podcast in subscribedPodcasts"
            :key="podcast.id"
            :podcast="podcast"
          />
        </div>
      </section>

      <section class="space-y-4">
        <h2 class="text-lg font-medium">
          All podcasts
        </h2>

        <UEmpty
          v-if="!podcasts.data.length"
          icon="i-lucide-mic"
          title="No podcasts yet"
          description="Create the first one, or run the seeder to load the starter catalog."
        />

        <template v-else>
          <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <PodcastCard
              v-for="podcast in podcasts.data"
              :key="podcast.id"
              :podcast="podcast"
            />
          </div>

          <UPagination
            v-if="podcasts.meta.last_page > 1"
            :page="podcasts.meta.current_page"
            :total="podcasts.meta.total"
            :items-per-page="podcasts.meta.per_page"
            :to="paginationLink"
          />
        </template>
      </section>

      <p class="text-sm text-muted">
        Built on the four
        <ULink
          :to="patterns().url"
          class="underline"
        >
          CRUDdy by Design patterns
        </ULink>.
      </p>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import type { Episode, Paginated, PlaybackProgress, Podcast } from '@/types'
import { Head } from '@inertiajs/vue3'
import { create as createPodcasts, index as indexPodcasts } from '@/actions/App/Http/Controllers/PodcastsController'
import AppLayout from '@/layouts/app-layout.vue'
import { index as completedIndex } from '@/routes/completed-episodes'
import { index as inProgressIndex } from '@/routes/in-progress-episodes'
import { patterns } from '@/routes/podcasts'
import { index as subscriptionsIndex } from '@/routes/subscriptions'

defineProps<{
  inProgress: Array<Pick<PlaybackProgress, 'id' | 'percent_complete' | 'is_completed'> & { episode: Episode }>
  subscribedPodcasts: Podcast[]
  podcasts: Paginated<Podcast>
}>()

function paginationLink(page: number) {
  return { href: indexPodcasts({ query: { page } }).url }
}
</script>
