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

class Page extends Model
{
    use HasFactory, SoftDeletes;


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
        'description',
        'content',
        'html',
        'css',
        'data',
        'job_uuid',
        'released_at',
        'template_type'
    ];

    // // relationship between pages and page_pages
  // clipart tags
  public function tags()
  {
      return $this->belongsToMany('App\Models\Tag', 'page_tags', 'tag_id', 'page_id')->withPivot('tag_id', 'page_id');
  }

    // Owner/creator of this page
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'page_team_user', 'user_id', 'page_id')->withPivot('user_id', 'page_id');
    }

    public function teams()
    {
        return $this->belongsToMany('App\Models\Team', 'page_team_user', 'team_id', 'page_id')->withPivot('team_id', 'page_id');
    }

    public function projects()
    {
        return $this->belongsToMany('App\Models\Organisation\Project', 'projects_pages', 'project_id', 'page_id')->withPivot('project_id', 'page_id');
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
