<template>
  <AppLayout title="Settings">
    <div class="space-y-6">
      <div>
        <h1 class="text-xl font-semibold">
          Settings
        </h1>
        <p class="text-muted">
          Manage your profile and account settings
        </p>
      </div>

      <USeparator />

      <div class="flex flex-col gap-8 md:flex-row md:items-start">
        <UNavigationMenu
          :items="items"
          orientation="vertical"
          class="w-full md:w-56"
        />

        <div class="min-w-0 flex-1 space-y-1">
          <h2 class="font-medium">
            {{ heading }}
          </h2>
          <p class="text-sm text-muted">
            {{ subheading }}
          </p>

          <div class="mt-6 w-full max-w-lg">
            <slot />
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import type { NavigationMenuItem } from '@nuxt/ui'
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import { edit as editAccount } from '@/routes/account'
import { edit as editAppearance } from '@/routes/appearance'
import { edit as editProfile } from '@/routes/profile'
import AppLayout from './app-layout.vue'

defineProps<{ heading: string, subheading: string }>()

const page = usePage()
const currentPath = computed(() => new URL(page.url, 'http://localhost').pathname)

const items = computed<NavigationMenuItem[]>(() => [
  { label: 'Profile', to: editProfile().url, active: currentPath.value === editProfile().url },
  { label: 'Appearance', to: editAppearance().url, active: currentPath.value === editAppearance().url },
  { label: 'Account', to: editAccount().url, active: currentPath.value === editAccount().url },
])
</script>
