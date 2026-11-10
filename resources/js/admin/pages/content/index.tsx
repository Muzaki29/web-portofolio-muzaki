import { Head, Link, router } from '@inertiajs/react'
import { ArrowDown, ArrowUp, MoreHorizontal, Pencil, Plus, Search, Trash2 } from 'lucide-react'
import { useMemo, useState } from 'react'

import { StatusBadge } from '@/components/status-badge'
import { UnderlineTabs } from '@/components/underline-tabs'
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
import { Button } from '@/components/ui/button'
import { Card } from '@/components/ui/card'
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu'
import { Input } from '@/components/ui/input'
import { Switch } from '@/components/ui/switch'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import AdminLayout from '@/layouts/admin-layout'
import type { SectionSchema } from '@/types'

type Row = { id: number; title: string; subtitle: string | null; meta: string | null; thumb: string | null; is_published: boolean }

export default function ContentIndex({ section, schema, items }: { section: string; schema: SectionSchema; items: Row[] }) {
  const base = `/admin/content/${section}`
  const [query, setQuery] = useState('')
  const [status, setStatus] = useState<'all' | 'published' | 'draft'>('all')
  const [deleting, setDeleting] = useState<Row | null>(null)
  const filtered = useMemo(
    () =>
      items.filter(
        (i) =>
          `${i.title} ${i.subtitle ?? ''}`.toLowerCase().includes(query.trim().toLowerCase()) &&
          (status === 'all' || (status === 'published') === i.is_published),
      ),
    [items, query, status],
  )
  const reorderable = !query && status === 'all'

  const move = (index: number, dir: -1 | 1) => {
    const ids = items.map((i) => i.id)
    ;[ids[index], ids[index + dir]] = [ids[index + dir], ids[index]]
    router.post(`${base}/reorder`, { ids }, { preserveScroll: true })
  }

  return (
    <AdminLayout
      title={schema.label}
      breadcrumbs={[{ label: 'Dashboard', href: '/admin' }, { label: schema.label }]}
      tabs={
        <UnderlineTabs
          label="Filter status"
          className="px-4 md:px-6"
          value={status}
          onChange={setStatus}
          tabs={[
            { value: 'all', label: `Semua (${items.length})` },
            { value: 'published', label: `Tayang (${items.filter((i) => i.is_published).length})` },
            { value: 'draft', label: `Draft (${items.filter((i) => !i.is_published).length})` },
          ]}
        />
      }
      actions={
        <Button size="sm" asChild>
          <Link href={`${base}/create`}><Plus /> Tambah {schema.singular}</Link>
        </Button>
      }
    >
      <Head title={schema.label} />
      <div className="relative max-w-sm">
        <Search className="text-muted-foreground pointer-events-none absolute top-1/2 left-2.5 size-4 -translate-y-1/2" />
        <Input placeholder={`Cari ${schema.singular}…`} className="pl-8" value={query} onChange={(e) => setQuery(e.target.value)} />
      </div>

      <Card className="overflow-hidden py-0">
        <Table>
          <TableHeader className="bg-muted/50">
            <TableRow>
              <TableHead className="hidden w-28 sm:table-cell">Urutan</TableHead>
              <TableHead>{schema.label}</TableHead>
              <TableHead className="hidden lg:table-cell">Periode</TableHead>
              <TableHead>Status</TableHead>
              <TableHead className="w-12"><span className="sr-only">Aksi</span></TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            {filtered.length === 0 && (
              <TableRow>
                <TableCell colSpan={5} className="text-muted-foreground h-24 text-center">
                  {items.length === 0 ? `Belum ada ${schema.singular}.` : 'Tidak ada yang cocok.'}
                </TableCell>
              </TableRow>
            )}
            {filtered.map((row) => {
              const index = items.findIndex((x) => x.id === row.id)
              return (
                <TableRow key={row.id} className={row.is_published ? '' : 'text-muted-foreground'}>
                  <TableCell className="hidden sm:table-cell">
                    <div className="flex items-center gap-1">
                      <span className="text-muted-foreground w-5 text-xs tabular-nums">{index + 1}</span>
                      <Button variant="ghost" size="icon" className="size-7" disabled={!reorderable || index === 0} onClick={() => move(index, -1)} aria-label="Naikkan"><ArrowUp /></Button>
                      <Button variant="ghost" size="icon" className="size-7" disabled={!reorderable || index === items.length - 1} onClick={() => move(index, 1)} aria-label="Turunkan"><ArrowDown /></Button>
                    </div>
                  </TableCell>
                  <TableCell className="w-full max-w-0">
                    <div className="flex items-center gap-3">
                      {row.thumb && <img src={row.thumb} alt="" className="hidden h-10 w-14 shrink-0 rounded-md border object-cover sm:block" />}
                      <div className="min-w-0">
                        <Link href={`${base}/${row.id}/edit`} className="text-foreground block truncate font-medium hover:underline">{row.title}</Link>
                        {row.subtitle && <p className="text-muted-foreground truncate text-xs">{row.subtitle}</p>}
                      </div>
                    </div>
                  </TableCell>
                  <TableCell className="text-muted-foreground hidden text-sm whitespace-nowrap lg:table-cell">{row.meta ?? '—'}</TableCell>
                  <TableCell>
                    <div className="flex items-center gap-3">
                      <StatusBadge tone={row.is_published ? 'published' : 'draft'} className="hidden md:inline-flex">{row.is_published ? 'Tayang' : 'Draft'}</StatusBadge>
                      <Switch checked={row.is_published} onCheckedChange={() => router.patch(`${base}/${row.id}/toggle`, {}, { preserveScroll: true })} aria-label={`Tayangkan ${row.title}`} />
                    </div>
                  </TableCell>
                  <TableCell>
                    <DropdownMenu>
                      <DropdownMenuTrigger asChild>
                        <Button variant="ghost" size="icon" className="size-8" aria-label={`Aksi untuk ${row.title}`}><MoreHorizontal /></Button>
                      </DropdownMenuTrigger>
                      <DropdownMenuContent align="end">
                        <DropdownMenuItem asChild><Link href={`${base}/${row.id}/edit`}><Pencil /> Edit</Link></DropdownMenuItem>
                        <DropdownMenuSeparator />
                        <DropdownMenuItem variant="destructive" onSelect={() => setDeleting(row)}><Trash2 /> Hapus</DropdownMenuItem>
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
            <AlertDialogDescription>Item ini hilang dari landing page dan tidak bisa dikembalikan. Untuk menyembunyikan saja, matikan saklar Tayang.</AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel>Batal</AlertDialogCancel>
            <AlertDialogAction
              className="bg-destructive hover:bg-destructive/90 text-white"
              onClick={() => deleting && router.delete(`${base}/${deleting.id}`, { preserveScroll: true, onFinish: () => setDeleting(null) })}
            >
              Hapus
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>
    </AdminLayout>
  )
}
