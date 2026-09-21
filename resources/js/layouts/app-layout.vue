<template>
  <UApp>
    <UDashboardGroup unit="rem">
      <UDashboardSidebar
        collapsible
        resizable
        :min-size="14"
        :default-size="16"
        :ui="{ footer: 'border-t border-default' }"
      >
        <template #header="{ collapsed }">
          <Link
            :href="dashboard()"
            class="flex items-center gap-2 font-semibold"
          >
            <UIcon
              name="i-lucide-layout-grid"
              class="size-5 shrink-0"
            />
            <span v-if="!collapsed">{{ page.props.name }}</span>
          </Link>
        </template>

        <template #default="{ collapsed }">
          <UNavigationMenu
            :collapsed="collapsed"
            :items="navigation"
            orientation="vertical"
          />

          <div class="flex-1" />

          <UNavigationMenu
            :collapsed="collapsed"
            :items="external"
            orientation="vertical"
          />
        </template>

        <template #footer="{ collapsed }">
          <UDropdownMenu :items="userMenu">
            <UButton
              v-if="page.props.auth.user"
              :label="collapsed ? undefined : page.props.auth.user.name"
              :avatar="{ alt: page.props.auth.user.name, text: page.props.auth.user.initials }"
              color="neutral"
              variant="ghost"
              class="w-full"
              :block="!collapsed"
              trailing-icon="i-lucide-chevrons-up-down"
            />
          </UDropdownMenu>
        </template>
      </UDashboardSidebar>

      <UDashboardPanel>
        <template #header>
          <UDashboardNavbar :title="title">
            <template #leading>
              <UDashboardSidebarCollapse />
            </template>
          </UDashboardNavbar>
        </template>

        <template #body>
          <slot />
        </template>
      </UDashboardPanel>
    </UDashboardGroup>
  </UApp>
</template>

<script setup lang="ts">
import type { DropdownMenuItem, NavigationMenuItem } from '@nuxt/ui'
import { Link, router, usePage } from '@inertiajs/vue3'
import { computed, watch } from 'vue'
import { dashboard, logout } from '@/routes'
import { edit as editProfile } from '@/routes/profile'

withDefaults(defineProps<{ title?: string }>(), { title: undefined })

const page = usePage()
const toast = useToast()

const currentPath = computed(() => new URL(page.url, 'http://localhost').pathname)

const navigation = computed<NavigationMenuItem[]>(() => [
  {
    label: 'Dashboard',
    icon: 'i-lucide-house',
    to: dashboard().url,
    active: currentPath.value === dashboard().url,
  },
])

const external: NavigationMenuItem[] = [
  {
    label: 'Repository',
    icon: 'i-lucide-folder-git-2',
    to: 'https://github.com/victortolbert/cruddy-by-design-inertia',
    target: '_blank',
  },
  {
    label: 'Documentation',
    icon: 'i-lucide-book-open-text',
    to: 'https://laravel.com/docs',
    target: '_blank',
  },
]

const userMenu = computed<DropdownMenuItem[][]>(() => [
  [
    {
      label: page.props.auth.user?.name ?? '',
      avatar: { text: page.props.auth.user?.initials, alt: page.props.auth.user?.name },
      type: 'label',
    },
  ],
  [
    { label: 'Settings', icon: 'i-lucide-settings', to: editProfile().url },
  ],
  [
    {
      label: 'Log out',
      icon: 'i-lucide-log-out',
      onSelect: () => router.post(logout().url),
    },
  ],
])

watch(
  () => page.props.flash.success,
  (message) => {
    if (message) {
      toast.add({ title: message, color: 'success', icon: 'i-lucide-check' })
    }
  },
  { immediate: true },
)
</script>
