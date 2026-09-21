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

export interface Podcast {
  id: number
  title: string
  slug: string
  description: string | null
  author: string | null
  website: string | null
  website_host: string | null
  feed_url: string | null
  is_owner: boolean
}

export interface Episode {
  id: number
  title: string
  slug: string
  description: string | null
  show_notes: string | null
  audio_url: string
  duration_seconds: number | null
  duration_for_humans: string | null
  is_published: boolean
  published_at: string | null
  published_at_for_humans: string | null
  podcast?: Podcast
}

export interface PaginationLink {
  url: string | null
  label: string
  active: boolean
}

export interface Paginated<T> {
  data: T[]
  links: {
    first: string | null
    last: string | null
    prev: string | null
    next: string | null
  }
  meta: {
    current_page: number
    from: number | null
    last_page: number
    per_page: number
    to: number | null
    total: number
    links: PaginationLink[]
  }
}

export type AppPage<T = Record<string, unknown>> = Page<SharedProps & T>
