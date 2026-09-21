<template>
  <div
    class="flex shrink-0 items-center justify-center bg-linear-to-br from-elevated to-accented font-semibold text-muted"
    :class="sizes[size]"
    aria-hidden="true"
  >
    {{ initials }}
  </div>
</template>

<script setup lang="ts">
import type { Podcast } from '@/types'
import { computed } from 'vue'

const props = withDefaults(defineProps<{
  podcast: Podcast
  size?: 'md' | 'lg' | 'xl' | '2xl'
}>(), { size: 'lg' })

const sizes = {
  'md': 'size-12 rounded-lg text-sm',
  'lg': 'size-16 rounded-xl text-base',
  'xl': 'size-32 rounded-2xl text-2xl',
  '2xl': 'size-48 rounded-2xl text-4xl',
}

const initials = computed(() => props.podcast.title
  .split(/\s+/)
  .filter(Boolean)
  .slice(0, 2)
  .map(word => word[0]?.toUpperCase())
  .join(''))
</script>
