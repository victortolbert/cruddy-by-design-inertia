<template>
  <UFormField
    label="Title"
    name="title"
    required
    :error="form.errors.title"
  >
    <UInput
      v-model="form.title"
      class="w-full"
      autofocus
    />
  </UFormField>

  <UFormField
    label="Description"
    name="description"
    :error="form.errors.description"
  >
    <UTextarea
      v-model="form.description"
      :rows="4"
      class="w-full"
    />
  </UFormField>

  <UFormField
    label="Author"
    name="author"
    :error="form.errors.author"
  >
    <UInput
      v-model="form.author"
      class="w-full"
    />
  </UFormField>

  <UFormField
    label="Website"
    name="website"
    :error="form.errors.website"
  >
    <UInput
      v-model="form.website"
      type="url"
      placeholder="https://"
      class="w-full"
    />
  </UFormField>

  <UFormField
    label="Feed URL"
    name="feed_url"
    :error="form.errors.feed_url"
  >
    <UInput
      v-model="form.feed_url"
      type="url"
      placeholder="https://"
      class="w-full"
    />
  </UFormField>
</template>

<script setup lang="ts">
import type { InertiaForm } from '@inertiajs/vue3'

export interface PodcastFormData {
  title: string
  description: string
  author: string
  website: string
  feed_url: string
}

defineProps<{ form: InertiaForm<PodcastFormData> }>()
</script>
