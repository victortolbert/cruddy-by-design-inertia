<template>
  <UApp>
    <UMain class="flex min-h-screen flex-col items-center justify-center gap-6 p-6">
      <Link
        :href="home()"
        class="flex items-center gap-2 font-semibold"
      >
        <UIcon
          name="i-lucide-layout-grid"
          class="size-5 shrink-0"
        />
        {{ page.props.name }}
      </Link>

      <UCard class="w-full max-w-sm">
        <div class="mb-6 space-y-1 text-center">
          <h1 class="text-xl font-semibold">
            {{ title }}
          </h1>
          <p
            v-if="description"
            class="text-sm text-muted"
          >
            {{ description }}
          </p>
        </div>

        <slot />
      </UCard>
    </UMain>
  </UApp>
</template>

<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'
import { home } from '@/routes'

withDefaults(defineProps<{ title: string, description?: string }>(), { description: undefined })

const page = usePage()
</script>
