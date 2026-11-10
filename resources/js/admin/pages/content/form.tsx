import { Head, Link, useForm } from '@inertiajs/react'
import { ArrowLeft, ArrowRight, ImageUp, Loader2, X } from 'lucide-react'
import { useState, type FormEvent } from 'react'

import { SchemaField } from '@/components/schema-field'
import { UnderlineTabs } from '@/components/underline-tabs'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Label } from '@/components/ui/label'
import { Switch } from '@/components/ui/switch'
import AdminLayout from '@/layouts/admin-layout'
import type { FieldValue, FieldValues, Locale, SectionSchema } from '@/types'

type Image = { full: string; preview?: string; url: string }
type Item = { id: number; is_published: boolean; data: FieldValues & { images?: Image[] } }

export default function ContentForm({ section, schema, item }: { section: string; schema: SectionSchema; item: Item | null }) {
  const base = `/admin/content/${section}`
  const [lang, setLang] = useState<Locale>('id')
  const textFields = schema.fields.filter((f) => f.type !== 'images')
  const imageField = schema.fields.find((f) => f.type === 'images')
  const [kept, setKept] = useState<Image[]>(item?.data.images ?? [])
  const [added, setAdded] = useState<{ file: File; url: string }[]>([])

  const { images: _ignored, ...initialData } = item?.data ?? {}
  const form = useForm({
    data: initialData as FieldValues,
    is_published: item?.is_published ?? true,
    keep_images: kept.map((i) => i.full),
    new_images: [] as File[],
    _method: item ? 'put' : 'post',
  })
  const errors = form.errors as Record<string, string>
  const err = (name: string, translatable?: boolean) => errors[translatable ? `data.${name}.${lang}` : `data.${name}`]
  const langHasError = (l: Locale) => Object.keys(errors).some((k) => k.endsWith(`.${l}`))

  const setField = (name: string, value: FieldValue) => form.setData('data', { ...form.data.data, [name]: value })

  const moveKept = (i: number, dir: -1 | 1) => {
    const next = [...kept]
    ;[next[i], next[i + dir]] = [next[i + dir], next[i]]
    setKept(next)
  }

  const submit = (e: FormEvent) => {
    e.preventDefault()
    form.transform((d) => ({ ...d, keep_images: kept.map((i) => i.full), new_images: added.map((a) => a.file) }))
    form.post(item ? `${base}/${item.id}` : base, { forceFormData: true })
  }

  const title = item ? `Edit ${schema.singular}` : `Tambah ${schema.singular}`

  return (
    <AdminLayout
      title={title}
      breadcrumbs={[{ label: 'Dashboard', href: '/admin' }, { label: schema.label, href: base }, { label: item ? 'Edit' : 'Tambah' }]}
      actions={
        <Button size="sm" type="submit" form="content-form" disabled={form.processing}>
          {form.processing && <Loader2 className="animate-spin" />}
          Simpan
        </Button>
      }
    >
      <Head title={title} />
      <form id="content-form" onSubmit={submit} className="grid items-start gap-6 lg:grid-cols-[1fr_320px]">
        <div className="grid gap-6">
          <Card>
            <CardHeader>
              <CardTitle>Isi</CardTitle>
              <CardDescription>Bahasa Indonesia wajib; English opsional (kosong = pakai teks Indonesia).</CardDescription>
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
              {textFields.map((f) => (
                <SchemaField key={f.name} field={f} lang={lang} value={form.data.data[f.name]} error={err(f.name, f.translatable)} onChange={(v) => setField(f.name, v)} />
              ))}
            </CardContent>
          </Card>

          {imageField && (
            <Card>
              <CardHeader>
                <CardTitle>{imageField.label}</CardTitle>
                <CardDescription>{imageField.hint}</CardDescription>
              </CardHeader>
              <CardContent className="grid gap-3">
                <div className="grid grid-cols-2 gap-3 md:grid-cols-3">
                  {kept.map((img, i) => (
                    <div key={img.full} className="group relative overflow-hidden rounded-lg border">
                      <img src={img.url} alt={`Halaman ${i + 1}`} className="aspect-[4/3] w-full object-cover" />
                      <span className="bg-background/80 absolute top-1.5 left-1.5 rounded px-1.5 font-mono text-xs">{i + 1}</span>
                      <div className="bg-background/90 absolute inset-x-0 bottom-0 flex justify-between p-1">
                        <div className="flex">
                          <Button type="button" variant="ghost" size="icon" className="size-7" disabled={i === 0} onClick={() => moveKept(i, -1)} aria-label="Geser kiri"><ArrowLeft /></Button>
                          <Button type="button" variant="ghost" size="icon" className="size-7" disabled={i === kept.length - 1} onClick={() => moveKept(i, 1)} aria-label="Geser kanan"><ArrowRight /></Button>
                        </div>
                        <Button type="button" variant="ghost" size="icon" className="size-7" onClick={() => setKept(kept.filter((_, j) => j !== i))} aria-label="Hapus halaman"><X /></Button>
                      </div>
                    </div>
                  ))}
                  {added.map((a, i) => (
                    <div key={a.url} className="relative overflow-hidden rounded-lg border border-dashed">
                      <img src={a.url} alt="Gambar baru" className="aspect-[4/3] w-full object-cover" />
                      <span className="bg-primary text-primary-foreground absolute top-1.5 left-1.5 rounded px-1.5 text-xs">Baru</span>
                      <Button type="button" variant="secondary" size="icon" className="absolute right-1 bottom-1 size-7" onClick={() => setAdded(added.filter((_, j) => j !== i))} aria-label="Batal tambah"><X /></Button>
                    </div>
                  ))}
                  <label className="bg-muted hover:bg-muted/70 text-muted-foreground flex aspect-[4/3] cursor-pointer flex-col items-center justify-center gap-1 rounded-lg border border-dashed text-sm transition-colors">
                    <ImageUp className="size-5" />
                    Tambah gambar
                    <input
                      type="file"
                      multiple
                      accept="image/png,image/jpeg,image/webp"
                      className="sr-only"
                      onChange={(e) => {
                        const files = Array.from(e.target.files ?? [])
                        setAdded([...added, ...files.map((file) => ({ file, url: URL.createObjectURL(file) }))])
                        e.target.value = ''
                      }}
                    />
                  </label>
                </div>
                <p className="text-muted-foreground text-xs">JPG, PNG, atau WebP, maks 5 MB per gambar.</p>
                {Object.entries(errors).filter(([k]) => k.startsWith('new_images')).map(([k, v]) => <p key={k} className="text-destructive text-sm">{v}</p>)}
              </CardContent>
            </Card>
          )}
        </div>

        <Card className="lg:sticky lg:top-20">
          <CardHeader><CardTitle>Publikasi</CardTitle></CardHeader>
          <CardContent className="grid gap-4">
            <div className="flex items-center justify-between gap-4">
              <Label htmlFor="published">Tayang di landing page</Label>
              <Switch id="published" checked={form.data.is_published} onCheckedChange={(v) => form.setData('is_published', v)} />
            </div>
            <div className="flex gap-2">
              <Button type="submit" className="flex-1" disabled={form.processing}>
                {form.processing && <Loader2 className="animate-spin" />}
                {item ? 'Simpan perubahan' : 'Simpan'}
              </Button>
              <Button type="button" variant="outline" asChild><Link href={base}>Batal</Link></Button>
            </div>
          </CardContent>
        </Card>
      </form>
    </AdminLayout>
  )
}
