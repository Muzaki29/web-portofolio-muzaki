import { Head, router, useForm, usePage } from '@inertiajs/react'
import {
  AlertTriangle, Archive, ChevronLeft, ChevronRight, Download, Folder, Forward, HardDrive, Inbox, Loader2, Mail, MailOpen,
  Paperclip, PenSquare, RefreshCw, Reply, Search, Send, ShieldAlert, Star, Trash2, X,
} from 'lucide-react'
import { useEffect, useState, type FormEvent } from 'react'
import { toast } from 'sonner'

import { UnderlineTabs } from '@/components/underline-tabs'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Progress } from '@/components/ui/progress'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Skeleton } from '@/components/ui/skeleton'
import { Textarea } from '@/components/ui/textarea'
import { Tooltip, TooltipContent, TooltipTrigger } from '@/components/ui/tooltip'
import { cn } from '@/lib/utils'
import AdminLayout from '@/layouts/admin-layout'

type Address = { name: string | null; address: string }
type Attachment = { id: string; filename: string | null; contentType: string; sizeBytes: number; inline: boolean }
type Message = {
  uid: number; date: string; unseen: boolean; flags?: string[]; subject: string | null; from: Address | null
  to?: Address[]; cc?: Address[]; size: number; attachments?: Attachment[]
}
type FolderT = { path: string; name: string; specialUse: string | null; messageCount: number; unreadCount: number }
type Quota = { totalPercentage: number; supported: boolean; totalUsageFormatted?: string; totalLimitFormatted?: string }
type Props = {
  enabled: boolean
  error?: string
  mailboxes?: { resourceId: string; address: string }[]
  mailbox?: { resourceId: string; address: string }
  folders?: FolderT[]
  folder?: string
  q?: string
  filter?: 'all' | 'unread' | 'flagged'
  quota?: Quota | null
  messages?: { data: Message[]; pagination: { page: number; totalPages: number; total: number } }
}
type ComposeMode = 'new' | 'reply' | 'forward'

const folderLabel: Record<string, string> = { INBOX: 'Kotak masuk', Drafts: 'Draf', Junk: 'Spam', Sent: 'Terkirim', Trash: 'Sampah' }
const folderIcon = (f: FolderT) =>
  f.path === 'INBOX' ? Inbox : f.specialUse === '\\Sent' || f.path === 'Sent' ? Send : f.specialUse === '\\Trash' || f.path === 'Trash' ? Trash2 : f.specialUse === '\\Junk' || f.path === 'Junk' ? ShieldAlert : Folder
const fmtDate = (d: string) => {
  const date = new Date(d)
  return new Date().toDateString() === date.toDateString()
    ? date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
    : date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' })
}
const fmtSize = (n: number) => (n > 1048576 ? `${(n / 1048576).toFixed(1)} MB` : `${Math.max(1, Math.round(n / 1024))} KB`)
const who = (a?: Address | null) => (a ? (a.name ? `${a.name} <${a.address}>` : a.address) : '')
const isFlagged = (m: Message) => m.flags?.includes('\\Flagged') ?? false
const findFolder = (folders: FolderT[], key: 'Trash' | 'Junk') =>
  folders.find((f) => f.specialUse === `\\${key}` || f.path === key || f.name === key)?.path

function Setup({ error }: { error?: string }) {
  return (
    <Card className="max-w-2xl">
      <CardHeader>
        <CardTitle className="flex items-center gap-2">{error ? <AlertTriangle className="text-destructive size-5" /> : <Mail className="size-5" />}{error ? 'Tidak bisa terhubung ke Hostinger Mail' : 'Hubungkan Hostinger Mail'}</CardTitle>
        <CardDescription>{error ?? 'Kelola kotak masuk syahdulab.biz.id langsung dari admin.'}</CardDescription>
      </CardHeader>
      <CardContent>
        <ol className="text-muted-foreground list-decimal space-y-1.5 pl-5 text-sm">
          <li>hPanel → Email → Agentic Mail → <span className="text-foreground">Akses API → Buat token API</span>.</li>
          <li>Tambahkan ke <code className="bg-muted rounded px-1">.env</code>: <code className="bg-muted rounded px-1">HOSTINGER_MAIL_TOKEN=isi-token</code></li>
          <li>Jalankan <code className="bg-muted rounded px-1">php artisan config:clear</code>, lalu muat ulang halaman ini.</li>
        </ol>
      </CardContent>
    </Card>
  )
}

