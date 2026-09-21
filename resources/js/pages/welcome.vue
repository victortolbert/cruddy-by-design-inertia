<template>
  <Head title="Welcome" />

  <UApp>
    <UMain class="flex min-h-screen flex-col">
      <UHeader>
        <template #left>
          <Link
            :href="home()"
            class="font-semibold"
          >
            {{ page.props.name }}
          </Link>
        </template>

        <template #right>
          <UButton
            v-if="page.props.auth.user"
            :to="dashboard().url"
            label="Dashboard"
            color="neutral"
            variant="ghost"
          />
          <template v-else>
            <UButton
              :to="login().url"
              label="Log in"
              color="neutral"
              variant="ghost"
            />
            <UButton
              :to="register().url"
              label="Register"
              color="neutral"
              variant="ghost"
            />
          </template>
        </template>
      </UHeader>

      <UPageHero
        title="CRUDdy by Design"
        description="A podcast app where every action is one of the seven resource verbs. Built on Laravel, Inertia, Vue and Nuxt UI as the companion to the free course."
        :links="links"
      />
    </UMain>
  </UApp>
</template>

<script setup lang="ts">
import type { ButtonProps } from '@nuxt/ui'
import { Head, Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import { dashboard, home, login, register } from '@/routes'

const page = usePage()

const links = computed<ButtonProps[]>(() => [
  page.props.auth.user
    ? { label: 'Open the dashboard', to: dashboard().url, icon: 'i-lucide-layout-grid' }
    : { label: 'Log in', to: login().url, icon: 'i-lucide-log-in' },
  { label: 'Read the docs', to: 'https://laravel.com/docs', target: '_blank', color: 'neutral', variant: 'subtle', trailingIcon: 'i-lucide-arrow-up-right' },
])
</script>
