<?php

/*
| Admin CMS field schemas. Each section's fields drive both validation (ContentController)
| and the generic React form (pages/content/form.tsx).
|
| type: text | textarea | url | images
| translatable: value stored as {"id": "...", "en": "..."}
*/

return [
    // Project Library filters (solution type, not framework). Labels live in lang files: work.cat_<key>.
    'project_categories' => ['web', 'operational', 'ecommerce', 'education', 'public', 'creative3d', 'uiux'],

    'sections' => [
        'experience' => [
            'label' => 'Pengalaman',
            'singular' => 'pengalaman',
            'title_field' => 'role',
            'subtitle_field' => 'company',
            'fields' => [
                ['name' => 'role', 'label' => 'Peran', 'type' => 'text', 'translatable' => true, 'required' => true],
                ['name' => 'company', 'label' => 'Perusahaan / instansi', 'type' => 'text', 'translatable' => true],
                ['name' => 'period', 'label' => 'Periode', 'type' => 'text', 'translatable' => true, 'hint' => 'Contoh: 2022 – 2024'],
                ['name' => 'location', 'label' => 'Lokasi & tipe kerja', 'type' => 'text', 'translatable' => true, 'hint' => 'Contoh: Jakarta Selatan · Hybrid'],
                ['name' => 'description', 'label' => 'Deskripsi singkat', 'type' => 'textarea', 'translatable' => true],
                ['name' => 'highlights', 'label' => 'Poin pencapaian', 'type' => 'textarea', 'translatable' => true, 'hint' => 'Satu poin per baris. Tampil sebagai daftar di kartu pengalaman.'],
                ['name' => 'link_url', 'label' => 'Tautan "Detail Karya"', 'type' => 'url'],
                ['name' => 'cert_key', 'label' => 'Kunci sertifikat terkait', 'type' => 'text', 'hint' => 'Isi kunci sertifikat agar kartu ini membuka sertifikatnya. Kosongkan bila tidak ada.'],
            ],
        ],
        'timeline' => [
            'label' => 'Timeline',
            'singular' => 'item timeline',
            'title_field' => 'title',
            'subtitle_field' => 'period',
            'fields' => [
                ['name' => 'title', 'label' => 'Judul', 'type' => 'text', 'translatable' => true, 'required' => true],
                ['name' => 'period', 'label' => 'Periode', 'type' => 'text', 'translatable' => true, 'required' => true, 'hint' => 'Contoh: Feb 2024 – Jun 2024'],
                ['name' => 'cert_key', 'label' => 'Kunci sertifikat terkait', 'type' => 'text', 'hint' => 'Opsional. Klik item akan membuka sertifikat ini.'],
            ],
        ],
        'certificate' => [
            'label' => 'Sertifikat',
            'singular' => 'sertifikat',
            'title_field' => 'title',
            'subtitle_field' => 'issuer',
            'fields' => [
                ['name' => 'title', 'label' => 'Judul kartu', 'type' => 'text', 'translatable' => true, 'required' => true],
                ['name' => 'badge', 'label' => 'Label kecil', 'type' => 'text', 'translatable' => true, 'hint' => 'Contoh: MSIB Batch 6'],
                ['name' => 'issuer', 'label' => 'Penerbit', 'type' => 'text', 'translatable' => true],
                ['name' => 'date', 'label' => 'Tanggal / periode', 'type' => 'text', 'translatable' => true],
                ['name' => 'full_title', 'label' => 'Judul lengkap (saat diperbesar)', 'type' => 'text', 'translatable' => true],
                ['name' => 'full_issuer', 'label' => 'Penerbit lengkap (saat diperbesar)', 'type' => 'text', 'translatable' => true],
                ['name' => 'key', 'label' => 'Kunci sertifikat', 'type' => 'text', 'hint' => 'Huruf kecil tanpa spasi, mis. asdos_devops. Dipakai Pengalaman/Timeline untuk menautkan.'],
                ['name' => 'images', 'label' => 'Halaman sertifikat', 'type' => 'images', 'hint' => 'Lebih dari satu gambar menjadi slider.'],
            ],
        ],
    ],

    // Profil & Kontak settings form.
    'settings' => [
        ['name' => 'show_experience', 'label' => 'Tampilkan Pengalaman', 'type' => 'toggle', 'group' => 'Tampilan section', 'default' => true],
        ['name' => 'show_timeline', 'label' => 'Tampilkan Journey / Timeline', 'type' => 'toggle', 'group' => 'Tampilan section', 'default' => true],
        ['name' => 'show_process', 'label' => 'Tampilkan Proses Kerja', 'type' => 'toggle', 'group' => 'Tampilan section', 'default' => true],
        ['name' => 'show_certifications', 'label' => 'Tampilkan Sertifikat', 'type' => 'toggle', 'group' => 'Tampilan section', 'default' => false],
        ['name' => 'hero_role', 'label' => 'Peran di hero', 'type' => 'text', 'translatable' => true, 'group' => 'Profil', 'hint' => 'Teks kecil di atas nama, mis. Full Stack · Freelance · Content'],
        ['name' => 'about_title', 'label' => 'Judul Tentang', 'type' => 'text', 'translatable' => true, 'group' => 'Profil'],
        ['name' => 'about_description', 'label' => 'Paragraf Tentang', 'type' => 'textarea', 'translatable' => true, 'group' => 'Profil'],
        ['name' => 'location', 'label' => 'Lokasi', 'type' => 'text', 'group' => 'Profil'],
        ['name' => 'contact_email', 'label' => 'Email kontak', 'type' => 'email', 'group' => 'Kontak & sosial'],
        ['name' => 'github_username', 'label' => 'Username GitHub', 'type' => 'text', 'group' => 'Kontak & sosial'],
        ['name' => 'linkedin_url', 'label' => 'URL LinkedIn', 'type' => 'url', 'group' => 'Kontak & sosial'],
        ['name' => 'linkedin_label', 'label' => 'Teks tautan LinkedIn', 'type' => 'text', 'group' => 'Kontak & sosial'],
        ['name' => 'dribbble_url', 'label' => 'URL Dribbble', 'type' => 'url', 'group' => 'Karya & konten', 'hint' => 'Kosongkan untuk menyembunyikan.'],
        ['name' => 'pinterest_url', 'label' => 'URL Pinterest', 'type' => 'url', 'group' => 'Karya & konten'],
        ['name' => 'pinterest_label', 'label' => 'Nama Pinterest', 'type' => 'text', 'group' => 'Karya & konten'],
        ['name' => 'instagram_username', 'label' => 'Username Instagram (tanpa @)', 'type' => 'text', 'group' => 'Karya & konten'],
        ['name' => 'threads_username', 'label' => 'Username Threads (tanpa @)', 'type' => 'text', 'group' => 'Karya & konten'],
    ],
];
