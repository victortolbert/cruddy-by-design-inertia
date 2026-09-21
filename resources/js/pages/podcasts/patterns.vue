<template>
  <Head title="CRUDdy by Design" />

  <AppLayout title="CRUDdy by Design">
    <div class="mx-auto w-full max-w-3xl space-y-10">
      <div class="space-y-2">
        <ULink
          :to="indexPodcasts().url"
          class="text-sm text-muted"
        >
          Podcasts
        </ULink>
        <h1 class="text-xl font-semibold">
          CRUDdy by Design
        </h1>
        <p class="text-muted">
          Every screen and every action in the podcast app is one of the seven resource verbs —
          index, show, create, store, edit, update, destroy. When something wanted a custom verb,
          it became a new resource instead. The moves below are from Adam Wathan's
          <ULink
            to="https://www.youtube.com/watch?v=MF0jFKvS4SI"
            target="_blank"
            rel="noopener"
            class="underline"
          >
            Laracon 2017 talk
          </ULink>;
          here each one is a resource controller and, on the front end, a Vue component whose
          Wayfinder actions are only resource verbs. The list grows with each lesson.
        </p>
      </div>

      <section
        v-for="pattern in patterns"
        :key="pattern.title"
        class="space-y-3"
      >
        <h2 class="text-lg font-medium">
          {{ pattern.title }}
        </h2>
        <p class="text-muted">
          {{ pattern.body }}
        </p>
        <ul class="space-y-1 text-sm">
          <li
            v-for="line in pattern.code"
            :key="line"
          >
            <code class="rounded bg-elevated px-1.5 py-0.5">{{ line }}</code>
          </li>
        </ul>
      </section>

      <USeparator />

      <p class="text-sm text-muted">
        Run <code class="rounded bg-elevated px-1.5 py-0.5">php artisan route:list --except-vendor</code> to see the shape in one place.
      </p>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { index as indexPodcasts } from '@/actions/App/Http/Controllers/PodcastsController'
import AppLayout from '@/layouts/app-layout.vue'

const patterns = [
  {
    title: '1. Give nested resources a dedicated controller',
    body: 'Listing a podcast\'s episodes is not Podcasts@episodes. It is PodcastEpisodesController@index, and creating one is @create / @store.',
    code: ['GET /podcasts/{slug}/episodes → PodcastEpisodesController@index', 'POST /podcasts/{slug}/episodes → PodcastEpisodesController@store'],
  },
  {
    title: '2. Treat properties edited independently as their own resource',
    body: 'The cover image is uploaded on its own, not alongside the title and description, so it is PodcastCoverImageController@update — never Podcasts@updateCoverImage.',
    code: ['PUT /podcasts/{slug}/cover-image', '<PodcastCoverImage> calls PodcastCoverImageController.update()'],
  },
]
</script>
