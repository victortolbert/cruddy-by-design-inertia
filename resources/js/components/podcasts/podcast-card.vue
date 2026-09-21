<template>
  <Link
    :href="show(podcast)"
    class="group flex gap-4 rounded-xl border border-default bg-default p-4 transition hover:border-accented hover:shadow-sm"
  >
    <PodcastCover
      :podcast="podcast"
      size="lg"
    />

    <div class="min-w-0 flex-1">
      <p class="truncate font-medium group-hover:underline">
        {{ podcast.title }}
      </p>
      <p
        v-if="podcast.author"
        class="truncate text-sm text-muted"
      >
        {{ podcast.author }}
      </p>
      <p
        v-if="podcast.description"
        class="mt-1 line-clamp-2 text-sm text-muted"
      >
        {{ podcast.description }}
      </p>
    </div>
  </Link>
</template>

<script setup lang="ts">
import type { Podcast } from '@/types'
import { Link } from '@inertiajs/vue3'
import { show } from '@/actions/App/Http/Controllers/PodcastsController'

defineProps<{ podcast: Podcast }>()
</script>
