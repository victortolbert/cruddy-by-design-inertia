<template>
  <Head title="Profile settings" />

  <SettingsLayout
    heading="Profile"
    subheading="Update your name and email address"
  >
    <UForm
      :state="form"
      class="space-y-6"
      @submit="submit"
    >
      <UFormField
        label="Name"
        name="name"
        required
        :error="form.errors.name"
      >
        <UInput
          v-model="form.name"
          autocomplete="name"
          autofocus
          class="w-full"
        />
      </UFormField>

      <UFormField
        label="Email"
        name="email"
        required
        :error="form.errors.email"
      >
        <UInput
          v-model="form.email"
          type="email"
          autocomplete="email"
          class="w-full"
        />
      </UFormField>

      <UButton
        type="submit"
        label="Save"
        :loading="form.processing"
      />
    </UForm>

    <USeparator class="my-10" />

    <DeleteUser />
  </SettingsLayout>
</template>

<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3'
import { updateProfileInformation } from '@/actions/App/Http/Controllers/Settings/ProfileController'
import SettingsLayout from '@/layouts/settings-layout.vue'

const page = usePage()

const form = useForm({
  name: page.props.auth.user?.name ?? '',
  email: page.props.auth.user?.email ?? '',
})

function submit() {
  form.submit(updateProfileInformation(), { preserveScroll: true })
}
</script>
