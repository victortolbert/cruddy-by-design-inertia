<template>
  <Head title="Dashboard" />

  <AppLayout title="Dashboard">
    <div class="flex h-full flex-1 flex-col gap-4">
      <div class="grid auto-rows-min gap-4 md:grid-cols-3">
        <div
          v-for="index in 3"
          :key="index"
          class="relative aspect-video overflow-hidden rounded-xl border border-default"
        >
          <PlaceholderPattern />
        </div>
      </div>
      <div class="relative min-h-[50vh] flex-1 overflow-hidden rounded-xl border border-default">
        <PlaceholderPattern />
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/app-layout.vue'
</script>
