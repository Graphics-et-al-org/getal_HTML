<?php

namespace App\Models\Page;

use App\Models\Page\PagePage as PagePage;
use App\Models\User;
use Database\Factories\PageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use HasFactory, SoftDeletes ;


    // explicitly define tables
    protected $table = 'page';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'group_id',
        'is_template',
        'label',
        'description'
    ];

    // relationship between pages and page_pages
    public function PagePages() : HasMany {
        return $this->hasMany(PagePage::class);
    }

     // relationship between pages and page_pages
     public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

          /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return PageFactory::new();
    }


}
