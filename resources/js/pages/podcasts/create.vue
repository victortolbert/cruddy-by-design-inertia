<template>
  <Head title="New podcast" />

  <AppLayout title="New podcast">
    <div class="mx-auto w-full max-w-2xl space-y-6">
      <div>
        <h1 class="text-xl font-semibold">
          New podcast
        </h1>
        <p class="mt-1 text-muted">
          Add cover art and episodes after saving.
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
            label="Create podcast"
            :loading="form.processing"
          />
          <UButton
            :to="indexPodcasts().url"
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
import type { PodcastFormData } from '@/components/podcasts/podcast-form-fields.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { index as indexPodcasts, store as storePodcasts } from '@/actions/App/Http/Controllers/PodcastsController'
import AppLayout from '@/layouts/app-layout.vue'

const form = useForm<PodcastFormData>({
  title: '',
  description: '',
  author: '',
  website: '',
  feed_url: '',
})

function submit() {
  form.submit(storePodcasts())
}
</script>
