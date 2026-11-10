import { Head, useForm } from '@inertiajs/react'
import { Loader2 } from 'lucide-react'
import { useState, type FormEvent } from 'react'

import { SchemaField } from '@/components/schema-field'
import { UnderlineTabs } from '@/components/underline-tabs'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import AdminLayout from '@/layouts/admin-layout'
import type { FieldSchema, FieldValues, Locale } from '@/types'

export default function Settings({ fields, values }: { fields: FieldSchema[]; values: FieldValues }) {
  const [lang, setLang] = useState<Locale>('id')
  const form = useForm({ values: { ...values } })
  const errors = form.errors as Record<string, string>
  const groups = [...new Set(fields.map((f) => f.group ?? 'Umum'))]

  const submit = (e: FormEvent) => {
    e.preventDefault()
    form.put('/admin/settings', { preserveScroll: true })
  }

  return (
    <AdminLayout
      title="Profil & Kontak"
      breadcrumbs={[{ label: 'Dashboard', href: '/admin' }, { label: 'Profil & Kontak' }]}
      tabs={
        <UnderlineTabs
          label="Bahasa"
          className="px-4 md:px-6"
          value={lang}
          onChange={setLang}
          tabs={[
            { value: 'id', label: 'Indonesia' },
            { value: 'en', label: 'English' },
          ]}
        />
      }
      actions={
        <Button size="sm" type="submit" form="settings-form" disabled={form.processing}>
          {form.processing && <Loader2 className="animate-spin" />}
          Simpan
        </Button>
      }
    >
      <Head title="Profil & Kontak" />
      <form id="settings-form" onSubmit={submit} className="grid max-w-3xl gap-6">
        {groups.map((g) => (
          <Card key={g}>
            <CardHeader>
              <CardTitle>{g}</CardTitle>
              {g === 'Profil' && <CardDescription>Teks yang tampil di hero dan bagian Tentang. Field bertanda ID/EN mengikuti tab bahasa di atas.</CardDescription>}
            </CardHeader>
            <CardContent className="grid gap-5">
              {fields.filter((f) => (f.group ?? 'Umum') === g).map((f) => (
                <SchemaField
                  key={f.name}
                  field={f}
                  lang={lang}
                  value={form.data.values[f.name]}
                  error={errors[f.translatable ? `values.${f.name}.${lang}` : `values.${f.name}`]}
                  onChange={(v) => form.setData('values', { ...form.data.values, [f.name]: v })}
                />
              ))}
            </CardContent>
          </Card>
        ))}
      </form>
    </AdminLayout>
  )
}
