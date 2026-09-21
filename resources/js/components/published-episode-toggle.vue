<template>
  <!-- Tip 4: a state is a resource — PublishedEpisodes@store / @destroy -->
  <UButton
    v-if="episode.is_published"
    icon="i-lucide-archive"
    color="neutral"
    variant="ghost"
    size="sm"
    label="Unpublish"
    :loading="form.processing"
    @click="unpublish"
  />
  <UButton
    v-else
    icon="i-lucide-send"
    size="sm"
    label="Publish"
    :loading="form.processing"
    @click="publish"
  />
</template>

<script setup lang="ts">
import type { Episode } from '@/types'
import { useForm } from '@inertiajs/vue3'
import { destroy, store } from '@/actions/App/Http/Controllers/PublishedEpisodesController'

const props = defineProps<{ episode: Episode }>()

const form = useForm({ episode_id: props.episode.id })

function publish() {
  form.submit(store(), { preserveScroll: true })
}

function unpublish() {
  form.submit(destroy(props.episode.id), { preserveScroll: true })
}
</script>
