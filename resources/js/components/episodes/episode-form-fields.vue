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
      :rows="3"
      class="w-full"
    />
  </UFormField>

  <UFormField
    label="Audio URL"
    name="audio_url"
    required
    :error="form.errors.audio_url"
  >
    <UInput
      v-model="form.audio_url"
      type="url"
      placeholder="https://"
      class="w-full"
    />
  </UFormField>

  <UFormField
    label="Duration (minutes)"
    name="duration_minutes"
    :error="form.errors.duration_minutes"
  >
    <UInputNumber
      v-model="form.duration_minutes"
      :min="1"
      :max="1440"
      class="w-full"
    />
  </UFormField>

  <UFormField
    label="Show notes"
    name="show_notes"
    :error="form.errors.show_notes"
  >
    <UTextarea
      v-model="form.show_notes"
      :rows="8"
      class="w-full"
    />
  </UFormField>
</template>

<script setup lang="ts">
import type { InertiaForm } from '@inertiajs/vue3'

export interface EpisodeFormData {
  title: string
  description: string
  audio_url: string
  duration_minutes: number | null
  show_notes: string
}

defineProps<{ form: InertiaForm<EpisodeFormData> }>()
</script>
