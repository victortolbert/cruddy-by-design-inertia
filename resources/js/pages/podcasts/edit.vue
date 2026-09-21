<template>
  <Head title="Edit podcast" />

  <AppLayout title="Edit podcast">
    <div class="mx-auto w-full max-w-2xl space-y-10">
      <div>
        <h1 class="text-xl font-semibold">
          Edit podcast
        </h1>
        <p class="mt-1 text-muted">
          {{ podcast.title }}
        </p>
      </div>

      <UForm
        :state="form"
        class="space-y-6"
        @submit="submit"
      >
        <PodcastFormFields :form="form" />

        <div class="flex items-center gap-2">
          <UButton
            type="submit"
            label="Save changes"
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

      <USeparator />

      <section class="space-y-4">
        <h2 class="text-lg font-medium">
          Delete podcast
        </h2>
        <p class="text-muted">
          Deletes every episode with it. This cannot be undone.
        </p>
        <UButton
          color="error"
          icon="i-lucide-trash-2"
          label="Delete podcast"
          @click="destroy"
        />
      </section>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import type { PodcastFormData } from '@/components/podcasts/podcast-form-fields.vue'
import type { Podcast } from '@/types'
import { Head, router, useForm } from '@inertiajs/vue3'
import { destroy as destroyPodcasts, show as showPodcasts, update as updatePodcasts } from '@/actions/App/Http/Controllers/PodcastsController'
import AppLayout from '@/layouts/app-layout.vue'

const props = defineProps<{ podcast: Podcast }>()

const form = useForm<PodcastFormData>({
  title: props.podcast.title,
  description: props.podcast.description ?? '',
  author: props.podcast.author ?? '',
  website: props.podcast.website ?? '',
  feed_url: props.podcast.feed_url ?? '',
})

function submit() {
  form.submit(updatePodcasts(props.podcast))
}

function destroy() {
  // eslint-disable-next-line no-alert
  if (!window.confirm('Delete this podcast and all of its episodes?'))
    return

  router.delete(destroyPodcasts(props.podcast).url)
}
</script>
