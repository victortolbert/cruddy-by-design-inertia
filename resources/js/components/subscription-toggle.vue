<template>
  <!-- Tip 3: the pivot is its own resource — Subscriptions@store / @destroy -->
  <UButton
    v-if="subscription"
    icon="i-lucide-check"
    color="neutral"
    variant="soft"
    size="sm"
    label="Subscribed"
    :loading="form.processing"
    @click="unsubscribe"
  />
  <UButton
    v-else
    icon="i-lucide-plus"
    size="sm"
    label="Subscribe"
    :loading="form.processing"
    @click="subscribe"
  />
</template>

<script setup lang="ts">
import type { Podcast } from '@/types'
import { useForm } from '@inertiajs/vue3'
import { destroy, store } from '@/actions/App/Http/Controllers/SubscriptionsController'

const props = defineProps<{
  podcast: Podcast
  subscription: { id: number } | null
}>()

const form = useForm({ podcast_id: props.podcast.id })

function subscribe() {
  form.submit(store(), { preserveScroll: true })
}

function unsubscribe() {
  if (!props.subscription)
    return

  form.submit(destroy(props.subscription.id), { preserveScroll: true })
}
</script>
