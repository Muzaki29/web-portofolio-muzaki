import { Head, Link, router, usePage } from '@inertiajs/react'
import {
  ArrowUpRight,
  Award,
  BriefcaseBusiness,
  CalendarDays,
  ExternalLink,
  Eye,
  FileText,
  FolderKanban,
  GraduationCap,
  Laptop,
  Plus,
  Smartphone,
  TrendingDown,
  TrendingUp,
  Users,
} from 'lucide-react'
import { useState } from 'react'
import { Area, AreaChart, CartesianGrid, XAxis, YAxis } from 'recharts'

import { SectionHeading } from '@/components/status-badge'
import { UnderlineTabs } from '@/components/underline-tabs'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Card, CardAction, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { ChartContainer, ChartTooltip, ChartTooltipContent, type ChartConfig } from '@/components/ui/chart'
import { Progress } from '@/components/ui/progress'
import { ToggleGroup, ToggleGroupItem } from '@/components/ui/toggle-group'
import { Tooltip, TooltipContent, TooltipTrigger } from '@/components/ui/tooltip'
import AdminLayout from '@/layouts/admin-layout'
import type { SharedProps } from '@/types'

type Point = { date: string; views: number; visitors: number }
type Props = {
  range: 7 | 30 | 90
  visits: {
    series: Point[]
    views: number
    visitors: number
    today: number
    all_time: number
    change: number | null
    devices: { name: string; value: number }[]
    referrers: { host: string; count: number }[]
  }
  caseStudies: { slug: string; title: string; count: number; id: number | null }[]
  landingViews: number
  completeness: { id: number; title: string; featured: boolean; percent: number; missing: string[] }[]
  content: { projects: number; experience: number; timeline: number; certificate: number; drafts: number }
}

const fmtDay = (d: string) => new Date(d).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' })
const num = (n: number) => n.toLocaleString('id-ID')

/** Tiny trend line inside a KPI card. */
function Sparkline({ data, dataKey }: { data: Point[]; dataKey: 'views' | 'visitors' }) {
  const id = `spark-${dataKey}`
  return (
    <ChartContainer config={{ [dataKey]: { label: dataKey, color: 'var(--primary)' } }} className="aspect-auto h-10 w-24">
      <AreaChart data={data} margin={{ top: 2, bottom: 2, left: 0, right: 0 }}>
        <defs>
          <linearGradient id={id} x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stopColor="var(--primary)" stopOpacity={0.35} />
            <stop offset="100%" stopColor="var(--primary)" stopOpacity={0} />
          </linearGradient>
        </defs>
        <Area dataKey={dataKey} type="monotone" stroke="var(--primary)" strokeWidth={1.5} fill={`url(#${id})`} isAnimationActive={false} />
      </AreaChart>
    </ChartContainer>
  )
}

