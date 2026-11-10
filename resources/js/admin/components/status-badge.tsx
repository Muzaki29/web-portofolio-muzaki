import { cn } from '@/lib/utils'

const tones = {
  published: 'border-emerald-500/30 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400',
  draft: 'border-amber-500/30 bg-amber-500/10 text-amber-600 dark:text-amber-400',
  soon: 'border-border bg-muted text-muted-foreground',
}

/** Pill with a leading dot, as in the reference admin's status column. */
export function StatusBadge({ tone, children, className }: { tone: keyof typeof tones; children: React.ReactNode; className?: string }) {
  return (
    <span className={cn('inline-flex items-center gap-1.5 rounded-md border px-2 py-0.5 text-xs font-medium whitespace-nowrap', tones[tone], className)}>
      <span className="size-1.5 rounded-full bg-current" aria-hidden />
      {children}
    </span>
  )
}

export function SectionHeading({ children, action }: { children: React.ReactNode; action?: React.ReactNode }) {
  return (
    <div className="flex items-center justify-between gap-4">
      <h2 className="text-base font-semibold">{children}</h2>
      {action}
    </div>
  )
}

/** Short mono identifier for a project, e.g. KRY-001. */
export const projectCode = (id: number) => `KRY-${String(id).padStart(3, '0')}`
