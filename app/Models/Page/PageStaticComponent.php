<?php

namespace App\Models\Page;

use App\Models\Team;
use App\Models\User;
use Database\Factories\PageStaticComponentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageStaticComponent extends Model
{
    use SoftDeletes, HasFactory;

    // explicitly define tables
    protected $table = 'page_static_components';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'team_id',
        'label',
        'description',
        'content',
        'thumb',
    ];

    // User relationship
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

     // team relationship
     public function team(): BelongsTo
     {
         return $this->belongsTo(Team::class);
     }

         // clipart tags
    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag', 'static_component_tags', 'tag_id', 'static_component_id')->withPivot('tag_id', 'static_component_id');
    }


    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return PageStaticComponentFactory::new();
    }
}
