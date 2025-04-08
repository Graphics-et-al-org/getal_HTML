<?php

namespace App\Models\Page;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageComponentCategory extends Model
{
    use HasFactory, SoftDeletes;

    // Category for static components
    // explicitly define tables
    protected $table = 'page_component_category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'label',
        'description',
        'uuid'
    ];

    public function components()
    {
        return $this->belongsToMany('App\Models\Page\PageComponent', 'page_component_category_components', 'page_component_category_id', 'page_component_id')->withPivot('page_component_category_id', 'page_component_id', 'order')->orderByPivot('order');
    }

     // user relationship


     public function users()
     {
         return $this->belongsToMany('App\Models\User', 'page_component_category_team_user', 'user_id', 'page_component_category_id')->withPivot('user_id', 'page_component_id');
     }

     // team relationship

     public function teams()
     {
         return $this->belongsToMany('App\Models\Team', 'page_component_category_team_user', 'team_id', 'page_component_category_id')->withPivot('team_id', 'page_component_category_id');
     }

     public function projects()
     {
         return $this->belongsToMany('App\Models\Organisation\Project', 'page_component_category_projects', 'project_id', 'page_component_category_id')->withPivot('project_id', 'page_component_category_id');
     }


    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return PageComponentCategory::new();
    }
}