export default function Dashboard({ range, visits, caseStudies, landingViews, completeness, content }: Props) {
  const { auth } = usePage<SharedProps>().props
  const [metric, setMetric] = useState<'views' | 'visitors'>('views')
  const firstName = (auth.user?.name ?? '').split(' ')[0]

  const kpis = [
    { label: 'Kunjungan', value: visits.views, icon: Eye, change: visits.change, spark: 'views' as const, hint: `${range} hari terakhir` },
    { label: 'Pengunjung unik', value: visits.visitors, icon: Users, spark: 'visitors' as const, hint: 'per hari, tanpa bot' },
    { label: 'Hari ini', value: visits.today, icon: CalendarDays, hint: 'kunjungan sejak 00.00' },
    { label: 'Total', value: visits.all_time, icon: TrendingUp, hint: 'sepanjang waktu' },
  ]
  const sections = [
    { label: 'Karya', icon: FolderKanban, href: '/admin/projects', count: content.projects },
    { label: 'Pengalaman', icon: BriefcaseBusiness, href: '/admin/content/experience', count: content.experience },
    { label: 'Timeline', icon: GraduationCap, href: '/admin/content/timeline', count: content.timeline },
    { label: 'Sertifikat', icon: Award, href: '/admin/content/certificate', count: content.certificate },
  ]
  const deviceTotal = Math.max(1, visits.devices.reduce((s, d) => s + d.value, 0))
  const maxRef = Math.max(1, ...visits.referrers.map((r) => r.count))
  const maxCase = Math.max(1, ...caseStudies.map((c) => c.count))
  const caseTotal = caseStudies.reduce((s, c) => s + c.count, 0)
  const avgComplete = completeness.length ? Math.round(completeness.reduce((s, c) => s + c.percent, 0) / completeness.length) : 0
  const chartConfig = {
    [metric]: { label: metric === 'views' ? 'Kunjungan' : 'Pengunjung unik', color: 'var(--primary)' },
  } satisfies ChartConfig

  return (
    <AdminLayout
      title={firstName ? `Halo, ${firstName}` : 'Ringkasan'}
      breadcrumbs={[{ label: 'Dashboard' }]}
      tabs={
        <UnderlineTabs
          label="Rentang waktu"
          className="px-4 md:px-6"
          value={String(range)}
          onChange={(v) => router.get('/admin', { range: v }, { preserveScroll: true, preserveState: true, replace: true })}
          tabs={[
            { value: '7', label: '7 hari' },
            { value: '30', label: '30 hari' },
            { value: '90', label: '90 hari' },
          ]}
        />
      }
      actions={
        <div className="flex flex-wrap gap-2">
          <Button size="sm" variant="outline" asChild>
            <a href="/" target="_blank" rel="noopener"><ExternalLink /> Lihat situs</a>
          </Button>
          <Button size="sm" asChild>
            <Link href="/admin/projects/create"><Plus /> Karya baru</Link>
          </Button>
        </div>
      }
    >
      <Head title="Dashboard" />

      {/* KPI row */}
      <section className="grid gap-3 sm:grid-cols-2 xl:grid-cols-4" aria-label="Ringkasan kunjungan">
        {kpis.map((k) => (
          <Card key={k.label} className="gap-3 p-5 transition-colors hover:border-primary/40">
            <div className="flex items-center justify-between">
              <span className="text-muted-foreground flex items-center gap-2 text-sm"><k.icon className="size-4" />{k.label}</span>
              {k.change != null && (
                <Badge variant="outline" className={k.change >= 0 ? 'text-emerald-500' : 'text-destructive'}>
                  {k.change >= 0 ? <TrendingUp /> : <TrendingDown />}
                  {k.change > 0 ? '+' : ''}{k.change}%
                </Badge>
              )}
            </div>
            <div className="flex items-end justify-between gap-3">
              <div>
                <div className="text-3xl font-semibold tracking-tight tabular-nums">{num(k.value)}</div>
                <p className="text-muted-foreground mt-1 text-xs">{k.hint}</p>
              </div>
              {k.spark && <Sparkline data={visits.series} dataKey={k.spark} />}
            </div>
          </Card>
        ))}
      </section>

      {/* Trend + side blocks */}
      <div className="grid gap-4 xl:grid-cols-3">
        <Card className="xl:col-span-2">
          <CardHeader>
            <CardTitle>Tren kunjungan</CardTitle>
            <CardDescription>Landing page dan case study, tanpa bot dan kunjungan admin.</CardDescription>
            <CardAction>
              <ToggleGroup type="single" variant="outline" size="sm" value={metric} onValueChange={(v) => v && setMetric(v as 'views' | 'visitors')} aria-label="Metrik grafik">
                <ToggleGroupItem value="views">Kunjungan</ToggleGroupItem>
                <ToggleGroupItem value="visitors">Pengunjung</ToggleGroupItem>
              </ToggleGroup>
            </CardAction>
          </CardHeader>
          <CardContent>
            <ChartContainer config={chartConfig} className="aspect-auto h-72 w-full">
              <AreaChart data={visits.series} margin={{ left: -16, right: 8, top: 8 }}>
                <defs>
                  <linearGradient id="fillMain" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="5%" stopColor="var(--primary)" stopOpacity={0.4} />
                    <stop offset="95%" stopColor="var(--primary)" stopOpacity={0.02} />
                  </linearGradient>
                </defs>
                <CartesianGrid vertical={false} strokeDasharray="3 3" />
                <XAxis dataKey="date" tickLine={false} axisLine={false} tickMargin={8} minTickGap={28} tickFormatter={fmtDay} />
                <YAxis allowDecimals={false} tickLine={false} axisLine={false} width={40} />
                <ChartTooltip cursor={{ stroke: 'var(--border)' }} content={<ChartTooltipContent indicator="line" labelFormatter={(v) => fmtDay(String(v))} />} />
                <Area dataKey={metric} type="monotone" stroke="var(--primary)" fill="url(#fillMain)" strokeWidth={2} />
              </AreaChart>
            </ChartContainer>
          </CardContent>
        </Card>

        <div className="grid content-start gap-4">
          <Card>
            <CardHeader>
              <CardTitle>Perangkat</CardTitle>
              <CardDescription>{num(deviceTotal === 1 && visits.devices.every((d) => !d.value) ? 0 : deviceTotal)} kunjungan</CardDescription>
            </CardHeader>
            <CardContent className="grid gap-4">
              <div className="bg-muted flex h-2.5 overflow-hidden rounded-full">
                {visits.devices.map((d, i) => (
                  <Tooltip key={d.name}>
                    <TooltipTrigger asChild>
                      <div className={i === 0 ? 'bg-primary h-full' : 'bg-muted-foreground/50 h-full'} style={{ width: `${(d.value / deviceTotal) * 100}%` }} />
                    </TooltipTrigger>
                    <TooltipContent>{d.name}: {num(d.value)}</TooltipContent>
                  </Tooltip>
                ))}
              </div>
              <div className="grid grid-cols-2 gap-3">
                {visits.devices.map((d, i) => {
                  const Icon = i === 0 ? Laptop : Smartphone
                  return (
                    <div key={d.name} className="rounded-lg border p-3">
                      <div className="text-muted-foreground flex items-center gap-1.5 text-xs"><Icon className="size-3.5" />{d.name}</div>
                      <div className="mt-1 text-lg font-semibold tabular-nums">{Math.round((d.value / deviceTotal) * 100)}%</div>
                    </div>
                  )
                })}
              </div>
            </CardContent>
          </Card>

          <Card>
            <CardHeader>
              <CardTitle>Sumber kunjungan</CardTitle>
              <CardDescription>Situs yang mengarahkan pengunjung.</CardDescription>
            </CardHeader>
            <CardContent className="grid gap-3">
              {visits.referrers.length === 0 && <p className="text-muted-foreground text-sm">Belum ada kunjungan di rentang ini.</p>}
              {visits.referrers.map((r) => (
                <div key={r.host} className="grid gap-1.5">
                  <div className="flex justify-between gap-3 text-sm"><span className="truncate">{r.host}</span><span className="text-muted-foreground tabular-nums">{num(r.count)}</span></div>
                  <Progress value={(r.count / maxRef) * 100} className="h-1.5" />
                </div>
              ))}
            </CardContent>
          </Card>
        </div>
      </div>

      {/* Case studies + completeness */}
      <div className="grid gap-4 xl:grid-cols-3">
        <Card>
          <CardHeader>
            <CardTitle>Case study terpopuler</CardTitle>
            <CardDescription>{num(caseTotal)} kunjungan case study · {num(landingViews)} landing page</CardDescription>
          </CardHeader>
          <CardContent className="grid gap-1">
            {caseStudies.length === 0 && <p className="text-muted-foreground text-sm">Belum ada yang membuka case study di rentang ini.</p>}
            {caseStudies.map((c, i) => (
              <a key={c.slug} href={`/work/${c.slug}`} target="_blank" rel="noopener" className="group hover:bg-accent -mx-2 grid gap-1.5 rounded-md px-2 py-2 transition-colors">
                <div className="flex items-center justify-between gap-3 text-sm">
                  <span className="flex min-w-0 items-center gap-2">
                    <span className="text-muted-foreground w-4 font-mono text-xs">{i + 1}</span>
                    <span className="truncate font-medium">{c.title}</span>
                  </span>
                  <span className="text-muted-foreground flex items-center gap-1 tabular-nums">{num(c.count)}<ArrowUpRight className="size-3.5 opacity-0 transition-opacity group-hover:opacity-100" /></span>
                </div>
                <Progress value={(c.count / maxCase) * 100} className="h-1" />
              </a>
            ))}
          </CardContent>
        </Card>

        <Card className="xl:col-span-2">
          <CardHeader>
            <CardTitle>Kelengkapan case study</CardTitle>
            <CardDescription>Rata-rata {avgComplete}% · bagian kosong tidak tampil di halaman publik.</CardDescription>
            <CardAction>
              <Button size="sm" variant="ghost" asChild><Link href="/admin/projects">Semua karya</Link></Button>
            </CardAction>
          </CardHeader>
          <CardContent className="grid gap-1">
            {completeness.slice(0, 6).map((p) => (
              <Link key={p.id} href={`/admin/projects/${p.id}/edit`} className="hover:bg-accent -mx-2 grid gap-2 rounded-md px-2 py-2.5 transition-colors sm:grid-cols-[minmax(0,1fr)_140px_minmax(0,1.2fr)] sm:items-center sm:gap-4">
                <span className="flex min-w-0 items-center gap-2 text-sm font-medium">
                  <span className="truncate">{p.title}</span>
                  {p.featured && <Badge variant="secondary" className="shrink-0">Pilihan</Badge>}
                </span>
                <span className="flex items-center gap-2">
                  <Progress value={p.percent} className="h-1.5" />
                  <span className="text-muted-foreground w-9 text-right text-xs tabular-nums">{p.percent}%</span>
                </span>
                <span className="flex flex-wrap gap-1">
                  {p.missing.length === 0
                    ? <Badge variant="outline" className="text-emerald-500">Lengkap</Badge>
                    : p.missing.map((m) => <Badge key={m} variant="outline" className="text-muted-foreground font-normal">{m}</Badge>)}
                </span>
              </Link>
            ))}
          </CardContent>
        </Card>
      </div>

      {/* Content shortcuts */}
      <section className="grid gap-3">
        <SectionHeading>Konten landing page</SectionHeading>
        <div className="grid grid-cols-2 gap-3 lg:grid-cols-5">
          {sections.map((s) => (
            <Link key={s.label} href={s.href} className="bg-card hover:border-primary/40 group grid gap-3 rounded-xl border p-4 transition-colors">
              <div className="flex items-center justify-between">
                <div className="bg-muted flex size-9 items-center justify-center rounded-md border"><s.icon className="text-muted-foreground size-4" /></div>
                <ArrowUpRight className="text-muted-foreground size-4 opacity-0 transition-opacity group-hover:opacity-100" />
              </div>
              <div>
                <div className="text-2xl font-semibold tabular-nums">{s.count}</div>
                <div className="text-muted-foreground text-sm">{s.label}</div>
              </div>
            </Link>
          ))}
          <Link href="/admin/settings" className="bg-card hover:border-primary/40 group col-span-2 grid gap-3 rounded-xl border p-4 transition-colors lg:col-span-1">
            <div className="flex items-center justify-between">
              <div className="bg-muted flex size-9 items-center justify-center rounded-md border"><FileText className="text-muted-foreground size-4" /></div>
              <ArrowUpRight className="text-muted-foreground size-4 opacity-0 transition-opacity group-hover:opacity-100" />
            </div>
            <div>
              <div className="text-sm font-medium">Profil & Kontak</div>
              <div className="text-muted-foreground text-sm">{content.drafts > 0 ? `${content.drafts} item draft` : 'Email, sosial, teks hero'}</div>
            </div>
          </Link>
        </div>
      </section>
    </AdminLayout>
  )
}
