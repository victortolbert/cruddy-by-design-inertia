import type { Page } from '@inertiajs/core'

export interface AuthUser {
  id: number
  name: string
  email: string
  initials: string
}

export interface SharedProps {
  name: string
  auth: {
    user: AuthUser | null
  }
  flash: {
    success: string | null
  }
  [key: string]: unknown
}

export type AppPage<T = Record<string, unknown>> = Page<SharedProps & T>
