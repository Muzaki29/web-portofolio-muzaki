import { createInertiaApp } from '@inertiajs/react'
import { createRoot } from 'react-dom/client'

const pages = import.meta.glob('./pages/**/*.tsx')

createInertiaApp({
  title: (title) => (title ? `${title} — Admin Portfolio` : 'Admin Portfolio'),
  resolve: (name) => pages[`./pages/${name}.tsx`]() as never,
  setup({ el, App, props }) {
    createRoot(el).render(<App {...props} />)
  },
  progress: { color: '#a31d1d' },
})
