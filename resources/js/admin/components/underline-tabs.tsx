import { cn } from '@/lib/utils'

type Tab<T extends string> = { value: T; label: string; alert?: boolean }

/** Underlined tab strip, as in the reference admin's "Overview / Assigned to me" row. */
export function UnderlineTabs<T extends string>({ tabs, value, onChange, label, className }: {
  tabs: Tab<T>[]
  value: T
  onChange: (value: T) => void
  label: string
  className?: string
}) {
  return (
    <div role="tablist" aria-label={label} className={cn('flex gap-1 border-b', className)}>
      {tabs.map((t) => (
        <button
          key={t.value}
          type="button"
          role="tab"
          aria-selected={value === t.value}
          onClick={() => onChange(t.value)}
          className={cn(
            'relative -mb-px inline-flex items-center gap-1.5 border-b-2 px-3 py-2.5 text-sm font-medium transition-colors',
            value === t.value ? 'border-foreground text-foreground' : 'text-muted-foreground hover:text-foreground border-transparent',
          )}
        >
          {t.label}
          {t.alert && <span className="bg-destructive size-1.5 rounded-full" aria-label="ada kesalahan" />}
        </button>
      ))}
    </div>
  )
}
