<?php

namespace Database\Factories;


use App\Models\Page\PageComponent;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Auth\User>
 */
class PageComponentFactory extends Factory
{

    protected $model = PageComponent::class;





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
            'uuid' => Str::uuid()->toString(),
            'content' => '<div class="flex w-full border border-blue-300 border-2 rounded-md mr-2 static-component"><div class="self-auto relative grid w-48 min-h-48 border border-solid border-2 border-gray-500 rounded-md"><div class="col-span-full m-0 m-0 p-2"><div class="h-32 w-full border border-solid border-2 border-red-500  rounded-md mb-2 text-center"><img class="object-contain w-full h-full" src="http://localhost:8000/colourway/85c4ab3d-80fe-4a92-bb0b-07cbe1018d3b" alt="Diagram of a burn" data-mce-src="../../../colourway/85c4ab3d-80fe-4a92-bb0b-07cbe1018d3b"></div><div class="min-h-12 w-full border border-solid border-2 border-red-500  rounded-md text-center ">Stay hydrated kiddo</div></div></div><div class="min-h-48 border border-solid border-2 border-gray-500 ml-2 rounded-md grow">General advice: Drink plenty of water</div></div>',
        ];
    }
}
