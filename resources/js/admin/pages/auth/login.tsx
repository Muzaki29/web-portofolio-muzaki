import { Head, useForm } from '@inertiajs/react'
import { Loader2 } from 'lucide-react'
import type { FormEvent } from 'react'

import { LogoLockup } from '@/components/logo'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Switch } from '@/components/ui/switch'

export default function Login() {
  const form = useForm({ email: '', password: '', remember: false })

  const submit = (e: FormEvent) => {
    e.preventDefault()
    form.post('/loginadmin', { onFinish: () => form.reset('password') })
  }

  return (
    <div className="bg-muted flex min-h-svh items-center justify-center p-6">
      <Head title="Masuk" />
      <div className="w-full max-w-sm">
        <LogoLockup className="mb-8" />
        <Card>
          <CardHeader>
            <CardTitle className="text-xl">Masuk ke admin</CardTitle>
            <CardDescription>Kelola konten landing page portofolio.</CardDescription>
          </CardHeader>
          <CardContent>
            <form onSubmit={submit} className="grid gap-5" noValidate>
              <div className="grid gap-2">
                <Label htmlFor="email">Email</Label>
                <Input
                  id="email"
                  type="email"
                  autoComplete="username"
                  autoFocus
                  value={form.data.email}
                  onChange={(e) => form.setData('email', e.target.value)}
                  aria-invalid={!!form.errors.email}
                />
                {form.errors.email && <p className="text-destructive text-sm">{form.errors.email}</p>}
              </div>
              <div className="grid gap-2">
                <Label htmlFor="password">Password</Label>
                <Input
                  id="password"
                  type="password"
                  autoComplete="current-password"
                  value={form.data.password}
                  onChange={(e) => form.setData('password', e.target.value)}
                  aria-invalid={!!form.errors.password}
                />
                {form.errors.password && <p className="text-destructive text-sm">{form.errors.password}</p>}
              </div>
              <div className="flex items-center gap-2">
                <Switch id="remember" checked={form.data.remember} onCheckedChange={(v) => form.setData('remember', v)} />
                <Label htmlFor="remember" className="font-normal">Ingat saya</Label>
              </div>
              <Button type="submit" className="w-full" disabled={form.processing}>
                {form.processing && <Loader2 className="animate-spin" />}
                Masuk
              </Button>
            </form>
          </CardContent>
        </Card>
      </div>
    </div>
  )
}
