import { Link, router, usePage } from '@inertiajs/react'
import {
  BriefcaseBusiness,
  ExternalLink,
  FolderKanban,
  GraduationCap,
  LayoutDashboard,
  Mail,
  LogOut,
  Moon,
  Sun,
  UserRound,
  Award,
} from 'lucide-react'
import { useEffect, useState, type ReactNode } from 'react'
import { toast } from 'sonner'

import { LogoMark } from '@/components/logo'
import { Avatar, AvatarFallback } from '@/components/ui/avatar'
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbPage,
  BreadcrumbSeparator,
} from '@/components/ui/breadcrumb'
import { Button } from '@/components/ui/button'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { Separator } from '@/components/ui/separator'
import {
  Sidebar,
  SidebarContent,
  SidebarFooter,
  SidebarGroup,
  SidebarGroupLabel,
  SidebarHeader,
  SidebarInset,
  SidebarMenu,
  SidebarMenuBadge,
  SidebarMenuButton,
  SidebarMenuItem,
  SidebarProvider,
  SidebarRail,
  SidebarTrigger,
} from '@/components/ui/sidebar'
import { Toaster } from '@/components/ui/sonner'
import { TooltipProvider } from '@/components/ui/tooltip'
import type { SharedProps } from '@/types'

type Crumb = { label: string; href?: string }

const contentNav = [
  { label: 'Karya', href: '/admin/projects', icon: FolderKanban },
  { label: 'Pengalaman', href: '/admin/content/experience', icon: BriefcaseBusiness },
  { label: 'Timeline', href: '/admin/content/timeline', icon: GraduationCap },
  { label: 'Sertifikat', href: '/admin/content/certificate', icon: Award },
  { label: 'Profil & Kontak', href: '/admin/settings', icon: UserRound },
  { label: 'Email', href: '/admin/mail', icon: Mail },
] as { label: string; href: string | null; icon: typeof Award }[]

function useTheme() {
  const [dark, setDark] = useState(() => document.documentElement.classList.contains('dark'))
  useEffect(() => {
    document.documentElement.classList.toggle('dark', dark)
    try {
      localStorage.setItem('admin-theme', dark ? 'dark' : 'light')
    } catch {
      /* storage blocked: theme just won't persist */
    }
  }, [dark])
  return [dark, setDark] as const
}

