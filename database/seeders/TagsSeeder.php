<?php

namespace Database\Seeders;


use App\Models\Tag;
use Illuminate\Database\Seeder;


class TagsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       Tag::factory(10)->create();
    }
}
