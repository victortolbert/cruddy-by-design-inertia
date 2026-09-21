<template>
  <!-- Tip 2: a property edited on its own is its own resource — PodcastCoverImage@update -->
  <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
    <PodcastCover
      :podcast="podcast"
      size="xl"
    />

    <UForm
      :state="form"
      class="flex-1 space-y-4"
      @submit="submit"
    >
      <UFormField
        label="Cover image"
        name="cover"
        description="Square, at least 500×500px, up to 2 MB."
        :error="form.errors.cover"
      >
        <UFileUpload
          v-model="form.cover"
          accept="image/*"
          label="Drop an image here"
          description="PNG or JPG"
          class="min-h-32"
        />
      </UFormField>

      <div class="flex items-center gap-2">
        <UButton
          type="submit"
          size="sm"
          label="Save cover"
          :disabled="!form.cover"
          :loading="form.processing"
        />
      </div>
    </UForm>
  </div>
</template>

<script setup lang="ts">
import type { Podcast } from '@/types'
import { useForm } from '@inertiajs/vue3'
import { update } from '@/actions/App/Http/Controllers/PodcastCoverImageController'

const props = defineProps<{ podcast: Podcast }>()

const form = useForm<{ cover: File | null }>({ cover: null })

function submit() {
  // Multipart bodies cannot be PUT, so the form is spoofed through POST.
  form.transform(data => ({ ...data, _method: 'put' }))
    .post(update(props.podcast).url, {
      preserveScroll: true,
      forceFormData: true,
      onSuccess: () => form.reset(),
    })
}
</script>
