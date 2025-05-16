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
            'html' => '<div class="container w-full"><nav class="bg-gray-200 border-gray-500 px-4 px-6 py-2 h-16 w-full z-50 ">
 <div class="flex flex-wrap justify-between items-center">
 <div class="flex justify-start items-center text-4xl">Customise header in template</div>
 </div>
 </nav>
 <div class="w-full justify-items-center border border-solid border-2 my-16">
 <div class="text-3xl " data-field="title">&nbsp;{{title}}</div>
 </div>
 <div class="w-full justify-items-center border border-solid border-2">
 <div class="text-xl" data-field="summary">{{summary}}</div>
 </div>
 <div class="w-full justify-items-center border border-solid border-2">
 <div class="text-xl">&nbsp;Keypoints</div>
 <div class="w-full grid grid-flow-row-dense grid-cols-3 grid-rows-5 border border-solid border-2 border-red-500" data-field="keypoints-container">{{keypoints_container}}</div>
 <div class="w-full border border-solid border-2 border-red-500" data-field="components-container">{{components_container}}</div>
 <footer class="bg-white rounded-lg shadow-sm fixed bottom-0 left-0 ">
 <div class="w-full mx-auto max-w-screen-xl p-4 flex items-center justify-between"><span class="text-sm text-gray-500 text-center &gt;&copy; 2023 &lt;a class=">Graphics et al/Mediguides- This is a footer in the template</span></div>
 </footer></div>
 </div>'];
    }
}
