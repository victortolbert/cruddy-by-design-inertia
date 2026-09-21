<template>
  <!-- Playback position is its own resource — PlaybackProgress@update / @destroy -->
  <div class="space-y-3">
    <audio
      ref="audio"
      controls
      preload="metadata"
      class="w-full"
      :src="episode.audio_url"
      @loadedmetadata="resume"
      @timeupdate="tick"
      @pause="pause"
      @ended="end"
    />

    <div
      v-if="progress"
      class="flex items-center gap-3"
    >
      <UProgress
        :model-value="progress.percent_complete"
        :color="progress.is_completed ? 'success' : 'primary'"
        size="sm"
        class="flex-1"
      />
      <span class="text-sm text-muted tabular-nums">
        {{ progress.is_completed ? 'Played' : `${progress.percent_complete}%` }}
      </span>
      <UButton
        color="neutral"
        variant="ghost"
        size="xs"
        icon="i-lucide-rotate-ccw"
        label="Reset"
        @click="reset"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Episode, PlaybackProgress } from '@/types'
import { router } from '@inertiajs/vue3'
import { useTemplateRef } from 'vue'
import { destroy, update } from '@/actions/App/Http/Controllers/PlaybackProgressController'

const props = defineProps<{
  episode: Episode
  progress: PlaybackProgress | null
}>()

const audio = useTemplateRef<HTMLAudioElement>('audio')

const SAVE_INTERVAL_SECONDS = 10
let lastSaved = 0

function save(position: number, completed = false) {
  router.put(update(props.episode.id).url, { position, completed }, {
    preserveScroll: true,
    preserveState: true,
    only: ['progress'],
  })
}

function resume() {
  const element = audio.value
  const position = props.progress && !props.progress.is_completed ? props.progress.position_seconds : 0

  if (element && position > 0 && position < element.duration)
    element.currentTime = position
}

function tick() {
  const element = audio.value
  if (!element || element.currentTime - lastSaved < SAVE_INTERVAL_SECONDS)
    return

  lastSaved = element.currentTime
  save(Math.floor(element.currentTime))
}

function pause() {
  const element = audio.value
  if (!element || element.ended)
    return

  lastSaved = element.currentTime
  save(Math.floor(element.currentTime))
}

function end() {
  const element = audio.value
  if (!element)
    return

  save(Math.floor(element.duration || element.currentTime), true)
}

function reset() {
  router.delete(destroy(props.episode.id).url, { preserveScroll: true })
}
</script>
