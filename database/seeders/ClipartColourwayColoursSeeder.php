<?php

namespace Database\Seeders;

use App\Models\Clipart\ClipartColourwayColour;
use App\Models\Page\ClipartColourway;
use Illuminate\Database\Seeder;


class ClipartColourwayColoursSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        ClipartColourwayColour::factory()->create([
            'name' => 'baseline',
            'colour_code' => '0x000000',
        ]);
        ClipartColourwayColour::factory()->create([
            'name' => 'Outline',
            'colour_code' => '0xdcdcdc',
        ]);
        ClipartColourwayColour::factory()->create([
            'name' => 'Blue',
            'colour_code' => '0x0000cd',
        ]);
        ClipartColourwayColour::factory()->create([
            'name' => 'Green',
            'colour_code' => '0x008000',
        ]);
        ClipartColourwayColour::factory()->create([
            'name' => 'Grey',
            'colour_code' => '0x696969',
        ]);
        ClipartColourwayColour::factory()->create([
            'name' => 'Purple',
            'colour_code' => '0x800080',
        ]);
        ClipartColourwayColour::factory()->create([
            'name' => 'Red',
            'colour_code' => '0xff0000',
        ]);
        ClipartColourwayColour::factory()->create([
            'name' => 'Yellow',
            'colour_code' => '0xffd800',
        ]);
    }
}
