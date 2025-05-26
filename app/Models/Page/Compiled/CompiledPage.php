<?php

namespace App\Models\Page\Compiled;

use App\Models\Page\Compiled\PageComponentInPage;
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

// A compiled page is a page that has been processed and is ready to be displayed.
// It contains the final content, including any components, templates, and other elements that make up the page.
// The compiled page is what users will see when they access the page in the application.
// It is generated from a GPT proces

class CompiledPage extends Model
{
    use SoftDeletes;

    // explicitly define tables
    protected $table = 'compiled_pages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'from_template_id',
        'team_id',
        'project_id',
        'label',
        'header',
        'footer',
        'css',
        'job_uuid',
        'released_at',
        'data',
        'title',
        'summary',
        'target_uuid'
    ];

    public function components():HasMany
    {
        return $this->hasMany(CompiledPageComponent::class, 'compiled_page_id', 'id')->orderBy('order', 'asc');
    }

    // // relationship between pages and page_pages
    // clipart tags
    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag', 'page_tags', 'page_id', 'tag_id')->withPivot('tag_id', 'page_id');
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

    public function team()
    {
        return $this->hasOne('App\Models\Team', 'team_id', 'id');
    }

    public function project()
    {
        return $this->hasOne('App\Models\Organisation\Project', 'project_id', 'id');
    }

}
