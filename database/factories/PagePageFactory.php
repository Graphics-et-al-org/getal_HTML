<?php

namespace Database\Factories;

use App\Models\Page\PagePage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class PagePageFactory extends Factory
{

    protected $model = PagePage::class;





    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->words(3, true);
        $content =fake()->sentence();

        return [
            'uuid' => Uuid::uuid4()->toString(),
            'page_id' => 1,
            'content'=>json_encode([
                'gjs-html' => "<header class='header'>\n  <h1 class='header-title'>".$title."</h1>\n</header>\n<main class='main'>\n  <section class='section'>\n    <p class='text'>".$content."</p>\n  </section>\n</main>\n<footer class='footer'>\n  <p class='footer-text'>Footer Content</p>\n</footer>",
                'gjs-css' => ".header {\n  background-color: #f5f5f5;\n  text-align: center;\n  padding: 20px;\n}\n.header-title {\n  font-size: 24px;\n  color: #333;\n}\n.main {\n  padding: 20px;\n}\n.section {\n  border: 1px solid #ddd;\n  padding: 10px;\n  margin: 10px 0;\n}\n.text {\n  font-size: 16px;\n  color: #666;\n}\n.footer {\n  background-color: #333;\n  color: white;\n  text-align: center;\n  padding: 10px;\n}\n.footer-text {\n  font-size: 14px;\n}",
                'gjs-components' => json_encode([
                    [
                        'type' => 'header',
                        'classes' => ['header'],
                        'components' => [
                            [
                                'type' => 'text',
                                'classes' => ['header-title'],
                                'content' => $title
                            ]
                        ]
                    ],
                    [
                        'type' => 'main',
                        'classes' => ['main'],
                        'components' => [
                            [
                                'type' => 'section',
                                'classes' => ['section'],
                                'components' => [
                                    [
                                        'type' => 'text',
                                        'classes' => ['text'],
                                        'content' => $content
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        'type' => 'footer',
                        'classes' => ['footer'],
                        'components' => [
                            [
                                'type' => 'text',
                                'classes' => ['footer-text'],
                                'content' => 'Footer Content'
                            ]
                        ]
                    ]
                ]),
                'gjs-styles' => json_encode([
                    [
                        'selectors' => ['.header'],
                        'style' => [
                            'background-color' => '#f5f5f5',
                            'text-align' => 'center',
                            'padding' => '20px'
                        ]
                    ],
                    [
                        'selectors' => ['.header-title'],
                        'style' => [
                            'font-size' => '24px',
                            'color' => '#333'
                        ]
                    ],
                    [
                        'selectors' => ['.main'],
                        'style' => [
                            'padding' => '20px'
                        ]
                    ],
                    [
                        'selectors' => ['.section'],
                        'style' => [
                            'border' => '1px solid #ddd',
                            'padding' => '10px',
                            'margin' => '10px 0'
                        ]
                    ],
                    [
                        'selectors' => ['.text'],
                        'style' => [
                            'font-size' => '16px',
                            'color' => '#666'
                        ]
                    ],
                    [
                        'selectors' => ['.footer'],
                        'style' => [
                            'background-color' => '#333',
                            'color' => 'white',
                            'text-align' => 'center',
                            'padding' => '10px'
                        ]
                    ],
                    [
                        'selectors' => ['.footer-text'],
                        'style' => [
                            'font-size' => '14px'
                        ]
                    ]
                ])
            ])
        ];
    }
}
