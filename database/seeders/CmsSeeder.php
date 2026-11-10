<?php

namespace Database\Seeders;

use App\Models\ContentItem;
use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * Imports experience, timeline, certificates and profile settings that used to be hardcoded
 * in portfolio.blade.php / lang files. Each section is seeded only while it is empty,
 * so re-running on deploy never overwrites admin edits.
 */
class CmsSeeder extends Seeder
{
    private function tr(string $key): array
    {
        return ['id' => __($key, [], 'id'), 'en' => __($key, [], 'en')];
    }

    private function same(string $text): array
    {
        return ['id' => $text, 'en' => $text];
    }

    public function run(): void
    {
        $this->seedSection('experience', [
            [
                'role' => $this->same('Associate Project Manager'),
                'company' => $this->same('PT. Darma Sarana Niaga'),
                'period' => ['id' => 'Sep 2026 – Sekarang', 'en' => 'Sep 2026 – Present'],
                'location' => ['id' => 'Jakarta Selatan · Purnawaktu · Hybrid', 'en' => 'South Jakarta · Full-time · Hybrid'],
                'description' => [
                    'id' => 'Mengelola proyek teknologi lintas tim — TI, AI, dan keuangan — agar tepat waktu dan sesuai anggaran.',
                    'en' => 'Managing cross-team tech projects — IT, AI, and finance — on time and on budget.',
                ],
                'highlights' => [
                    'id' => implode("\n", [
                        'Koordinasi tim teknis dengan manajemen & keuangan',
                        'Integrasi AI ke alur kerja internal',
                        'Jadwal, milestone, dan anggaran proyek',
                    ]),
                    'en' => implode("\n", [
                        'Bridging tech teams with management & finance',
                        'Integrating AI into internal workflows',
                        'Scheduling, milestones, and project budgets',
                    ]),
                ],
            ],
            [
                'role' => $this->same('Freelance Web Developer & QA Engineer'),
                'company' => ['id' => 'Freelance · Wiraswasta', 'en' => 'Freelance · Self-employed'],
                'period' => ['id' => 'Mei 2026 – Sekarang', 'en' => 'May 2026 – Present'],
                'location' => ['id' => 'Jakarta Raya · Jarak jauh', 'en' => 'Greater Jakarta · Remote'],
                'description' => [
                    'id' => 'Membangun dan menguji aplikasi web untuk klien.',
                    'en' => 'Building and testing web applications for clients.',
                ],
                'highlights' => [
                    'id' => implode("\n", [
                        'Laravel, React/Next.js, MySQL',
                        'API & functional testing sebelum rilis',
                    ]),
                    'en' => implode("\n", [
                        'Laravel, React/Next.js, MySQL',
                        'API & functional testing before release',
                    ]),
                ],
                'link_url' => 'https://github.com/Muzaki29',
            ],
            [
                'role' => $this->tr('experience.role_pranata'),
                'company' => $this->tr('experience.comp_pranata'),
                'period' => $this->tr('experience.date_pranata'),
                'description' => $this->tr('experience.desc_pranata_short'),
                'link_url' => 'https://github.com/Muzaki29/website-presensi-magang-muspen',
            ],
            [
                'role' => $this->tr('experience.role_onexpert'),
                'company' => $this->tr('experience.comp_onexpert'),
                'period' => $this->tr('experience.date_onexpert'),
                'description' => $this->tr('experience.desc_onexpert_short'),
                'link_url' => 'https://github.com/Muzaki29',
            ],
            [
                'role' => $this->tr('experience.role_lecturer_short'),
                'company' => $this->tr('experience.comp_lecturer'),
                'period' => $this->tr('experience.date_lecturer'),
                'description' => $this->tr('experience.desc_lecturer_short'),
                'cert_key' => 'asdos_devops',
            ],
        ]);

        $present = $this->tr('timeline.present');
        $this->seedSection('timeline', [
            ['title' => $this->same('Associate Project Manager — PT. Darma Sarana Niaga'), 'period' => ['id' => 'Sep 2026 – Sekarang', 'en' => 'Sep 2026 – Present']],
            ['title' => $this->same('Freelance Web Developer & QA Engineer'), 'period' => ['id' => 'Mei 2026 – Sekarang', 'en' => 'May 2026 – Present']],
            ['title' => $this->tr('timeline.item_pranata'), 'period' => $this->same('24 Nov 2025 – 24 Mei 2026')],
            ['title' => $this->tr('timeline.item_lecturer_short'), 'period' => $this->same('Sep 2023 – Jan 2025'), 'cert_key' => 'asdos_devops'],
            ['title' => $this->tr('timeline.item_codeless'), 'period' => $this->same('Feb 2024 – Jun 2024')],
            ['title' => $this->tr('timeline.item_webdev'), 'period' => $this->same('Aug 2023 – Dec 2023')],
            ['title' => $this->tr('timeline.item_onexpert'), 'period' => $this->same('Dec 2022 – Dec 2024')],
            ['title' => $this->tr('timeline.item_karangtaruna'), 'period' => ['id' => '2022 – '.$present['id'], 'en' => '2022 – '.$present['en']]],
            ['title' => $this->tr('timeline.item_trainee'), 'period' => $this->same('Oct 2019 – Mar 2020')],
            ['title' => $this->tr('timeline.item_informatics'), 'period' => $this->same('2021 – 2025')],
        ]);

        $img = fn (string $file, string $preview) => ['full' => "images/certificates/$file", 'preview' => "images/certificates/previews/$preview"];
        $this->seedSection('certificate', [
            [
                'key' => 'msib_batch_6',
                'badge' => $this->same('MSIB Batch 6 (2 Pages)'),
                'title' => $this->same('Sertifikat & Transkrip Codeless Data Science'),
                'issuer' => $this->same('PT Nurul Fikri Cipta Inovasi'),
                'date' => $this->same('Feb – Jun 2024'),
                'full_title' => $this->same('Sertifikat & Transkrip MSIB Batch 6 - Codeless Data Science'),
                'full_issuer' => $this->same('Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi x PT Nurul Fikri Cipta Inovasi'),
                'images' => [$img('msib_batch_6_page1.jpg', 'msib_batch_6_page1.webp'), $img('msib_batch_6_page2.jpg', 'msib_batch_6_page2.webp')],
            ],
            [
                'key' => 'msib_batch_5',
                'badge' => $this->same('MSIB Batch 5 (4 Pages)'),
                'title' => $this->same('Sertifikat & Transkrip Web Development'),
                'issuer' => $this->same('Infinite Learning'),
                'date' => $this->same('Aug – Dec 2023'),
                'full_title' => $this->same('Sertifikat & Transkrip MSIB Batch 5 - Web Development'),
                'full_issuer' => $this->same('Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi x Infinite Learning'),
                'images' => array_map(fn ($n) => $img("msib_batch_5_page$n.jpg", "msib_batch_5_page$n.webp"), [1, 2, 3, 4]),
            ],
            [
                'key' => 'magenta',
                'badge' => $this->same('Magenta BUMN'),
                'title' => $this->same('Magang Kerja BUMN'),
                'issuer' => $this->same('Kementerian BUMN & FHCI'),
                'date' => $this->same('2024'),
                'full_title' => $this->same('Sertifikat Magang Magenta BUMN'),
                'full_issuer' => $this->same('Kementerian BUMN / Forum Human Capital Indonesia (FHCI)'),
                'images' => [$img('magenta.png', 'magenta.webp')],
            ],
            [
                'key' => 'maganghub_batch_2',
                'badge' => $this->same('Maganghub'),
                'title' => $this->same('Maganghub Batch 2'),
                'issuer' => $this->same('Maganghub Indonesia'),
                'date' => $this->same('24 November 2025 – 24 Mei 2026'),
                'full_title' => $this->same('Sertifikat Maganghub Batch 2'),
                'full_issuer' => $this->same('Maganghub Indonesia'),
                'images' => [$img('maganghub_batch_2.png', 'maganghub_batch_2.webp')],
            ],
            [
                'key' => 'nets_2023',
                'badge' => $this->same('Seminar Nasional'),
                'title' => $this->same('Seminar Nasional NETS 2023'),
                'issuer' => $this->same('National Education & Technology'),
                'date' => $this->same('2023'),
                'full_title' => $this->same('Sertifikat Seminar Nasional (NETS) 2023'),
                'full_issuer' => $this->same('National Education and Technology Seminar'),
                'images' => [$img('nets_2023.jpg', 'nets_2023.webp')],
            ],
            [
                'key' => 'hmpsti',
                'badge' => $this->same('Organisasi'),
                'title' => $this->same('HMPSTI STT NF'),
                'issuer' => $this->same('Himpunan Mahasiswa TI STT NF'),
                'date' => $this->same('2023 – 2024'),
                'full_title' => $this->same('Sertifikat Pengurus HMPSTI STT NF'),
                'full_issuer' => $this->same('Himpunan Mahasiswa Program Studi Teknologi Informasi'),
                'images' => [$img('hmpsti.png', 'hmpsti.webp')],
            ],
            [
                'key' => 'asdos_devops',
                'badge' => $this->same('Asisten Dosen'),
                'title' => $this->same('Asisten Praktikum DevOps'),
                'issuer' => $this->same('STT Terpadu Nurul Fikri'),
                'date' => $this->same('Genap 2023/2024'),
                'full_title' => $this->same('Sertifikat Asisten Praktikum DevOps 2023/2024'),
                'full_issuer' => $this->same('STT Terpadu Nurul Fikri'),
                'images' => [$img('asdos_devops.png', 'asdos_devops.webp')],
            ],
        ]);

        if (! Setting::exists()) {
            foreach ([
                'hero_role' => $this->tr('hero.kicker'),
                'about_title' => $this->tr('about.title'),
                'about_description' => $this->tr('about.description'),
                'location' => 'Jakarta, Indonesia',
                'contact_email' => 'muzaki@syahdulab.biz.id',
                'github_username' => 'Muzaki29',
                'linkedin_url' => 'https://www.linkedin.com/in/muzaki-abdullah-irsyad-893a98220/',
                'linkedin_label' => 'muzaki-abdullah-irsyad',
                'dribbble_url' => 'https://dribbble.com/muzaki-abdullah-irsyad',
                'pinterest_url' => 'https://id.pinterest.com/syahdulab/',
                'pinterest_label' => 'SyahduLab',
                'instagram_username' => 'priasyahdu',
                'threads_username' => 'tuanpriasyahdu',
            ] as $key => $value) {
                Setting::create(['key' => $key, 'value' => $value]);
            }
            Setting::flush();
        }
    }

    private function seedSection(string $section, array $items): void
    {
        if (ContentItem::where('section', $section)->exists()) {
            return;
        }
        foreach ($items as $i => $data) {
            ContentItem::create(['section' => $section, 'sort_order' => $i, 'is_published' => true, 'data' => $data]);
        }
    }
}
