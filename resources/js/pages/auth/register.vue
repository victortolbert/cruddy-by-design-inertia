<template>
  <Head title="Register" />

  <AuthLayout
    title="Create an account"
    description="Enter your details below to create your account"
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

      <UFormField
        label="Password"
        name="password"
        required
        :error="form.errors.password"
      >
        <UInput
          v-model="form.password"
          type="password"
          autocomplete="new-password"
          class="w-full"
        />
      </UFormField>

      <UFormField
        label="Confirm password"
        name="password_confirmation"
        required
        :error="form.errors.password_confirmation"
      >
        <UInput
          v-model="form.password_confirmation"
          type="password"
          autocomplete="new-password"
          class="w-full"
        />
      </UFormField>

      <UButton
        type="submit"
        label="Create account"
        block
        :loading="form.processing"
      />
    </UForm>

    <p class="mt-6 text-center text-sm text-muted">
      Already have an account?
      <ULink
        :to="login().url"
        class="underline"
      >
        Log in
      </ULink>
    </p>
  </AuthLayout>
</template>

<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'
import { store as storeUser } from '@/actions/App/Http/Controllers/Auth/RegisteredUserController'
import AuthLayout from '@/layouts/auth-layout.vue'
import { login } from '@/routes'

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

function submit() {
  form.submit(storeUser(), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>
