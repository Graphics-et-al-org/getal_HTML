<?php

namespace App\Models\Organisation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
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

        'label',
        'description',

    ];


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
