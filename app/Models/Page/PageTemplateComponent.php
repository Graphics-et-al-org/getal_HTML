<?php

namespace App\Models\Page;

use App\Models\Page\PagePage as PagePage;
use App\Models\User as User;
use Database\Factories\PageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

// This is the page templates components. We use
class PageTemplateComponent extends Model
{
    use  SoftDeletes;


    // explicitly define tables
    protected $table = 'page_template_components';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'label',
        'description',
        'type',
        'content'
    ];

      //tags
     public function tags()
     {
         return $this->belongsToMany('App\Models\Tag', 'page_template_components_tags', 'page_template_component_id', 'tag_id')->withPivot('tag_id', 'page_template_component_id');
     }

     // Owner/creator of this page
     public function owner(): BelongsTo
     {
         return $this->belongsTo(User::class);
     }

     public function users()
     {
         return $this->belongsToMany('App\Models\User', 'page_template_components_team_user', 'page_template_component_id', 'user_id')->withPivot('user_id', 'page_template_component_id');
     }

     public function teams()
     {
         return $this->belongsToMany('App\Models\Team', 'page_template_components_team_user', 'page_template_component_id', 'team_id')->withPivot('team_id', 'page_template_component_id');
     }

     public function projects()
     {
         return $this->belongsToMany('App\Models\Organisation\Project', 'page_template_components_projects', 'page_template_component_id', 'project_id')->withPivot('project_id', 'page_template_component_id');
     }


}
