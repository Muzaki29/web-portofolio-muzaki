import { Head, useForm } from '@inertiajs/react'
import { ArrowUpRight, Loader2 } from 'lucide-react'
import type { FormEvent } from 'react'

import { LogoMark } from '@/components/logo'

const pill =
  'h-11 w-full rounded-full border border-white/10 bg-white/[0.06] px-5 text-sm text-white placeholder:text-white/45 outline-none transition focus:border-white/30 focus:bg-white/[0.09] focus-visible:ring-2 focus-visible:ring-[#e0313f]/60 aria-[invalid=true]:border-[#e0313f]'

function GoogleIcon() {
  return (
    <svg viewBox="0 0 24 24" className="size-4" aria-hidden="true">
      <path fill="#4285F4" d="M23.5 12.3c0-.8-.1-1.6-.2-2.3H12v4.4h6.5a5.6 5.6 0 0 1-2.4 3.6v3h3.9c2.2-2.1 3.5-5.1 3.5-8.7z" />
      <path fill="#34A853" d="M12 24c3.2 0 6-1.1 7.9-2.9l-3.9-3c-1.1.7-2.4 1.2-4 1.2-3.1 0-5.7-2.1-6.6-4.9h-4v3.1A12 12 0 0 0 12 24z" />
      <path fill="#FBBC05" d="M5.4 14.4a7.2 7.2 0 0 1 0-4.7V6.6h-4a12 12 0 0 0 0 10.8l4-3z" />
      <path fill="#EA4335" d="M12 4.8c1.8 0 3.3.6 4.6 1.8l3.4-3.4A12 12 0 0 0 1.4 6.6l4 3.1C6.3 6.9 8.9 4.8 12 4.8z" />
    </svg>
  )
}

export default function Login({ googleEnabled = false }: { googleEnabled?: boolean }) {
  const form = useForm({ email: '', password: '', remember: true })

  const submit = (e: FormEvent) => {
    e.preventDefault()
    form.post('/loginadmin', { onFinish: () => form.reset('password') })
  }

  return (
    <div className="flex min-h-svh flex-col bg-[#121212] text-white">
      <Head title="Masuk" />

      <header className="flex h-16 items-center justify-between px-5 sm:px-8">
        <a href="/" className="flex items-center gap-3" aria-label="Kembali ke portofolio">
          <LogoMark className="h-6 w-auto text-[#e0313f]" />
          <span className="hidden text-sm font-semibold tracking-tight sm:inline">Muzaki Abdullah Irsyad</span>
        </a>
        <a href="/" className="inline-flex h-9 items-center gap-1.5 rounded-full bg-white px-4 text-sm font-semibold text-black transition hover:bg-white/85">
          Lihat situs <ArrowUpRight className="size-4" />
        </a>
      </header>

      <main className="flex flex-1 items-center justify-center px-5 pb-16">
        <div className="w-full max-w-sm">
          <div className="mb-7 text-center">
            <h1 className="text-2xl font-bold tracking-tight">Selamat datang kembali</h1>
            <p className="mt-2 text-sm text-white/60">Masuk ke admin portofolio</p>
          </div>

          {googleEnabled ? (
            <a href="/auth/google/redirect" className="flex h-11 w-full items-center justify-center gap-2.5 rounded-full bg-white/[0.1] text-sm font-semibold transition hover:bg-white/[0.16] focus-visible:ring-2 focus-visible:ring-[#e0313f]/60 focus-visible:outline-none">
              <GoogleIcon /> Lanjutkan dengan Google
            </a>
          ) : (
            <div className="grid gap-2">
              <button type="button" disabled className="flex h-11 w-full cursor-not-allowed items-center justify-center gap-2.5 rounded-full bg-white/[0.06] text-sm font-semibold text-white/45">
                <GoogleIcon /> Lanjutkan dengan Google
              </button>
              <p className="text-center text-xs text-white/40">Login Google aktif setelah GOOGLE_CLIENT_ID diisi di .env.</p>
            </div>
          )}

          <div className="my-6 flex items-center gap-4 text-xs text-white/40">
            <span className="h-px flex-1 bg-white/10" />atau<span className="h-px flex-1 bg-white/10" />
          </div>

          <form onSubmit={submit} className="grid gap-4" noValidate>
            <div className="grid gap-1.5">
              <label htmlFor="email" className="sr-only">Email</label>
              <input
                id="email"
                type="email"
                autoComplete="username"
                autoFocus
                placeholder="Email"
                className={pill}
                value={form.data.email}
                onChange={(e) => form.setData('email', e.target.value)}
                aria-invalid={!!form.errors.email}
                aria-describedby={form.errors.email ? 'email-error' : undefined}
              />
            </div>
            <div className="grid gap-1.5">
              <label htmlFor="password" className="sr-only">Password</label>
              <input
                id="password"
                type="password"
                autoComplete="current-password"
                placeholder="Password"
                className={pill}
                value={form.data.password}
                onChange={(e) => form.setData('password', e.target.value)}
                aria-invalid={!!form.errors.password}
              />
            </div>
            {(form.errors.email || form.errors.password) && (
              <p id="email-error" role="alert" className="text-center text-sm text-[#ff6b75]">{form.errors.email || form.errors.password}</p>
            )}

            <label className="flex cursor-pointer items-center justify-center gap-2 text-xs text-white/55">
              <input type="checkbox" className="size-3.5 accent-[#e0313f]" checked={form.data.remember} onChange={(e) => form.setData('remember', e.target.checked)} />
              Tetap masuk di perangkat ini
            </label>

            <button
              type="submit"
              disabled={form.processing}
              className="flex h-11 w-full items-center justify-center gap-2 rounded-full bg-gradient-to-r from-[#7a1220] via-[#c8102e] to-[#ff5a5f] text-sm font-semibold text-white shadow-[0_8px_24px_-8px_rgba(200,16,46,.6)] transition hover:brightness-110 focus-visible:ring-2 focus-visible:ring-white/70 focus-visible:outline-none disabled:opacity-70"
            >
              {form.processing && <Loader2 className="size-4 animate-spin" />}
              Masuk
            </button>
          </form>

          <p className="mt-8 text-center text-xs text-white/40">Hanya untuk pemilik portofolio. Akun dibuat lewat <code className="font-mono">php artisan admin:create</code>.</p>
        </div>
      </main>
    </div>
  )
}
