<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'title' => 'About',
                'slug' => 'about',
                'content' => '<p>About page. Update this content in admin.</p>',
                'is_published' => true,
            ],
            [
                'title' => 'Contact',
                'slug' => 'contact',
                'content' => '<p>Contact page. Update this content in admin.</p>',
                'is_published' => true,
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy',
                'content' => '<p>Privacy policy. Update this content in admin.</p>',
                'is_published' => true,
            ],
        ];

        foreach ($pages as $p) {
            Page::updateOrCreate(['slug' => $p['slug']], $p);
        }
    }
}
