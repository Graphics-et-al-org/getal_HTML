<?php

namespace Database\Factories;

use App\Models\Page\Page;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Auth\User>
 */
class PageFactory extends Factory
{

    protected $model = Page::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->words(3, true);
        $content = fake()->sentence();

        return [
            'uuid' => Str::uuid(),
            'user_id' => 1,
            'group_id' => null,
            'is_template' => (fake()->boolean() ? 1 : null),
            'label' => fake()->sentence(2),
            'description' => fake()->text(),
            'content' => $content,
            'html' => "<h1>$title</h1><p>$content</p>"];
    }
}
