import { Head, Link, useForm } from '@inertiajs/react'
import { ImageUp, Loader2, Plus, X } from 'lucide-react'
import { useState, type FormEvent, type ReactNode } from 'react'

import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Switch } from '@/components/ui/switch'
import { Textarea } from '@/components/ui/textarea'
import { projectCode } from '@/components/status-badge'
import { UnderlineTabs } from '@/components/underline-tabs'
import AdminLayout from '@/layouts/admin-layout'
import type { Locale, Tag, Translated } from '@/types'

type Project = {
  id: number
  title: Translated
  short: Translated | null
  tagline: Translated | null
  description: Translated | null
  bullets: Translated<string[]> | null
  tags: Tag[] | null
  github_url: string | null
  live_url: string | null
  link_type: 'github' | 'live' | 'none'
  is_published: boolean
  gradient: string | null
  image_url: string | null
}

const blank = { id: '', en: '' }

function Field({ label, error, hint, children }: { label: string; error?: string; hint?: string; children: ReactNode }) {
  return (
    <div className="grid gap-2">
      <Label>{label}</Label>
      {children}
      {hint && !error && <p className="text-muted-foreground text-xs">{hint}</p>}
      {error && <p className="text-destructive text-sm">{error}</p>}
    </div>
  )
}

export default function ProjectForm({ project }: { project: Project | null }) {
  const [lang, setLang] = useState<Locale>('id')
  const [preview, setPreview] = useState<string | null>(project?.image_url ?? null)

  const form = useForm({
    title: project?.title ?? { ...blank },
    short: project?.short ?? { ...blank },
    tagline: project?.tagline ?? { ...blank },
    description: project?.description ?? { ...blank },
    bullets: project?.bullets ?? { id: [''], en: [''] },
    tags: project?.tags ?? ([] as Tag[]),
    github_url: project?.github_url ?? '',
    live_url: project?.live_url ?? '',
    link_type: project?.link_type ?? 'none',
    is_published: project?.is_published ?? true,
    gradient: project?.gradient ?? 'pc-g1',
    image: null as File | null,
    _method: project ? 'put' : 'post',
  })

  const { data, setData, errors } = form
  const err = (key: string) => (errors as Record<string, string>)[key]
  const setT = (field: 'title' | 'short' | 'tagline' | 'description', value: string) =>
    setData(field, { ...data[field], [lang]: value })
  const bullets = data.bullets[lang]
  const setBullets = (list: string[]) => setData('bullets', { ...data.bullets, [lang]: list })
  const langHasError = (l: Locale) => Object.keys(errors).some((k) => k.endsWith(`.${l}`) || k.includes(`.${l}.`))

  const submit = (e: FormEvent) => {
    e.preventDefault()
    // Multipart + method spoofing so the image upload also works on update.
    form.post(project ? `/admin/projects/${project.id}` : '/admin/projects', { forceFormData: true })
  }

  const title = project ? `Edit: ${project.title.id}` : 'Tambah karya'

  return (
    <AdminLayout
      title={title}
      breadcrumbs={[{ label: 'Dashboard', href: '/admin' }, { label: 'Karya', href: '/admin/projects' }, { label: project ? 'Edit' : 'Tambah' }]}
      actions={
        <Button size="sm" type="submit" form="project-form" disabled={form.processing}>
          {form.processing && <Loader2 className="animate-spin" />}
          Simpan
        </Button>
      }
    >
      <Head title={title} />
      <form id="project-form" onSubmit={submit} className="grid items-start gap-6 lg:grid-cols-[1fr_340px]">
        <Card>
          <CardHeader>
            <CardTitle>Konten</CardTitle>
            <CardDescription>Isi kedua bahasa; landing page mengikuti pilihan bahasa pengunjung.</CardDescription>
          </CardHeader>
          <UnderlineTabs
            label="Bahasa konten"
            className="px-6"
            value={lang}
            onChange={setLang}
            tabs={[
              { value: 'id', label: 'Indonesia', alert: langHasError('id') },
              { value: 'en', label: 'English', alert: langHasError('en') },
            ]}
          />
          <CardContent className="grid gap-5">
            <Field label="Judul" error={err(`title.${lang}`)}>
              <Input value={data.title[lang]} onChange={(e) => setT('title', e.target.value)} />
            </Field>
            <Field label="Tagline" error={err(`tagline.${lang}`)} hint="Satu baris di bawah judul pada detail karya.">
              <Input value={data.tagline[lang]} onChange={(e) => setT('tagline', e.target.value)} />
            </Field>
            <Field label="Deskripsi singkat" error={err(`short.${lang}`)} hint="Tampil di kartu karya.">
              <Textarea rows={2} value={data.short[lang]} onChange={(e) => setT('short', e.target.value)} />
            </Field>
            <Field label="Deskripsi lengkap" error={err(`description.${lang}`)}>
              <Textarea rows={5} value={data.description[lang]} onChange={(e) => setT('description', e.target.value)} />
            </Field>
            <Field label="Fitur utama" hint="Boleh pakai <strong> untuk menebalkan awal kalimat.">
              <div className="grid gap-2">
                {bullets.map((b, i) => (
                  <div key={i} className="flex gap-2">
                    <Textarea rows={2} value={b} onChange={(e) => setBullets(bullets.map((x, j) => (j === i ? e.target.value : x)))} />
                    <Button type="button" variant="ghost" size="icon" onClick={() => setBullets(bullets.filter((_, j) => j !== i))} aria-label="Hapus fitur">
                      <X />
                    </Button>
                  </div>
                ))}
                <Button type="button" variant="outline" size="sm" className="justify-self-start" onClick={() => setBullets([...bullets, ''])}>
                  <Plus /> Tambah fitur
                </Button>
              </div>
            </Field>
          </CardContent>
        </Card>

        <div className="grid gap-6 lg:sticky lg:top-20">
          <Card>
            <CardHeader>
              <CardTitle>Publikasi</CardTitle>
              {project && <CardDescription className="font-mono text-xs">{projectCode(project.id)}</CardDescription>}
            </CardHeader>
            <CardContent className="grid gap-4">
              <div className="flex items-center justify-between gap-4">
                <Label htmlFor="published">Tayang di landing page</Label>
                <Switch id="published" checked={data.is_published} onCheckedChange={(v) => setData('is_published', v)} />
              </div>
              <div className="flex gap-2">
                <Button type="submit" className="flex-1" disabled={form.processing}>
                  {form.processing && <Loader2 className="animate-spin" />}
                  {project ? 'Simpan perubahan' : 'Simpan karya'}
                </Button>
                <Button type="button" variant="outline" asChild><Link href="/admin/projects">Batal</Link></Button>
              </div>
            </CardContent>
          </Card>

          <Card>
            <CardHeader><CardTitle>Gambar</CardTitle></CardHeader>
            <CardContent className="grid gap-3">
              <label className="bg-muted hover:bg-muted/70 flex aspect-[16/10] cursor-pointer items-center justify-center overflow-hidden rounded-lg border border-dashed transition-colors">
                {preview ? (
                  <img src={preview} alt="Pratinjau" className="size-full object-cover" />
                ) : (
                  <span className="text-muted-foreground flex flex-col items-center gap-1 text-sm"><ImageUp className="size-5" /> Pilih gambar</span>
                )}
                <input
                  type="file"
                  accept="image/png,image/jpeg,image/webp"
                  className="sr-only"
                  onChange={(e) => {
                    const file = e.target.files?.[0] ?? null
                    setData('image', file)
                    if (file) setPreview(URL.createObjectURL(file))
                  }}
                />
              </label>
              <p className="text-muted-foreground text-xs">JPG, PNG, atau WebP, maks 4 MB. Rasio 16:10.</p>
              {err('image') && <p className="text-destructive text-sm">{err('image')}</p>}
            </CardContent>
          </Card>

          <Card>
            <CardHeader><CardTitle>Tautan</CardTitle></CardHeader>
            <CardContent className="grid gap-4">
              <Field label="Tombol di kartu" error={err('link_type')}>
                <Select value={data.link_type} onValueChange={(v) => setData('link_type', v as Project['link_type'])}>
                  <SelectTrigger className="w-full"><SelectValue /></SelectTrigger>
                  <SelectContent>
                    <SelectItem value="github">Repository GitHub</SelectItem>
                    <SelectItem value="live">Situs live</SelectItem>
                    <SelectItem value="none">Tanpa tautan</SelectItem>
                  </SelectContent>
                </Select>
              </Field>
              <Field label="URL GitHub" error={err('github_url')}>
                <Input type="url" placeholder="https://github.com/…" value={data.github_url} onChange={(e) => setData('github_url', e.target.value)} />
              </Field>
              <Field label="URL live" error={err('live_url')}>
                <Input type="url" placeholder="https://…" value={data.live_url} onChange={(e) => setData('live_url', e.target.value)} />
              </Field>
            </CardContent>
          </Card>

          <Card>
            <CardHeader>
              <CardTitle>Teknologi</CardTitle>
              <CardDescription>3 pertama tampil di kartu.</CardDescription>
            </CardHeader>
            <CardContent className="grid gap-2">
              {data.tags.map((t, i) => (
                <div key={i} className="flex gap-2">
                  <Input
                    placeholder="Laravel 12"
                    value={t.label}
                    aria-invalid={!!err(`tags.${i}.label`)}
                    onChange={(e) => setData('tags', data.tags.map((x, j) => (j === i ? { ...x, label: e.target.value } : x)))}
                  />
                  <Input
                    placeholder="fab fa-laravel"
                    className="font-mono text-xs"
                    value={t.icon}
                    aria-label="Kelas ikon Font Awesome"
                    onChange={(e) => setData('tags', data.tags.map((x, j) => (j === i ? { ...x, icon: e.target.value } : x)))}
                  />
                  <Button type="button" variant="ghost" size="icon" onClick={() => setData('tags', data.tags.filter((_, j) => j !== i))} aria-label="Hapus teknologi">
                    <X />
                  </Button>
                </div>
              ))}
              <Button type="button" variant="outline" size="sm" className="justify-self-start" onClick={() => setData('tags', [...data.tags, { label: '', icon: 'fas fa-code' }])}>
                <Plus /> Tambah teknologi
              </Button>
            </CardContent>
          </Card>
        </div>
      </form>
    </AdminLayout>
  )
}
