<?php

namespace App\Models\Page;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// PageComponentCategory is basically 'Collections' of page components, or 'snippets' of content

class SnippetsCollection extends Model
{
    use  SoftDeletes;
    //HasFactory,

    // Category for static components
    // explicitly define tables
    protected $table = 'snippets_collection';

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
        return $this->belongsToMany('App\Models\Page\Snippet', 'snippets_collection_snippets', 'snippet_collection_id', 'snippet_id')->withPivot('snippet_collection_id', 'snippet_id', 'order')->orderByPivot('order');
    }

    // user relationship


    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'snippets_collection_snippets_team_user', 'snippet_collection_id', 'user_id')->withPivot('user_id', 'snippet_collection_id');
    }

    // team relationship

    public function teams()
    {
        return $this->belongsToMany('App\Models\Team', 'snippets_collection_snippets_team_user', 'snippet_collection_id', 'team_id')->withPivot('team_id', 'snippet_collection_id');
    }

    public function projects()
    {
        return $this->belongsToMany('App\Models\Organisation\Project', 'snippets_collection_projects', 'snippet_collection_id', 'project_id')->withPivot('project_id', 'snippet_collection_id');
    }

    public function scopeIsInUsersProjects($query, $userId)
    {
        return $query->whereHas('projects', function (Builder $projectQ) use ($userId) {
            $projectQ->whereHas('users', function (Builder $userQ) use ($userId) {
                $userQ->where('users.id', $userId);
            });
        });
    }

    public function scopeIsInUsersTeams($query, $userId)
    {
        return $query->whereHas('teams', function (Builder $projectQ) use ($userId) {
            $projectQ->whereHas('users', function (Builder $userQ) use ($userId) {
                $userQ->where('users.id', $userId);
            });
        });
    }

    public function scopeIsAvailableToUser($query, $userId)
    {
        return $query->whereHas('projects', function (Builder $q) use ($userId) {
            $q->whereHas('users', function (Builder $q2) use ($userId) {
                $q2->where('users.id', $userId);
            });
        })->orWhereHas('teams', function (Builder $q) use ($userId) {
            $q->whereHas('users', function (Builder $q2) use ($userId) {
                $q2->where('users.id', $userId);
            });
        })
            ->orWhereDoesntHave('projects')
            ->orWhereDoesntHave('teams');
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
