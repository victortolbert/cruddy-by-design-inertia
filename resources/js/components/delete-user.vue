<template>
  <section class="space-y-6">
    <div>
      <h3 class="font-medium">
        Delete account
      </h3>
      <p class="text-sm text-muted">
        Delete your account and all of its resources
      </p>
    </div>

    <UAlert
      color="error"
      variant="soft"
      title="Warning"
      description="Please proceed with caution, this cannot be undone."
      icon="i-lucide-triangle-alert"
    />

    <UModal
      v-model:open="open"
      title="Are you sure you want to delete your account?"
      description="Once your account is deleted, all of its resources and data will also be permanently deleted. Please enter your password to confirm you would like to permanently delete your account."
    >
      <UButton
        color="error"
        label="Delete account"
        data-test="delete-user-button"
      />

      <template #body>
        <UForm
          :state="form"
          class="space-y-6"
          @submit="destroy"
        >
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

          <div class="flex justify-end gap-2">
            <UButton
              color="neutral"
              variant="soft"
              label="Cancel"
              @click="close"
            />
            <UButton
              type="submit"
              color="error"
              label="Delete account"
              data-test="confirm-delete-user-button"
              :loading="form.processing"
            />
          </div>
        </UForm>
      </template>
    </UModal>
  </section>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import { deleteUser } from '@/actions/App/Http/Controllers/Settings/ProfileController'

const open = ref(false)
const form = useForm({ password: '' })

function close() {
  open.value = false
  form.clearErrors()
  form.reset()
}

function destroy() {
  form.submit(deleteUser(), {
    preserveScroll: true,
    onError: () => form.reset(),
  })
}
</script>
