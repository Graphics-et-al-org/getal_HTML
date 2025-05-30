<?php

namespace App\Models\Media;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{

    use SoftDeletes;

    // explicitly define tables
    protected $table = 'media';

    protected $fillable = [

        'uuid',
        'name',
        'description',
        'type',
        'path',
        'location',
        'size',
        'thumb',
        'created_by',
    ];

    //

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'media_team_user', 'media_id', 'user_id')->withPivot('user_id', 'media_id');
    }

    // team relationship

    public function teams()
    {
        return $this->belongsToMany('App\Models\Team', 'media_team_user', 'media_id', 'team_id')->withPivot('team_id', 'media_id');
    }

    // projects relationship
    public function projects()
    {
        return $this->belongsToMany('App\Models\Organisation\Project', 'media_team_user', 'media_id', 'project_id')->withPivot('project_id', 'media_id');
    }

    // // Media tags
    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag', 'media_tags', 'tag_id', 'media_id')->withPivot('tag_id', 'media_id');
    }

}
