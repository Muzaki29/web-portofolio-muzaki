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
