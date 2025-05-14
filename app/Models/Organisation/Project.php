<?php

namespace App\Models\Organisation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes, HasFactory;

    // explicitly define tables
    protected $table = 'projects';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [

        'label',
        'description',

    ];

    // user relationship


    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'projects_team_user', 'user_id', 'project_id')->withPivot('user_id', 'project_id');
    }

    // team relationship

    public function teams()
    {
        return $this->belongsToMany('App\Models\Team', 'projects_team_user', 'team_id', 'project_id')->withPivot('team_id', 'project_id');
    }

    // pages relationship

    public function page_templates()
    {
        return $this->belongsToMany('App\Models\Page\PageTemplate', 'projects_pages', 'page_id', 'project_id')->withPivot('page_id', 'project_id');
    }

    public function components()
    {
        return $this->belongsToMany('App\Models\Page\PageComponent', 'page_template_components_projects', 'page_template_component_id', 'project_id')->withPivot('page_template_component_id', 'project_id');
    }

    public function collections()
    {
        return $this->belongsToMany('App\Models\Page\SnippetsCollection', 'snippets_collection_projects', 'snippet_collection_id', 'project_id')->withPivot('snippet_collection_id', 'project_id');
    }

    // clipart tags
    // public function tags()
    // {
    //     return $this->belongsToMany('App\Models\Tag', 'static_component_tags', 'tag_id', 'static_component_id')->withPivot('tag_id', 'static_component_id');
    // }


    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    // protected static function newFactory()
    // {
    //     return PageComponentFactory::new();
    // }
}
