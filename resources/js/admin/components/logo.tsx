import { cn } from '@/lib/utils'

/** MAI monogram (redrawn from the brand logo). Uses currentColor so it can sit on any surface. */
export function LogoMark({ className }: { className?: string }) {
  return (
    <svg viewBox="323 143 890 590" className={cn('fill-current', className)} role="img" aria-label="MAI">
      <polygon points="333,160 611,410 863,160 1178,722 1054,722 837,318 611,540 433,370 433,722 333,722" />
      <polygon points="832,365 870,435 688,722 593,722" />
      <polygon points="825,550 931,550 971,625 778,625" />
      <polygon points="1102,224 1202,153 1202,250 1102,322" />
      <polygon points="1102,350 1202,279 1202,722 1193,722 1102,562" />
    </svg>
  )
}

/** Full lockup: monogram over MUZAKI / ABDULLAH IRSYAD, as on the brand logo. */
export function LogoLockup({ className }: { className?: string }) {
  return (
    <div className={cn('text-primary flex flex-col items-center', className)}>
      <LogoMark className="h-14 w-auto" />
      <span className="mt-3 text-lg font-semibold tracking-[0.35em]">MUZAKI</span>
      <span className="mt-1 text-[0.65rem] font-medium tracking-[0.3em]">ABDULLAH IRSYAD</span>
    </div>
  )
}
