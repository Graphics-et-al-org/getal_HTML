<?php

namespace App\Models\Page;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// PageComponentCategory is basically 'Collections' of page components, or 'snippets' of content

class SnippetsCategory extends Model
{
    use  SoftDeletes;
    //HasFactory,

    // Category for static components
    // explicitly define tables
    protected $table = 'snippets_category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'label',
        'description',
    ];

    public function snippets()
    {
        return $this->belongsToMany('App\Models\Page\Snippet', 'snippets_category_snippets', 'snippet_category_id', 'snippet_id')->withPivot('snippet_category_id', 'snippet_id', 'order')->orderByPivot('order');
    }

     // user relationship


     public function users()
     {
         return $this->belongsToMany('App\Models\User', 'snippets_category_snippets_team_user', 'snippet_category_id', 'user_id')->withPivot('user_id', 'snippet_category_id');
     }

     // team relationship

     public function teams()
     {
         return $this->belongsToMany('App\Models\Team', 'snippets_category_snippets_team_user', 'snippet_category_id', 'team_id')->withPivot('team_id', 'snippet_category_id');
     }

     public function projects()
     {
         return $this->belongsToMany('App\Models\Organisation\Project', 'snippets_category_projects', 'snippet_category_id', 'project_id')->withPivot('project_id', 'snippet_category_id');
     }


    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    // protected static function newFactory()
    // {
    //     return PageComponentCategory::new();
    // }
}
