<?php

namespace App\Models\Page;

use App\Models\Team;
use App\Models\User;
use Database\Factories\PageComponentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

// Page components, or 'snippets' of content to be included in a page

class Snippet extends Model
{
    use SoftDeletes, HasFactory;

    // explicitly define tables
    protected $table = 'snippets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'team_id',
        'keypoint',
        'label',
        'description',
        'content',
        'html',
        'css',
        'data',
        'approved_by',
        'approved_status',
        'approved_at',
        'approved_notes'
    ];

    // User relationship
    public function owner(): BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }


    public function collections()
    {
        return $this->belongsToMany('App\Models\Page\SnippetsCollection', 'snippets_collection_snippets', 'snippet_id', 'snippet_collection_id')->withPivot('snippet_collection_id', 'snippet_id', 'order');
    }

    // user relationship


    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'snippets_team_user', 'user_id', 'snippet_id')->withPivot('user_id', 'snippet_id');
    }

    // team relationship

    public function teams()
    {
        return $this->belongsToMany('App\Models\Team', 'snippets_team_user', 'snippet_id', 'team_id')->withPivot('team_id', 'snippet_id');
    }

    public function projects()
    {
        return $this->belongsToMany('App\Models\Organisation\Project', 'snippets_projects', 'snippet_id', 'project_id')->withPivot('project_id', 'snippet_id');
    }

    // clipart tags
    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag', 'snippets_tags', 'snippet_id', 'tag_id')->withPivot('tag_id', 'snippet_id');
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
