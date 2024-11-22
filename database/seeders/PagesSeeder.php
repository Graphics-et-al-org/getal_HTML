<?php

namespace Database\Seeders;

use App\Models\Page\Page;
use App\Models\Page\PagePage;
use Illuminate\Database\Seeder;


class PagesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       Page::factory(10)->create()->each(function ($page) {
        $page->pagepages()->saveMany(
            PagePage::factory(1)->make()
        );
    });
    }
}
