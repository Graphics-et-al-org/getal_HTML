<?php

namespace App\Models\Page;

use App\Models\Team;
use App\Models\User;
use Database\Factories\PageComponentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageComponent extends Model
{
    use SoftDeletes, HasFactory;

    // explicitly define tables
    protected $table = 'page_components';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'team_id',
        'category_id',
        'weight',
        'keypoint',
        'label',
        'description',
        'content',
        'thumb',
    ];

    // User relationship
    public function owner(): BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }


    public function categories()
    {
        return $this->belongsToMany('App\Models\Page\PageComponentCategory', 'page_component_category_components', 'page_component_id', 'page_component_category_id')->withPivot('page_component_category_id', 'page_component_id', 'order');
    }

    // user relationship


    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'page_component_team_user', 'user_id', 'page_component_id')->withPivot('user_id', 'page_component_id');
    }

    // team relationship

    public function teams()
    {
        return $this->belongsToMany('App\Models\Team', 'page_component_team_user', 'team_id', 'page_component_id')->withPivot('team_id', 'page_component_id');
    }

    public function projects()
    {
        return $this->belongsToMany('App\Models\Organisation\Project', 'page_component_projects', 'project_id', 'page_id')->withPivot('project_id', 'page_id');
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
        return PageComponentFactory::new();
    }
}
