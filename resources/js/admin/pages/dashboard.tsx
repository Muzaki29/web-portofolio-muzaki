import { Head, Link, router } from '@inertiajs/react'
import { Award, BriefcaseBusiness, CalendarDays, Eye, FolderKanban, GraduationCap, Plus, TrendingDown, TrendingUp, Users } from 'lucide-react'
import { Area, AreaChart, CartesianGrid, XAxis, YAxis } from 'recharts'

import { projectCode, SectionHeading, StatusBadge } from '@/components/status-badge'
import { UnderlineTabs } from '@/components/underline-tabs'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { ChartContainer, ChartTooltip, ChartTooltipContent, type ChartConfig } from '@/components/ui/chart'
import AdminLayout from '@/layouts/admin-layout'

type Props = {
  range: 7 | 30 | 90
  visits: {
    series: { date: string; views: number; visitors: number }[]
    views: number
    visitors: number
    today: number
    all_time: number
    change: number | null
    devices: { name: string; value: number }[]
    referrers: { host: string; count: number }[]
  }
  content: { projects: number; experience: number; timeline: number; certificate: number; drafts: number }
  recent: { id: number; title: string; is_published: boolean; updated_at: string }[]
}

const chartConfig = {
  views: { label: 'Kunjungan', color: 'var(--primary)' },
  visitors: { label: 'Pengunjung unik', color: 'var(--muted-foreground)' },
} satisfies ChartConfig

const fmtDay = (d: string) => new Date(d).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' })

