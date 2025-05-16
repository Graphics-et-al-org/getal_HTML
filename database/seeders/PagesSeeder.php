<?php

namespace Database\Seeders;

use App\Models\Page\Page;
use Illuminate\Database\Seeder;


class PagesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       Page::factory(10)->create();
    }

}