export default function MailIndex(props: Props) {
  const { enabled, error, mailboxes = [], mailbox, folders = [], folder = 'INBOX', q = '', filter = 'all', quota, messages } = props
  const { errors } = usePage<{ errors: Record<string, string> }>().props
  const [open, setOpen] = useState<Message | null>(null)
  const [body, setBody] = useState<{ loading: boolean; text?: string; error?: string }>({ loading: false })
  const [search, setSearch] = useState(q)
  const [compose, setCompose] = useState<ComposeMode | null>(null)
  const box = mailbox?.resourceId ?? ''
  const base = `/admin/mail/${encodeURIComponent(box)}`

  useEffect(() => { if (errors?.mail) toast.error(errors.mail) }, [errors?.mail])

  const go = (params: Record<string, string | number>) =>
    router.get('/admin/mail', { mailbox: box, folder, q, filter, page: 1, ...params }, { preserveScroll: true })

  const read = async (m: Message) => {
    setOpen(m)
    setBody({ loading: true })
    try {
      const res = await fetch(`${base}/messages/${m.uid}?folder=${encodeURIComponent(folder)}`, { headers: { Accept: 'application/json' } })
      const json = await res.json()
      setBody(res.ok ? { loading: false, text: json.text } : { loading: false, error: json.message ?? 'Gagal memuat pesan.' })
      if (res.ok && m.unseen) router.reload({ only: ['messages', 'folders'] })
    } catch {
      setBody({ loading: false, error: 'Gagal memuat pesan.' })
    }
  }

  const action = (url: string, data: Record<string, string | number | boolean>, after?: () => void) =>
    router.post(url, data, { preserveScroll: true, preserveState: true, onSuccess: () => after?.() })

  const toggle = (m: Message, key: 'seen' | 'flagged') =>
    action(`${base}/messages/${m.uid}/flags`, { folder, [key]: key === 'seen' ? m.unseen : !isFlagged(m) }, () =>
      setOpen((o) => (o && o.uid === m.uid ? { ...o, unseen: key === 'seen' ? !m.unseen : o.unseen, flags: key === 'flagged' ? (isFlagged(m) ? [] : ['\\Flagged']) : o.flags } : o)),
    )
  const move = (m: Message, target: string) => action(`${base}/messages/${m.uid}/move`, { folder, target }, () => setOpen(null))

  const trash = findFolder(folders, 'Trash')
  const junk = findFolder(folders, 'Junk')
  const inbox = folders.find((f) => f.path === 'INBOX')
  const page = messages?.pagination.page ?? 1
  const totalPages = Math.max(1, messages?.pagination.totalPages ?? 1)
  const list = messages?.data ?? []

  return (
    <AdminLayout
      title="Email"
      breadcrumbs={[{ label: 'Dashboard', href: '/admin' }, { label: 'Email' }]}
      actions={
        enabled && !error ? (
          <div className="flex flex-wrap items-center gap-2">
            {mailboxes.length > 1 && (
              <Select value={box} onValueChange={(v) => router.get('/admin/mail', { mailbox: v })}>
                <SelectTrigger size="sm" className="w-56"><SelectValue /></SelectTrigger>
                <SelectContent>{mailboxes.map((b) => <SelectItem key={b.resourceId} value={b.resourceId}>{b.address}</SelectItem>)}</SelectContent>
              </Select>
            )}
            <Button size="sm" variant="outline" onClick={() => router.post('/admin/mail/refresh', {}, { preserveScroll: true })}><RefreshCw /> Perbarui</Button>
            <Button size="sm" onClick={() => setCompose('new')}><PenSquare /> Tulis email</Button>
          </div>
        ) : undefined
      }
    >
      <Head title="Email" />
      {!enabled || error ? (
        <Setup error={error} />
      ) : (
        <>
          <div className="grid gap-3 sm:grid-cols-3">
            <Card className="flex-row items-center gap-4 p-4">
              <div className="bg-muted flex size-10 items-center justify-center rounded-md border"><Mail className="text-muted-foreground size-4" /></div>
              <div className="min-w-0"><p className="text-muted-foreground text-sm">Mailbox</p><p className="truncate font-semibold">{mailbox?.address}</p></div>
            </Card>
            <Card className="flex-row items-center gap-4 p-4">
              <div className="bg-muted flex size-10 items-center justify-center rounded-md border"><Inbox className="text-muted-foreground size-4" /></div>
              <div><p className="text-muted-foreground text-sm">Belum dibaca di kotak masuk</p><p className="text-xl font-semibold tabular-nums">{inbox?.unreadCount ?? 0}<span className="text-muted-foreground text-sm font-normal"> / {inbox?.messageCount ?? 0} pesan</span></p></div>
            </Card>
            <Card className="gap-2 p-4">
              <div className="flex items-center justify-between text-sm">
                <span className="text-muted-foreground flex items-center gap-2"><HardDrive className="size-4" />Penyimpanan</span>
                <span className="tabular-nums">{quota?.supported ? `${quota.totalUsageFormatted ?? '—'} / ${quota.totalLimitFormatted ?? '—'}` : '—'}</span>
              </div>
              <Progress value={quota?.supported ? Math.max(quota.totalPercentage, 1) : 0} className="h-2" />
              <p className="text-muted-foreground text-xs">{quota?.supported ? `${quota.totalPercentage}% terpakai` : 'Tidak dilaporkan server'}</p>
            </Card>
          </div>

          <Card className="gap-0 overflow-hidden py-0">
            {/* Toolbar: search + filter */}
            <div className="flex flex-wrap items-center gap-3 border-b p-3">
              <form onSubmit={(e: FormEvent) => { e.preventDefault(); go({ q: search }) }} className="relative min-w-56 flex-1">
                <Search className="text-muted-foreground pointer-events-none absolute top-1/2 left-2.5 size-4 -translate-y-1/2" />
                <Input value={search} onChange={(e) => setSearch(e.target.value)} placeholder="Cari subjek, pengirim, atau isi…" className="pr-8 pl-8" aria-label="Cari email" />
                {q && <button type="button" onClick={() => { setSearch(''); go({ q: '' }) }} className="text-muted-foreground hover:text-foreground absolute top-1/2 right-2 -translate-y-1/2" aria-label="Hapus pencarian"><X className="size-4" /></button>}
              </form>
              <UnderlineTabs
                label="Filter pesan"
                className="border-b-0"
                value={filter}
                onChange={(v) => go({ filter: v })}
                tabs={[{ value: 'all', label: 'Semua' }, { value: 'unread', label: 'Belum dibaca' }, { value: 'flagged', label: 'Berbintang' }]}
              />
            </div>

            <div className="grid min-h-[600px] lg:grid-cols-[210px_minmax(0,1fr)_minmax(0,1.15fr)]">
              {/* Folders */}
              <nav aria-label="Folder" className="flex gap-1 overflow-x-auto border-b p-2 lg:flex-col lg:border-r lg:border-b-0">
                {folders.map((f) => {
                  const Icon = folderIcon(f)
                  return (
                    <button key={f.path} type="button" onClick={() => { setOpen(null); go({ folder: f.path, q: '', filter: 'all' }) }} aria-current={f.path === folder}
                      className={cn('flex shrink-0 items-center gap-2 rounded-md px-3 py-2 text-left text-sm transition-colors', f.path === folder ? 'bg-accent font-medium' : 'text-muted-foreground hover:bg-accent/60 hover:text-foreground')}>
                      <Icon className="size-4 shrink-0" />
                      <span className="truncate">{folderLabel[f.name] ?? f.name}</span>
                      {f.unreadCount > 0 ? <Badge className="ml-auto h-5 px-1.5 tabular-nums">{f.unreadCount}</Badge> : <span className="text-muted-foreground ml-auto text-xs tabular-nums">{f.messageCount || ''}</span>}
                    </button>
                  )
                })}
              </nav>

              {/* Message list */}
              <div className="flex min-w-0 flex-col border-b lg:border-r lg:border-b-0">
                {q && <p className="text-muted-foreground border-b px-4 py-2 text-xs">Hasil untuk “{q}” di {folderLabel[folder] ?? folder}</p>}
                <ul className="flex-1 divide-y overflow-y-auto">
                  {list.length === 0 && (
                    <li className="text-muted-foreground flex flex-col items-center gap-2 p-10 text-center text-sm">
                      <Inbox className="size-6" />{q || filter !== 'all' ? 'Tidak ada pesan yang cocok.' : 'Folder ini kosong.'}
                    </li>
                  )}
                  {list.map((m) => (
                    <li key={m.uid} className="group relative">
                      <button type="button" onClick={() => read(m)} aria-current={open?.uid === m.uid}
                        className={cn('grid w-full gap-1 px-4 py-3 pr-10 text-left transition-colors hover:bg-accent/60', open?.uid === m.uid && 'bg-accent')}>
                        <span className="flex items-center gap-2">
                          {m.unseen && <span className="bg-primary size-2 shrink-0 rounded-full" aria-label="Belum dibaca" />}
                          <span className={cn('truncate text-sm', m.unseen ? 'font-semibold' : 'text-muted-foreground')}>{folder === 'Sent' ? `Ke: ${m.to?.[0]?.address ?? ''}` : m.from?.name || m.from?.address || '(tanpa pengirim)'}</span>
                          <span className="text-muted-foreground ml-auto shrink-0 text-xs tabular-nums">{fmtDate(m.date)}</span>
                        </span>
                        <span className={cn('flex items-center gap-1.5 truncate text-sm', m.unseen ? 'font-medium' : 'text-muted-foreground')}>
                          {(m.attachments?.filter((a) => !a.inline).length ?? 0) > 0 && <Paperclip className="size-3.5 shrink-0" />}
                          <span className="truncate">{m.subject || '(tanpa subjek)'}</span>
                        </span>
                      </button>
                      <button type="button" onClick={() => toggle(m, 'flagged')} aria-label={isFlagged(m) ? 'Hapus bintang' : 'Beri bintang'}
                        className={cn('absolute top-3 right-3 rounded p-0.5 transition-opacity', isFlagged(m) ? 'text-amber-500' : 'text-muted-foreground opacity-0 group-hover:opacity-100 focus-visible:opacity-100')}>
                        <Star className={cn('size-4', isFlagged(m) && 'fill-current')} />
                      </button>
                    </li>
                  ))}
                </ul>
                <div className="text-muted-foreground flex items-center justify-between border-t px-3 py-2 text-xs">
                  <span className="tabular-nums">{messages?.pagination.total ?? 0} pesan</span>
                  <div className="flex items-center gap-1">
                    <Button variant="ghost" size="icon" className="size-7" disabled={page <= 1} onClick={() => go({ page: page - 1 })} aria-label="Halaman sebelumnya"><ChevronLeft /></Button>
                    <span className="tabular-nums">{page} / {totalPages}</span>
                    <Button variant="ghost" size="icon" className="size-7" disabled={page >= totalPages} onClick={() => go({ page: page + 1 })} aria-label="Halaman berikutnya"><ChevronRight /></Button>
                  </div>
                </div>
              </div>

              {/* Reading pane */}
              <article className="flex min-w-0 flex-col" aria-live="polite">
                {!open ? (
                  <div className="text-muted-foreground flex flex-1 flex-col items-center justify-center gap-3 p-8 text-center text-sm">
                    <MailOpen className="size-7" />
                    Pilih pesan untuk membaca.
                    <Button size="sm" variant="outline" onClick={() => setCompose('new')}><PenSquare /> Tulis email baru</Button>
                  </div>
                ) : (
                  <>
                    <div className="flex flex-wrap items-center gap-1 border-b p-2">
                      <Btn label="Balas" onClick={() => setCompose('reply')}><Reply /></Btn>
                      <Btn label="Teruskan" onClick={() => setCompose('forward')}><Forward /></Btn>
                      <span className="bg-border mx-1 h-5 w-px" />
                      <Btn label={open.unseen ? 'Tandai dibaca' : 'Tandai belum dibaca'} onClick={() => toggle(open, 'seen')}>{open.unseen ? <MailOpen /> : <Mail />}</Btn>
                      <Btn label={isFlagged(open) ? 'Hapus bintang' : 'Beri bintang'} onClick={() => toggle(open, 'flagged')}><Star className={cn(isFlagged(open) && 'fill-amber-500 text-amber-500')} /></Btn>
                      <span className="bg-border mx-1 h-5 w-px" />
                      {folder !== 'INBOX' && <Btn label="Pindah ke kotak masuk" onClick={() => move(open, 'INBOX')}><Archive /></Btn>}
                      {junk && folder !== junk && <Btn label="Tandai spam" onClick={() => move(open, junk)}><ShieldAlert /></Btn>}
                      {trash && folder !== trash && <Btn label="Pindah ke sampah" onClick={() => move(open, trash)}><Trash2 /></Btn>}
                      <Button variant="ghost" size="icon" className="ml-auto size-8" onClick={() => setOpen(null)} aria-label="Tutup pesan"><X /></Button>
                    </div>
                    <div className="grid gap-4 overflow-y-auto p-5">
                      <div className="grid gap-1.5 border-b pb-4">
                        <h2 className="text-lg leading-snug font-semibold">{open.subject || '(tanpa subjek)'}</h2>
                        <dl className="text-muted-foreground grid grid-cols-[auto_1fr] gap-x-3 gap-y-0.5 text-sm">
                          <dt>Dari</dt><dd className="text-foreground truncate">{who(open.from)}</dd>
                          {(open.to?.length ?? 0) > 0 && <><dt>Kepada</dt><dd className="truncate">{open.to!.map(who).join(', ')}</dd></>}
                          {(open.cc?.length ?? 0) > 0 && <><dt>CC</dt><dd className="truncate">{open.cc!.map(who).join(', ')}</dd></>}
                          <dt>Tanggal</dt><dd>{new Date(open.date).toLocaleString('id-ID', { dateStyle: 'full', timeStyle: 'short' })}</dd>
                        </dl>
                      </div>
                      {body.loading && <div className="grid gap-2"><Skeleton className="h-4 w-3/4" /><Skeleton className="h-4 w-full" /><Skeleton className="h-4 w-5/6" /><Skeleton className="h-4 w-2/3" /></div>}
                      {body.error && <p className="text-destructive text-sm">{body.error}</p>}
                      {body.text !== undefined && <pre className="font-sans text-sm leading-relaxed break-words whitespace-pre-wrap">{body.text || '(pesan kosong)'}</pre>}
                      {(open.attachments?.filter((a) => !a.inline).length ?? 0) > 0 && (
                        <div className="grid gap-2 border-t pt-4">
                          <p className="text-sm font-medium">Lampiran</p>
                          <div className="flex flex-wrap gap-2">
                            {open.attachments!.filter((a) => !a.inline).map((a) => (
                              <a key={a.id} href={`${base}/messages/${open.uid}/attachments/${encodeURIComponent(a.id)}?folder=${encodeURIComponent(folder)}&name=${encodeURIComponent(a.filename ?? 'lampiran')}`}
                                className="hover:bg-accent flex items-center gap-2 rounded-md border px-3 py-2 text-sm transition-colors">
                                <Paperclip className="text-muted-foreground size-4" />
                                <span className="max-w-48 truncate">{a.filename ?? 'lampiran'}</span>
                                <span className="text-muted-foreground text-xs">{fmtSize(a.sizeBytes)}</span>
                                <Download className="text-muted-foreground size-3.5" />
                              </a>
                            ))}
                          </div>
                        </div>
                      )}
                      <div className="flex gap-2 border-t pt-4">
                        <Button size="sm" variant="outline" onClick={() => setCompose('reply')}><Reply /> Balas</Button>
                        <Button size="sm" variant="outline" onClick={() => setCompose('forward')}><Forward /> Teruskan</Button>
                      </div>
                    </div>
                  </>
                )}
              </article>
            </div>
          </Card>
          <p className="text-muted-foreground text-xs">Terhubung ke Hostinger Mail API · data diperbarui tiap 1 menit · pesan hanya dipindah ke Sampah, tidak dihapus permanen.</p>

          {compose && mailbox && (
            <Compose mode={compose} from={mailbox.address} action={`${base}/send`} source={compose === 'new' ? null : open} folder={folder} quoted={body.text} onClose={() => setCompose(null)} />
          )}
        </>
      )}
    </AdminLayout>
  )
}