export default function Dashboard({ range, visits, content, recent }: Props) {
  const stats = [
    { label: `Kunjungan ${range} hari`, value: visits.views, icon: Eye, change: visits.change },
    { label: 'Pengunjung unik', value: visits.visitors, icon: Users },
    { label: 'Hari ini', value: visits.today, icon: CalendarDays },
    { label: 'Total sepanjang waktu', value: visits.all_time, icon: TrendingUp },
  ]

  const sections = [
    { label: 'Karya', icon: FolderKanban, href: '/admin/projects', count: content.projects },
    { label: 'Pengalaman', icon: BriefcaseBusiness, href: '/admin/content/experience', count: content.experience },
    { label: 'Timeline', icon: GraduationCap, href: '/admin/content/timeline', count: content.timeline },
    { label: 'Sertifikat', icon: Award, href: '/admin/content/certificate', count: content.certificate },
  ]

  const deviceTotal = Math.max(1, visits.devices.reduce((s, d) => s + d.value, 0))
  const maxRef = Math.max(1, ...visits.referrers.map((r) => r.count))

  return (
    <AdminLayout
      title="Ringkasan"
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
        <Button size="sm" variant="outline" asChild>
          <a href="/" target="_blank" rel="noopener">Lihat landing page</a>
        </Button>
      }
    >
      <Head title="Dashboard" />

      <section className="grid gap-3">
        <SectionHeading>Pengunjung landing page</SectionHeading>
        <div className="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
          {stats.map((c) => (
            <Card key={c.label} className="flex-row items-center gap-4 p-4">
              <div className="bg-muted flex size-10 shrink-0 items-center justify-center rounded-md border">
                <c.icon className="text-muted-foreground size-4" />
              </div>
              <div className="grid gap-0.5">
                <span className="text-muted-foreground text-sm">{c.label}</span>
                <div className="flex items-baseline gap-2">
                  <span className="text-xl font-semibold tabular-nums">{c.value.toLocaleString('id-ID')}</span>
                  {c.change != null && (
                    <span className={`inline-flex items-center gap-0.5 text-xs ${c.change >= 0 ? 'text-emerald-500' : 'text-destructive'}`}>
                      {c.change >= 0 ? <TrendingUp className="size-3" /> : <TrendingDown className="size-3" />}
                      {c.change > 0 ? '+' : ''}{c.change}%
                    </span>
                  )}
                </div>
              </div>
            </Card>
          ))}
        </div>
      </section>

      <div className="grid gap-6 xl:grid-cols-[1fr_320px]">
        <Card>
          <CardHeader>
            <CardTitle>Tren kunjungan</CardTitle>
            <CardDescription>Kunjungan dan pengunjung unik per hari, tanpa bot dan tanpa kunjungan admin.</CardDescription>
          </CardHeader>
          <CardContent>
            <ChartContainer config={chartConfig} className="aspect-auto h-64 w-full">
              <AreaChart data={visits.series} margin={{ left: -16, right: 8, top: 8 }}>
                <defs>
                  <linearGradient id="fillViews" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="5%" stopColor="var(--color-views)" stopOpacity={0.35} />
                    <stop offset="95%" stopColor="var(--color-views)" stopOpacity={0.02} />
                  </linearGradient>
                </defs>
                <CartesianGrid vertical={false} strokeDasharray="3 3" />
                <XAxis dataKey="date" tickLine={false} axisLine={false} tickMargin={8} minTickGap={24} tickFormatter={fmtDay} />
                <YAxis allowDecimals={false} tickLine={false} axisLine={false} width={40} />
                <ChartTooltip content={<ChartTooltipContent labelFormatter={(v) => fmtDay(String(v))} />} />
                <Area dataKey="visitors" type="monotone" stroke="var(--color-visitors)" fill="transparent" strokeDasharray="4 4" strokeWidth={1.5} />
                <Area dataKey="views" type="monotone" stroke="var(--color-views)" fill="url(#fillViews)" strokeWidth={2} />
              </AreaChart>
            </ChartContainer>
          </CardContent>
        </Card>

        <div className="grid content-start gap-6">
          <Card>
            <CardHeader><CardTitle>Perangkat</CardTitle></CardHeader>
            <CardContent className="grid gap-3">
              <div className="bg-muted flex h-2 overflow-hidden rounded-full">
                <div className="bg-primary h-full" style={{ width: `${(visits.devices[0].value / deviceTotal) * 100}%` }} />
                <div className="bg-muted-foreground/60 h-full" style={{ width: `${(visits.devices[1].value / deviceTotal) * 100}%` }} />
              </div>
              {visits.devices.map((d, i) => (
                <div key={d.name} className="flex items-center justify-between text-sm">
                  <span className="flex items-center gap-2">
                    <span className={`size-2 rounded-full ${i === 0 ? 'bg-primary' : 'bg-muted-foreground/60'}`} />
                    {d.name}
                  </span>
                  <span className="text-muted-foreground tabular-nums">{Math.round((d.value / deviceTotal) * 100)}%</span>
                </div>
              ))}
            </CardContent>
          </Card>
          <Card>
            <CardHeader>
              <CardTitle>Sumber kunjungan</CardTitle>
              <CardDescription>Situs yang mengarahkan pengunjung.</CardDescription>
            </CardHeader>
            <CardContent className="grid gap-3">
              {visits.referrers.length === 0 && <p className="text-muted-foreground text-sm">Belum ada kunjungan.</p>}
              {visits.referrers.map((r) => (
                <div key={r.host} className="grid gap-1.5">
                  <div className="flex justify-between gap-3 text-sm">
                    <span className="truncate">{r.host}</span>
                    <span className="text-muted-foreground tabular-nums">{r.count}</span>
                  </div>
                  <div className="bg-muted h-1.5 overflow-hidden rounded-full">
                    <div className="bg-primary h-full rounded-full" style={{ width: `${(r.count / maxRef) * 100}%` }} />
                  </div>
                </div>
              ))}
            </CardContent>
          </Card>
        </div>
      </div>

      <section className="grid gap-3">
        <SectionHeading>Konten landing page</SectionHeading>
        <div className="grid grid-cols-2 gap-3 xl:grid-cols-4">
          {sections.map((s) => (
            <Link key={s.label} href={s.href} className="bg-card hover:bg-accent grid gap-2 rounded-xl border p-4 transition-colors">
              <div className="text-muted-foreground flex items-center gap-2 text-sm">
                <s.icon className="size-4" />
                {s.label}
              </div>
              <span className="text-xl font-semibold tabular-nums">{s.count}</span>
            </Link>
          ))}
        </div>
        {content.drafts > 0 && <p className="text-muted-foreground text-sm">{content.drafts} item masih draft dan tidak tampil di landing page.</p>}
      </section>

      <section className="grid gap-3">
        <SectionHeading action={<Button size="sm" asChild><Link href="/admin/projects/create"><Plus /> Tambah karya</Link></Button>}>
          Karya terbaru
        </SectionHeading>
        <Card className="gap-0 py-0">
          <ul className="divide-y">
            {recent.map((p) => (
              <li key={p.id} className="flex items-center gap-3 px-4 py-3">
                <span className="text-muted-foreground font-mono text-xs">{projectCode(p.id)}</span>
                <Link href={`/admin/projects/${p.id}/edit`} className="min-w-0 flex-1 truncate font-medium hover:underline">{p.title}</Link>
                <span className="text-muted-foreground hidden text-xs sm:inline">{p.updated_at}</span>
                <StatusBadge tone={p.is_published ? 'published' : 'draft'}>{p.is_published ? 'Tayang' : 'Draft'}</StatusBadge>
              </li>
            ))}
          </ul>
        </Card>
      </section>
    </AdminLayout>
  )
}
