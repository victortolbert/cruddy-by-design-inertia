<template>
  <Head title="New episode" />

  <AppLayout title="New episode">
    <div class="mx-auto w-full max-w-2xl space-y-6">
      <div>
        <p class="text-sm text-muted">
          {{ podcast.title }}
        </p>
        <h1 class="text-xl font-semibold">
          New episode
        </h1>
        <p class="mt-1 text-muted">
          Episodes start as drafts. Publish when it is ready.
        </p>
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
            label="Save draft"
            :loading="form.processing"
          />
          <UButton
            :to="showPodcasts(podcast).url"
            label="Cancel"
            color="neutral"
            variant="ghost"
          />
        </div>
      </UForm>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import type { EpisodeFormData } from '@/components/episodes/episode-form-fields.vue'
import type { Podcast } from '@/types'
import { Head, useForm } from '@inertiajs/vue3'
import { store as storePodcastEpisodes } from '@/actions/App/Http/Controllers/PodcastEpisodesController'
import { show as showPodcasts } from '@/actions/App/Http/Controllers/PodcastsController'
import AppLayout from '@/layouts/app-layout.vue'

const props = defineProps<{ podcast: Podcast }>()

const form = useForm<EpisodeFormData>({
  title: '',
  description: '',
  audio_url: '',
  duration_minutes: null,
  show_notes: '',
})

function submit() {
  form.submit(storePodcastEpisodes(props.podcast))
}
</script>
