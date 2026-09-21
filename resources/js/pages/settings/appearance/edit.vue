<template>
  <Head title="Appearance settings" />

  <SettingsLayout
    heading="Appearance"
    subheading="Update the appearance settings for your account"
  >
    <URadioGroup
      v-model="colorMode"
      :items="items"
      orientation="horizontal"
      variant="card"
    />
  </SettingsLayout>
</template>

<script setup lang="ts">
import type { RadioGroupItem } from '@nuxt/ui'
import { Head } from '@inertiajs/vue3'
import { useColorMode } from '@vueuse/core'
import SettingsLayout from '@/layouts/settings-layout.vue'

// Same store Nuxt UI's color-mode components read, so this stays in sync with
// <UColorModeButton /> and friends.
const colorMode = useColorMode()

const items: RadioGroupItem[] = [
  { label: 'Light', value: 'light' },
  { label: 'Dark', value: 'dark' },
  { label: 'System', value: 'auto' },
]
</script>
