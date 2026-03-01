<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Featured Works
    |--------------------------------------------------------------------------
    | Each work can have optional live_url (demo) and github_url (repository).
    | Set to null if no link is available yet.
    */

    'featured_works' => [
        [
            'title' => 'PT. Onexpert International - IT Support',
            'description' => 'Junior IT Support Freelancer ensuring computer functionality and application maintenance for company operations.',
            'tags' => ['IT Support', 'Computer Maintenance', 'Application Support'],
            'icon' => 'fas fa-laptop-code',
            'live_url' => null,
            'github_url' => null,
        ],
        [
            'title' => 'STT Terpadu Nurul Fikri - Teaching Assistant',
            'description' => 'Assistant Lecturer for multiple courses including English, Computer Network, DevOps, and Software Engineering.',
            'tags' => ['Teaching', 'Education', 'Mentoring'],
            'icon' => 'fas fa-graduation-cap',
            'live_url' => null,
            'github_url' => null,
        ],
        [
            'title' => 'MSIB Batch 6 - Codeless Data Science',
            'description' => 'Student at PT Nurul Fikri Cipta Inovasi learning data science and analytics without coding.',
            'tags' => ['Data Science', 'Analytics', 'Codeless Tools'],
            'icon' => 'fas fa-cloud',
            'live_url' => null,
            'github_url' => null,
        ],
        [
            'title' => 'MSIB Batch 5 - Web Development',
            'description' => 'Student at Infinite Learning mastering web development technologies and frameworks.',
            'tags' => ['Web Development', 'Frontend', 'Backend'],
            'icon' => 'fas fa-code',
            'live_url' => null,
            'github_url' => 'https://github.com/Muzaki29',
        ],
        [
            'title' => 'The Mayflower - Electrical Engineering',
            'description' => 'Trainee Engineering at Marriot Executive Apartements applying electrical equipment and K3 principles.',
            'tags' => ['Electrical', 'Installation', 'K3'],
            'icon' => 'fas fa-bolt',
            'live_url' => null,
            'github_url' => null,
        ],
        [
            'title' => 'Karang Taruna & Campus Organization',
            'description' => 'Public Relations and Human Resource roles in community and campus organizations.',
            'tags' => ['Public Relations', 'HR Management', 'Community'],
            'icon' => 'fas fa-users',
            'live_url' => null,
            'github_url' => null,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Certifications & Training
    |--------------------------------------------------------------------------
    | Each item can have an optional url (credential page or PDF). Use null if
    | no link is available. PDF links will show "Download" instead of "View credential".
    */

    'certifications' => [
        [
            'category' => 'NF Computer, STT Terpadu Nurul Fikri',
            'items' => [
                ['name' => 'Microsoft Office Professional', 'url' => null],
                ['name' => 'Graphic Design', 'url' => null],
                ['name' => 'Cloud & Web Instant', 'url' => null],
                ['name' => 'Video Editing & Youtube', 'url' => null],
            ],
        ],
        [
            'category' => 'Kampus Merdeka Programs',
            'items' => [
                ['name' => 'MSIB Batch 6 - Codeless Data Science', 'url' => null],
                ['name' => 'PT Nurul Fikri Cipta Inovasi', 'url' => null],
                ['name' => 'MSIB Batch 5 - Web Development', 'url' => null],
                ['name' => 'Infinite Learning', 'url' => null],
            ],
        ],
        [
            'category' => 'Other Experiences',
            'items' => [
                ['name' => 'Volunteer - Assistant Trainer Common Room', 'url' => null],
                ['name' => 'Basic Network Education', 'url' => null],
                ['name' => 'Digital Financial Literacy', 'url' => null],
                ['name' => 'Network Device Installation', 'url' => null],
            ],
        ],
    ],

];
