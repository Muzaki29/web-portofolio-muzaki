import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Switch } from '@/components/ui/switch'
import { Textarea } from '@/components/ui/textarea'
import type { FieldSchema, FieldValue, Locale, Translated } from '@/types'

/** One text/textarea/url/email input described by config/cms.php. Translatable fields edit the active locale. */
export function SchemaField({ field, value, lang, error, onChange }: {
  field: FieldSchema
  value: FieldValue | undefined
  lang: Locale
  error?: string
  onChange: (value: FieldValue) => void
}) {
  const id = `f-${field.name}`

  if (field.type === 'toggle') {
    return (
      <div className="flex items-center justify-between gap-4 rounded-lg border p-3">
        <div className="grid gap-0.5">
          <Label htmlFor={id}>{field.label}</Label>
          {field.hint && <p className="text-muted-foreground text-xs">{field.hint}</p>}
        </div>
        <Switch id={id} checked={value === true} onCheckedChange={(v) => onChange(v)} />
      </div>
    )
  }
  const current = field.translatable ? ((value as Translated | null)?.[lang] ?? '') : ((value as string | null) ?? '')
  const set = (v: string) =>
    onChange(field.translatable ? { id: '', en: '', ...((value as Translated | null) ?? {}), [lang]: v } : v)

  return (
    <div className="grid gap-2">
      <Label htmlFor={id}>
        {field.label}
        {field.required && <span className="text-destructive">*</span>}
        {field.translatable && <span className="text-muted-foreground ml-auto font-mono text-[10px] uppercase">{lang}</span>}
      </Label>
      {field.type === 'textarea' ? (
        <Textarea id={id} rows={field.name === 'highlights' ? 4 : 3} value={current} onChange={(e) => set(e.target.value)} aria-invalid={!!error} />
      ) : (
        <Input id={id} type={field.type === 'text' ? 'text' : field.type} value={current} onChange={(e) => set(e.target.value)} aria-invalid={!!error} />
      )}
      {field.hint && !error && <p className="text-muted-foreground text-xs">{field.hint}</p>}
      {error && <p className="text-destructive text-sm">{error}</p>}
    </div>
  )
}
