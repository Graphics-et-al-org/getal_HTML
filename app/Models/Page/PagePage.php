<?php

namespace App\Models\Page;

use App\Models\Page\Page;
use Database\Factories\PagePageFactory;
use Database\Factories\PageFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PagePage extends Model
{
    use SoftDeletes, HasFactory;


    // explicitly define tables
    protected $table = 'page_page';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'page_id',
        'content',
    ];

    // relationship between pages and page_pages
    public function Page() : BelongsTo {
        return $this->BelongsTo(Page::class);
    }

      /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return PagePageFactory::new();
    }
}
