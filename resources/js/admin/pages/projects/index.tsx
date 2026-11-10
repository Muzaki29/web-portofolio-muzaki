import { Head, Link, router } from '@inertiajs/react'
import { ArrowDown, ArrowUp, ImageOff, MoreHorizontal, Pencil, Plus, Search, Trash2 } from 'lucide-react'
import { useMemo, useState } from 'react'

import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from '@/components/ui/alert-dialog'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Card } from '@/components/ui/card'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { Input } from '@/components/ui/input'
import { Switch } from '@/components/ui/switch'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { projectCode, StatusBadge } from '@/components/status-badge'
import { UnderlineTabs } from '@/components/underline-tabs'
import AdminLayout from '@/layouts/admin-layout'

type Row = {
  id: number
  title: string
  short: string | null
  image: string | null
  tags: string[]
  link_type: 'github' | 'live' | 'none'
  is_published: boolean
  updated_at: string | null
}

const linkLabel = { github: 'GitHub', live: 'Live', none: '—' }

export default function ProjectsIndex({ projects }: { projects: Row[] }) {
  const [query, setQuery] = useState('')
  const [status, setStatus] = useState<'all' | 'published' | 'draft'>('all')
  const [deleting, setDeleting] = useState<Row | null>(null)
  const filtered = useMemo(
    () =>
      projects.filter(
        (p) =>
          p.title.toLowerCase().includes(query.trim().toLowerCase()) &&
          (status === 'all' || (status === 'published') === p.is_published),
      ),
    [projects, query, status],
  )

  const move = (index: number, dir: -1 | 1) => {
    const ids = projects.map((p) => p.id)
    ;[ids[index], ids[index + dir]] = [ids[index + dir], ids[index]]
    router.post('/admin/projects/reorder', { ids }, { preserveScroll: true })
  }

  return (
    <AdminLayout
      title="Karya"
      tabs={
        <UnderlineTabs
          label="Filter status"
          className="px-4 md:px-6"
          value={status}
          onChange={setStatus}
          tabs={[
            { value: 'all', label: `Semua (${projects.length})` },
            { value: 'published', label: `Tayang (${projects.filter((p) => p.is_published).length})` },
            { value: 'draft', label: `Draft (${projects.filter((p) => !p.is_published).length})` },
          ]}
        />
      }
      breadcrumbs={[{ label: 'Dashboard', href: '/admin' }, { label: 'Karya' }]}
      actions={
        <Button size="sm" asChild>
          <Link href="/admin/projects/create"><Plus /> Tambah karya</Link>
        </Button>
      }
    >
      <Head title="Karya" />
      <div className="relative max-w-sm">
        <Search className="text-muted-foreground pointer-events-none absolute top-1/2 left-2.5 size-4 -translate-y-1/2" />
        <Input placeholder="Cari judul karya…" className="pl-8" value={query} onChange={(e) => setQuery(e.target.value)} />
      </div>

      <Card className="overflow-hidden py-0">
        <Table>
          <TableHeader className="bg-muted/50">
            <TableRow>
              <TableHead className="hidden w-28 sm:table-cell">Urutan</TableHead>
              <TableHead>Karya</TableHead>
              <TableHead className="hidden xl:table-cell">Teknologi</TableHead>
              <TableHead className="hidden lg:table-cell">Tautan</TableHead>
              <TableHead>Status</TableHead>
              <TableHead className="w-12"><span className="sr-only">Aksi</span></TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            {filtered.length === 0 && (
              <TableRow>
                <TableCell colSpan={6} className="text-muted-foreground h-24 text-center">
                  {projects.length === 0 ? 'Belum ada karya.' : 'Tidak ada karya yang cocok.'}
                </TableCell>
              </TableRow>
            )}
            {filtered.map((p) => {
              const index = projects.findIndex((x) => x.id === p.id)
              return (
                <TableRow key={p.id} className={p.is_published ? '' : 'text-muted-foreground'}>
                  <TableCell className="hidden sm:table-cell">
                    <div className="flex items-center gap-1">
                      <span className="text-muted-foreground w-5 text-xs tabular-nums">{index + 1}</span>
                      <Button variant="ghost" size="icon" className="size-7" disabled={!!query || status !== 'all' || index === 0} onClick={() => move(index, -1)} aria-label="Naikkan">
                        <ArrowUp />
                      </Button>
                      <Button variant="ghost" size="icon" className="size-7" disabled={!!query || status !== 'all' || index === projects.length - 1} onClick={() => move(index, 1)} aria-label="Turunkan">
                        <ArrowDown />
                      </Button>
                    </div>
                  </TableCell>
                  <TableCell className="w-full max-w-0">
                    <div className="flex items-center gap-3">
                      <div className="bg-muted hidden h-10 w-16 shrink-0 sm:flex items-center justify-center overflow-hidden rounded-md border">
                        {p.image ? <img src={p.image} alt="" className="size-full object-cover" /> : <ImageOff className="text-muted-foreground size-4" />}
                      </div>
                      <div className="min-w-0">
                        <div className="flex items-center gap-2">
                          <span className="text-muted-foreground hidden font-mono text-xs md:inline">{projectCode(p.id)}</span>
                          <Link href={`/admin/projects/${p.id}/edit`} className="text-foreground truncate font-medium hover:underline">{p.title}</Link>
                        </div>
                        <p className="text-muted-foreground truncate text-xs">{p.short}</p>
                      </div>
                    </div>
                  </TableCell>
                  <TableCell className="hidden xl:table-cell">
                    <div className="flex gap-1">
                      {p.tags.slice(0, 2).map((t) => <Badge key={t} variant="outline" className="whitespace-nowrap">{t}</Badge>)}
                      {p.tags.length > 2 && <Badge variant="outline">+{p.tags.length - 2}</Badge>}
                    </div>
                  </TableCell>
                  <TableCell className="text-muted-foreground hidden text-sm lg:table-cell">{linkLabel[p.link_type]}</TableCell>
                  <TableCell>
                    <div className="flex items-center gap-3">
                    <StatusBadge tone={p.is_published ? 'published' : 'draft'} className="hidden md:inline-flex">{p.is_published ? 'Tayang' : 'Draft'}</StatusBadge>
                    <Switch
                      checked={p.is_published}
                      onCheckedChange={() => router.patch(`/admin/projects/${p.id}/toggle`, {}, { preserveScroll: true })}
                      aria-label={`Tayangkan ${p.title}`}
                    />
                    </div>
                  </TableCell>
                  <TableCell>
                    <DropdownMenu>
                      <DropdownMenuTrigger asChild>
                        <Button variant="ghost" size="icon" className="size-8" aria-label={`Aksi untuk ${p.title}`}><MoreHorizontal /></Button>
                      </DropdownMenuTrigger>
                      <DropdownMenuContent align="end">
                        <DropdownMenuItem asChild>
                          <Link href={`/admin/projects/${p.id}/edit`}><Pencil /> Edit</Link>
                        </DropdownMenuItem>
                        <DropdownMenuSeparator />
                        <DropdownMenuItem variant="destructive" onSelect={() => setDeleting(p)}>
                          <Trash2 /> Hapus
                        </DropdownMenuItem>
                      </DropdownMenuContent>
                    </DropdownMenu>
                  </TableCell>
                </TableRow>
              )
            })}
          </TableBody>
        </Table>
      </Card>

      <AlertDialog open={!!deleting} onOpenChange={(open) => !open && setDeleting(null)}>
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>Hapus “{deleting?.title}”?</AlertDialogTitle>
            <AlertDialogDescription>
              Karya ini hilang dari landing page dan tidak bisa dikembalikan. Kalau hanya ingin menyembunyikan, matikan saklar Tayang.
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel>Batal</AlertDialogCancel>
            <AlertDialogAction
              className="bg-destructive text-white hover:bg-destructive/90"
              onClick={() => deleting && router.delete(`/admin/projects/${deleting.id}`, { preserveScroll: true, onFinish: () => setDeleting(null) })}
            >
              Hapus karya
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>
    </AdminLayout>
  )
}
