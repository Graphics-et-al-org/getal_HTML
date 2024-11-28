<?php

namespace Database\Factories;

use App\Models\Page\Page;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

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
        return [
            'uuid' => Uuid::uuid4()->toString(),
            'user_id'=>1,
            'group_id' => null,
            'is_template'=>(fake()->boolean()?1:null),
            'label' => fake()->sentence(2),
            'description' => fake()->text(),
        ];
    }
}