export default function AdminLayout({ title, breadcrumbs = [], actions, tabs, children }: {
  title: string
  breadcrumbs?: Crumb[]
  actions?: ReactNode
  tabs?: ReactNode
  children: ReactNode
}) {
  const { auth, flash } = usePage<SharedProps>().props
  const { url } = usePage()
  const [dark, setDark] = useTheme()
  const initials = (auth.user?.name ?? 'A').split(/\s+/).filter((w) => /^\p{L}/u.test(w)).map((w) => w[0]).slice(0, 2).join('').toUpperCase()

  useEffect(() => {
    if (flash.success) toast.success(flash.success)
  }, [flash.success])

  return (
    <TooltipProvider>
      <SidebarProvider>
        <Sidebar collapsible="icon">
          <SidebarHeader>
            <SidebarMenu>
              <SidebarMenuItem>
                <SidebarMenuButton size="lg" asChild>
                  <Link href="/admin">
                    <div className="flex aspect-square size-8 items-center justify-center rounded-lg border bg-white">
                      <LogoMark className="text-primary h-4 w-auto" />
                    </div>
                    <div className="grid flex-1 text-left text-sm leading-tight">
                      <span className="truncate font-semibold">Muzaki Abdullah Irsyad</span>
                      <span className="text-muted-foreground truncate text-xs">muzakiabdullahirsyad.my.id</span>
                    </div>
                  </Link>
                </SidebarMenuButton>
              </SidebarMenuItem>
            </SidebarMenu>
          </SidebarHeader>

          <SidebarContent>
            <SidebarGroup>
              <SidebarMenu>
                <SidebarMenuItem>
                  <SidebarMenuButton asChild isActive={url === '/admin'} tooltip="Dashboard">
                    <Link href="/admin">
                      <LayoutDashboard />
                      <span>Dashboard</span>
                    </Link>
                  </SidebarMenuButton>
                </SidebarMenuItem>
              </SidebarMenu>
            </SidebarGroup>

            <SidebarGroup>
              <SidebarGroupLabel>Konten landing page</SidebarGroupLabel>
              <SidebarMenu>
                {contentNav.map((item) => (
                  <SidebarMenuItem key={item.label}>
                    {item.href ? (
                      <SidebarMenuButton asChild isActive={url.startsWith(item.href)} tooltip={item.label}>
                        <Link href={item.href}>
                          <item.icon />
                          <span>{item.label}</span>
                        </Link>
                      </SidebarMenuButton>
                    ) : (
                      <>
                        <SidebarMenuButton disabled tooltip={`${item.label} (segera)`}>
                          <item.icon />
                          <span>{item.label}</span>
                        </SidebarMenuButton>
                        <SidebarMenuBadge className="text-muted-foreground">Segera</SidebarMenuBadge>
                      </>
                    )}
                  </SidebarMenuItem>
                ))}
              </SidebarMenu>
            </SidebarGroup>

            <SidebarGroup className="mt-auto">
              <SidebarMenu>
                <SidebarMenuItem>
                  <SidebarMenuButton asChild tooltip="Lihat situs">
                    <a href="/" target="_blank" rel="noopener">
                      <ExternalLink />
                      <span>Lihat situs</span>
                    </a>
                  </SidebarMenuButton>
                </SidebarMenuItem>
              </SidebarMenu>
            </SidebarGroup>
          </SidebarContent>

          <SidebarFooter>
            <SidebarMenu>
              <SidebarMenuItem>
                <DropdownMenu>
                  <DropdownMenuTrigger asChild>
                    <SidebarMenuButton size="lg">
                      <Avatar className="size-8 rounded-lg">
                        <AvatarFallback className="rounded-lg">{initials}</AvatarFallback>
                      </Avatar>
                      <div className="grid flex-1 text-left text-sm leading-tight">
                        <span className="truncate font-medium">{auth.user?.name}</span>
                        <span className="text-muted-foreground truncate text-xs">{auth.user?.email}</span>
                      </div>
                    </SidebarMenuButton>
                  </DropdownMenuTrigger>
                  <DropdownMenuContent side="top" align="start" className="w-56">
                    <DropdownMenuLabel className="font-normal">{auth.user?.email}</DropdownMenuLabel>
                    <DropdownMenuSeparator />
                    <DropdownMenuItem onSelect={() => router.post('/admin/logout')}>
                      <LogOut /> Keluar
                    </DropdownMenuItem>
                  </DropdownMenuContent>
                </DropdownMenu>
              </SidebarMenuItem>
            </SidebarMenu>
          </SidebarFooter>
          <SidebarRail />
        </Sidebar>

        <SidebarInset>
          <header className="bg-background/95 sticky top-0 z-10 flex h-14 shrink-0 items-center gap-2 border-b px-4 backdrop-blur">
            <SidebarTrigger className="-ml-1" />
            <Separator orientation="vertical" className="mr-2 data-[orientation=vertical]:h-4" />
            <Breadcrumb>
              <BreadcrumbList>
                {breadcrumbs.map((c, i) => (
                  <span key={c.label} className="contents">
                    <BreadcrumbItem className={i < breadcrumbs.length - 1 ? 'hidden md:block' : ''}>
                      {c.href ? (
                        <BreadcrumbLink asChild>
                          <Link href={c.href}>{c.label}</Link>
                        </BreadcrumbLink>
                      ) : (
                        <BreadcrumbPage>{c.label}</BreadcrumbPage>
                      )}
                    </BreadcrumbItem>
                    {i < breadcrumbs.length - 1 && <BreadcrumbSeparator className="hidden md:block" />}
                  </span>
                ))}
              </BreadcrumbList>
            </Breadcrumb>
            <div className="ml-auto flex items-center gap-2">
              <Button variant="outline" size="icon" className="size-8" onClick={() => setDark(!dark)} aria-label="Ganti tema">
                {dark ? <Sun /> : <Moon />}
              </Button>
            </div>
          </header>

          {tabs}

          <div className="flex flex-1 flex-col gap-8 p-4 md:p-6">
            <div className="flex flex-wrap items-center justify-between gap-4">
              <h1 className="text-xl font-semibold tracking-tight">{title}</h1>
              {actions}
            </div>
            {children}
          </div>
        </SidebarInset>
        <Toaster position="top-right" theme={dark ? 'dark' : 'light'} />
      </SidebarProvider>
    </TooltipProvider>
  )
}
