<template>
  <Head title="Log in" />

  <AuthLayout
    title="Log in to your account"
    description="Enter your email and password below"
  >
    <UAlert
      v-if="status"
      :title="status"
      color="success"
      variant="soft"
      class="mb-6"
    />

    <UForm
      :state="form"
      class="space-y-6"
      @submit="submit"
    >
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
          autofocus
          class="w-full"
        />
      </UFormField>

      <UFormField
        label="Password"
        name="password"
        required
        :error="form.errors.password"
      >
        <UInput
          v-model="form.password"
          type="password"
          autocomplete="current-password"
          class="w-full"
        />
      </UFormField>

      <UCheckbox
        v-model="form.remember"
        label="Remember me"
      />

      <UButton
        type="submit"
        label="Log in"
        block
        :loading="form.processing"
      />
    </UForm>

    <p class="mt-6 text-center text-sm text-muted">
      Don't have an account?
      <ULink
        :to="register().url"
        class="underline"
      >
        Sign up
      </ULink>
    </p>
  </AuthLayout>
</template>

<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'
import { store as storeSession } from '@/actions/App/Http/Controllers/Auth/AuthenticatedSessionController'
import AuthLayout from '@/layouts/auth-layout.vue'
import { register } from '@/routes'

withDefaults(defineProps<{ status?: string | null }>(), { status: null })

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

function submit() {
  form.submit(storeSession(), {
    onFinish: () => form.reset('password'),
  })
}
</script>
