<template>
  <Head title="Edit episode" />

  <AppLayout title="Edit episode">
    <div class="mx-auto w-full max-w-2xl space-y-10">
      <div>
        <p class="text-sm text-muted">
          {{ podcast.title }}
        </p>
        <h1 class="text-xl font-semibold">
          Edit episode
        </h1>
      </div>

      <UForm
        :state="form"
        class="space-y-6"
        @submit="submit"
      >
        <EpisodeFormFields :form="form" />

        <div class="flex items-center gap-2">
          <UButton
            type="submit"
            label="Save changes"
            :loading="form.processing"
          />
          <UButton
            :to="indexPodcastEpisodes(podcast).url"
            label="Cancel"
            color="neutral"
            variant="ghost"
          />
        </div>
      </UForm>

      <USeparator />

      <section class="space-y-4">
        <h2 class="text-lg font-medium">
          Delete episode
        </h2>
        <UButton
          color="error"
          icon="i-lucide-trash-2"
          label="Delete episode"
          @click="destroy"
        />
      </section>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import type { EpisodeFormData } from '@/components/episodes/episode-form-fields.vue'
import type { Episode, Podcast } from '@/types'
import { Head, router, useForm } from '@inertiajs/vue3'
import { destroy as destroyEpisodes, update as updateEpisodes } from '@/actions/App/Http/Controllers/EpisodesController'
import { index as indexPodcastEpisodes } from '@/actions/App/Http/Controllers/PodcastEpisodesController'
import AppLayout from '@/layouts/app-layout.vue'

const props = defineProps<{
  podcast: Podcast
  episode: Episode
}>()

const form = useForm<EpisodeFormData>({
  title: props.episode.title,
  description: props.episode.description ?? '',
  audio_url: props.episode.audio_url,
  duration_minutes: props.episode.duration_seconds !== null ? Math.floor(props.episode.duration_seconds / 60) : null,
  show_notes: props.episode.show_notes ?? '',
})

function submit() {
  form.submit(updateEpisodes([props.podcast, props.episode]))
}

function destroy() {
  // eslint-disable-next-line no-alert
  if (!window.confirm('Delete this episode?'))
    return

  router.delete(destroyEpisodes([props.podcast, props.episode]).url)
}
</script>
