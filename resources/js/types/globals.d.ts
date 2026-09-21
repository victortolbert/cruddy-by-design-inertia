import type { SharedProps } from './index'

declare module '@inertiajs/core' {
  export interface PageProps extends SharedProps {}
}

export {}
