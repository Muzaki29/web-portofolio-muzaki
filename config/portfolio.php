<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Featured Works
    |--------------------------------------------------------------------------
    | Uses translation keys (title_key, description_key, tags_key) for locale.
    | tags_key is comma-separated in lang files.
    */

    'featured_works' => [
        [
            'title_key' => 'works.onexpert_title',
            'description_key' => 'works.onexpert_desc',
            'tags_key' => 'works.onexpert_tags',
            'icon' => 'fas fa-laptop-code',
            'live_url' => null,
            'github_url' => null,
        ],
        [
            'title_key' => 'works.teaching_title',
            'description_key' => 'works.teaching_desc',
            'tags_key' => 'works.teaching_tags',
            'icon' => 'fas fa-graduation-cap',
            'live_url' => null,
            'github_url' => null,
        ],
        [
            'title_key' => 'works.codeless_title',
            'description_key' => 'works.codeless_desc',
            'tags_key' => 'works.codeless_tags',
            'icon' => 'fas fa-cloud',
            'live_url' => null,
            'github_url' => null,
        ],
        [
            'title_key' => 'works.webdev_title',
            'description_key' => 'works.webdev_desc',
            'tags_key' => 'works.webdev_tags',
            'icon' => 'fas fa-code',
            'live_url' => null,
            'github_url' => 'https://github.com/Muzaki29',
        ],
        [
            'title_key' => 'works.mayflower_title',
            'description_key' => 'works.mayflower_desc',
            'tags_key' => 'works.mayflower_tags',
            'icon' => 'fas fa-bolt',
            'live_url' => null,
            'github_url' => null,
        ],
        [
            'title_key' => 'works.org_title',
            'description_key' => 'works.org_desc',
            'tags_key' => 'works.org_tags',
            'icon' => 'fas fa-users',
            'live_url' => null,
            'github_url' => null,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Certifications & Training
    |--------------------------------------------------------------------------
    | category_key and item name_key for translation. url stays as-is.
    */

    /*
    |--------------------------------------------------------------------------
    | Works Section — Project Cards & Modals
    |--------------------------------------------------------------------------
    */

    'works_projects' => [
        [
            'gradient' => 'pc-g1',
            'image' => 'images/project_merchband.png',
            'placeholder_icon' => 'fas fa-shirt',
            'placeholder_label' => 'PMO Merch',
            'title_key' => 'works.merchband_title',
            'short_key' => 'works.merchband_short',
            'tagline_key' => 'works.merchband_tagline',
            'desc_key' => 'works.merchband_desc',
            'bullets' => ['works.merchband_b1', 'works.merchband_b2', 'works.merchband_b3'],
            'tags' => [
                ['icon' => 'fab fa-laravel', 'label' => 'Laravel 12'],
                ['icon' => 'fas fa-cube', 'label' => 'Three.js'],
                ['icon' => 'fas fa-store', 'label' => 'Filament 5'],
                ['icon' => 'fas fa-qrcode', 'label' => 'Midtrans QRIS'],
                ['icon' => 'fas fa-wind', 'label' => 'Tailwind CSS 4'],
            ],
            'github_url' => 'https://github.com/Muzaki29/web-merchband',
            'live_url' => null,
            'link_type' => 'github',
        ],
        [
            'gradient' => 'pc-g5',
            'image' => 'images/project_syahdulab.png',
            'placeholder_icon' => 'fas fa-palette',
            'placeholder_label' => 'SyahduLab',
            'title_key' => 'works.syahdulab_title',
            'short_key' => 'works.syahdulab_short',
            'tagline_key' => 'works.syahdulab_tagline',
            'desc_key' => 'works.syahdulab_desc',
            'bullets' => ['works.syahdulab_b1', 'works.syahdulab_b2', 'works.syahdulab_b3'],
            'tags' => [
                ['icon' => 'fab fa-react', 'label' => 'Next.js 14'],
                ['icon' => 'fab fa-js', 'label' => 'TypeScript'],
                ['icon' => 'fas fa-database', 'label' => 'Supabase'],
                ['icon' => 'fas fa-wind', 'label' => 'Tailwind CSS'],
                ['icon' => 'fas fa-magic', 'label' => 'Framer Motion'],
            ],
            'github_url' => 'https://github.com/Muzaki29/web-syahdulab',
            'live_url' => 'https://syahdulab.dev',
            'link_type' => 'live',
        ],
        [
            'gradient' => 'pc-g2',
            'image' => 'images/project_mes_depok.png',
            'placeholder_icon' => 'fas fa-mosque',
            'placeholder_label' => 'MES Depok',
            'title_key' => 'works.mesdepok_title',
            'short_key' => 'works.mesdepok_short',
            'tagline_key' => 'works.mesdepok_tagline',
            'desc_key' => 'works.mesdepok_desc',
            'bullets' => ['works.mesdepok_b1', 'works.mesdepok_b2', 'works.mesdepok_b3'],
            'tags' => [
                ['icon' => 'fab fa-laravel', 'label' => 'Laravel 12'],
                ['icon' => 'fas fa-bolt', 'label' => 'Livewire 4'],
                ['icon' => 'fab fa-google', 'label' => 'Socialite'],
                ['icon' => 'fas fa-qrcode', 'label' => 'Midtrans'],
                ['icon' => 'fas fa-wind', 'label' => 'Tailwind CSS 4'],
            ],
            'github_url' => 'https://github.com/Muzaki29/web-mes-depok',
            'live_url' => null,
            'link_type' => 'github',
        ],
        [
            'gradient' => 'pc-g3',
            'image' => 'images/project_jagadproperty.png',
            'placeholder_icon' => 'fas fa-home',
            'placeholder_label' => 'Jagad Property',
            'title_key' => 'works.jagad_title',
            'short_key' => 'works.jagad_short',
            'tagline_key' => 'works.jagad_tagline',
            'desc_key' => 'works.jagad_desc',
            'bullets' => ['works.jagad_b1', 'works.jagad_b2', 'works.jagad_b3'],
            'tags' => [
                ['icon' => 'fab fa-laravel', 'label' => 'Laravel 12'],
                ['icon' => 'fas fa-wind', 'label' => 'Tailwind CSS 4'],
                ['icon' => 'fas fa-database', 'label' => 'SQLite/MySQL'],
                ['icon' => 'fab fa-whatsapp', 'label' => 'WhatsApp'],
                ['icon' => 'fas fa-map-marker-alt', 'label' => 'Google Maps'],
            ],
            'github_url' => 'https://github.com/Muzaki29/web-properti',
            'live_url' => null,
            'link_type' => 'github',
        ],
        [
            'gradient' => 'pc-g4',
            'image' => 'images/project_cuci_motor.png',
            'placeholder_icon' => 'fas fa-motorcycle',
            'placeholder_label' => 'Cumo',
            'title_key' => 'works.cumo_title',
            'short_key' => 'works.cumo_short',
            'tagline_key' => 'works.cumo_tagline',
            'desc_key' => 'works.cumo_desc',
            'bullets' => ['works.cumo_b1', 'works.cumo_b2', 'works.cumo_b3'],
            'tags' => [
                ['icon' => 'fab fa-laravel', 'label' => 'Laravel 12'],
                ['icon' => 'fas fa-wind', 'label' => 'Tailwind CSS 4'],
                ['icon' => 'fas fa-calendar-check', 'label' => 'Online Booking'],
                ['icon' => 'fas fa-search', 'label' => 'Order Tracking'],
                ['icon' => 'fas fa-database', 'label' => 'SQLite'],
            ],
            'github_url' => null,
            'live_url' => null,
            'link_type' => 'none',
        ],
        [
            'gradient' => 'pc-g6',
            'image' => 'images/project_surat_rt.png',
            'placeholder_icon' => 'fas fa-file-alt',
            'placeholder_label' => 'SuratRT',
            'title_key' => 'works.suratrt_title',
            'short_key' => 'works.suratrt_short',
            'tagline_key' => 'works.suratrt_tagline',
            'desc_key' => 'works.suratrt_desc',
            'bullets' => ['works.suratrt_b1', 'works.suratrt_b2', 'works.suratrt_b3'],
            'tags' => [
                ['icon' => 'fab fa-react', 'label' => 'Next.js 16'],
                ['icon' => 'fas fa-database', 'label' => 'Prisma'],
                ['icon' => 'fas fa-shield-alt', 'label' => 'NextAuth v5'],
                ['icon' => 'fas fa-file-pdf', 'label' => 'pdf-lib'],
                ['icon' => 'fas fa-eye', 'label' => 'Tesseract OCR'],
            ],
            'github_url' => 'https://github.com/Muzaki29/website-surat-rt',
            'live_url' => null,
            'link_type' => 'github',
        ],
        [
            'gradient' => 'pc-g7',
            'image' => 'images/project_website_sekolah.png',
            'placeholder_icon' => 'fas fa-school',
            'placeholder_label' => 'SDN 08',
            'title_key' => 'works.sekolah_title',
            'short_key' => 'works.sekolah_short',
            'tagline_key' => 'works.sekolah_tagline',
            'desc_key' => 'works.sekolah_desc',
            'bullets' => ['works.sekolah_b1', 'works.sekolah_b2', 'works.sekolah_b3'],
            'tags' => [
                ['icon' => 'fab fa-laravel', 'label' => 'Laravel 13'],
                ['icon' => 'fab fa-vuejs', 'label' => 'Vue 3 SPA'],
                ['icon' => 'fas fa-wind', 'label' => 'Tailwind CSS 4'],
                ['icon' => 'fas fa-database', 'label' => 'SQLite/MySQL'],
                ['icon' => 'fas fa-cogs', 'label' => 'CMS Admin'],
            ],
            'github_url' => 'https://github.com/Muzaki29/website-sekolah',
            'live_url' => null,
            'link_type' => 'github',
        ],
        [
            'gradient' => 'pc-g8',
            'image' => 'images/project_presensi_magang.png',
            'placeholder_icon' => 'fas fa-user-check',
            'placeholder_label' => 'SINAR',
            'title_key' => 'works.sinar_title',
            'short_key' => 'works.sinar_short',
            'tagline_key' => 'works.sinar_tagline',
            'desc_key' => 'works.sinar_desc',
            'bullets' => ['works.sinar_b1', 'works.sinar_b2', 'works.sinar_b3'],
            'tags' => [
                ['icon' => 'fab fa-laravel', 'label' => 'Laravel'],
                ['icon' => 'fas fa-database', 'label' => 'SQLite'],
                ['icon' => 'fas fa-key', 'label' => 'Passkeys'],
                ['icon' => 'fab fa-bootstrap', 'label' => 'Bootstrap 5'],
                ['icon' => 'fas fa-map-marker-alt', 'label' => 'Haversine'],
            ],
            'github_url' => 'https://github.com/Muzaki29/website-presensi-magang-muspen',
            'live_url' => null,
            'link_type' => 'github',
        ],
        [
            'gradient' => 'pc-g9',
            'image' => 'images/project_pengelolaan_koleksi.png',
            'placeholder_icon' => 'fas fa-cubes',
            'placeholder_label' => 'Koleksi',
            'title_key' => 'works.koleksi_title',
            'short_key' => 'works.koleksi_short',
            'tagline_key' => 'works.koleksi_tagline',
            'desc_key' => 'works.koleksi_desc',
            'bullets' => ['works.koleksi_b1', 'works.koleksi_b2', 'works.koleksi_b3'],
            'tags' => [
                ['icon' => 'fab fa-laravel', 'label' => 'Laravel 11'],
                ['icon' => 'fas fa-wind', 'label' => 'Tailwind CSS'],
                ['icon' => 'fas fa-chart-line', 'label' => 'Chart.js'],
                ['icon' => 'fas fa-search', 'label' => 'Fuzzy Search'],
                ['icon' => 'fas fa-microphone', 'label' => 'Speech API'],
            ],
            'github_url' => 'https://github.com/Muzaki29/web-pengelolahan-koleksi',
            'live_url' => null,
            'link_type' => 'github',
        ],
        [
            'gradient' => 'pc-g10',
            'image' => 'images/project_museum_penerangan.png',
            'placeholder_icon' => 'fas fa-museum',
            'placeholder_label' => 'Portal Resmi',
            'title_key' => 'works.muspen_title',
            'short_key' => 'works.muspen_short',
            'tagline_key' => 'works.muspen_tagline',
            'desc_key' => 'works.muspen_desc',
            'bullets' => ['works.muspen_b1', 'works.muspen_b2', 'works.muspen_b3'],
            'tags' => [
                ['icon' => 'fas fa-globe', 'label' => 'Official Portal'],
                ['icon' => 'fas fa-vr-cardboard', 'label' => 'Virtual Tour 360°'],
                ['icon' => 'fas fa-calendar-check', 'label' => 'Online Booking'],
                ['icon' => 'fas fa-university', 'label' => 'Komdigi'],
            ],
            'github_url' => null,
            'live_url' => 'https://muspen.komdigi.go.id/',
            'link_type' => 'live',
            'footer_label_key' => 'works.official_site',
        ],
    ],

    'certifications' => [
        [
            'category_key' => 'certs.nf_category',
            'items' => [
                ['name_key' => 'certs.nf_item_1', 'url' => null],
                ['name_key' => 'certs.nf_item_2', 'url' => null],
                ['name_key' => 'certs.nf_item_3', 'url' => null],
                ['name_key' => 'certs.nf_item_4', 'url' => null],
            ],
        ],
        [
            'category_key' => 'certs.kampus_category',
            'items' => [
                ['name_key' => 'certs.kampus_item_1', 'url' => null],
                ['name_key' => 'certs.kampus_item_2', 'url' => null],
                ['name_key' => 'certs.kampus_item_3', 'url' => null],
                ['name_key' => 'certs.kampus_item_4', 'url' => null],
            ],
        ],
        [
            'category_key' => 'certs.other_category',
            'items' => [
                ['name_key' => 'certs.other_item_1', 'url' => null],
                ['name_key' => 'certs.other_item_2', 'url' => null],
                ['name_key' => 'certs.other_item_3', 'url' => null],
                ['name_key' => 'certs.other_item_4', 'url' => null],
            ],
        ],
    ],

];