function Btn({ label, onClick, children }: { label: string; onClick: () => void; children: React.ReactNode }) {
  return (
    <Tooltip>
      <TooltipTrigger asChild>
        <Button variant="ghost" size="icon" className="size-8" onClick={onClick} aria-label={label}>{children}</Button>
      </TooltipTrigger>
      <TooltipContent>{label}</TooltipContent>
    </Tooltip>
  )
}

function Compose({ mode, from, action, source, folder, quoted, onClose }: {
  mode: ComposeMode; from: string; action: string; source: Message | null; folder: string; quoted?: string; onClose: () => void
}) {
  const prefix = (s: string, p: string) => (s.toLowerCase().startsWith(p.toLowerCase()) ? s : `${p} ${s}`)
  const quote = quoted ? `\n\n\nPada ${source ? new Date(source.date).toLocaleString('id-ID') : ''}, ${who(source?.from)} menulis:\n${quoted.split('\n').map((l) => `> ${l}`).join('\n')}` : ''
  const form = useForm({
    to: mode === 'reply' ? source?.from?.address ?? '' : '',
    cc: '',
    subject: mode === 'reply' ? prefix(source?.subject ?? '', 'Re:') : mode === 'forward' ? prefix(source?.subject ?? '', 'Fwd:') : '',
    text: mode === 'new' ? '' : quote,
  })
  const errs = form.errors as Record<string, string>
  const split = (s: string) => s.split(/[,;\s]+/).map((x) => x.trim()).filter(Boolean)

  const submit = (e: FormEvent) => {
    e.preventDefault()
    form.transform((d) => ({
      to: split(d.to), cc: split(d.cc), subject: d.subject, text: d.text, mode,
      ...(mode !== 'new' && source ? { source: { uid: source.uid, folder } } : {}),
    }))
    form.post(action, { preserveScroll: true, onSuccess: () => onClose() })
  }
  const title = mode === 'reply' ? 'Balas email' : mode === 'forward' ? 'Teruskan email' : 'Tulis email baru'

  return (
    <Dialog open onOpenChange={(o) => !o && onClose()}>
      <DialogContent className="sm:max-w-2xl">
        <form onSubmit={submit} className="grid gap-4">
          <DialogHeader>
            <DialogTitle>{title}</DialogTitle>
            <DialogDescription>Dikirim dari <span className="text-foreground font-medium">{from}</span>{mode === 'forward' ? ' · lampiran asli ikut diteruskan' : ''}.</DialogDescription>
          </DialogHeader>
          <div className="grid gap-2">
            <Label htmlFor="mail-to">Kepada</Label>
            <Input id="mail-to" value={form.data.to} onChange={(e) => form.setData('to', e.target.value)} placeholder="nama@contoh.com, pisahkan dengan koma" autoFocus={mode !== 'reply'} aria-invalid={!!(errs.to || errs['to.0'])} />
            {(errs.to || errs['to.0']) && <p className="text-destructive text-sm">{errs.to || errs['to.0']}</p>}
          </div>
          <div className="grid gap-2">
            <Label htmlFor="mail-cc">CC <span className="text-muted-foreground font-normal">(opsional)</span></Label>
            <Input id="mail-cc" value={form.data.cc} onChange={(e) => form.setData('cc', e.target.value)} />
            {errs['cc.0'] && <p className="text-destructive text-sm">{errs['cc.0']}</p>}
          </div>
          <div className="grid gap-2">
            <Label htmlFor="mail-subject">Subjek</Label>
            <Input id="mail-subject" value={form.data.subject} onChange={(e) => form.setData('subject', e.target.value)} aria-invalid={!!errs.subject} />
            {errs.subject && <p className="text-destructive text-sm">{errs.subject}</p>}
          </div>
          <div className="grid gap-2">
            <Label htmlFor="mail-text">Pesan</Label>
            <Textarea id="mail-text" rows={10} value={form.data.text} onChange={(e) => form.setData('text', e.target.value)} autoFocus={mode === 'reply'} aria-invalid={!!errs.text} />
            {errs.text && <p className="text-destructive text-sm">{errs.text}</p>}
          </div>
          <DialogFooter>
            <Button type="button" variant="outline" onClick={onClose}>Batal</Button>
            <Button type="submit" disabled={form.processing}>{form.processing ? <Loader2 className="animate-spin" /> : <Send />} Kirim</Button>
          </DialogFooter>
        </form>
      </DialogContent>
    </Dialog>
  )
}
