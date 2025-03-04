<?php

namespace App\Models\Clipart;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clipart extends Model
{

    use SoftDeletes;

    // explicitly define tables
    protected $table = 'clipart';

    protected $fillable = [
        'owner_id', 'name', 'description', 'type', 'created_by', 'thumb'
    ];

    //
    public function colourways()
    {
        return $this->hasMany('App\Models\Clipart\ClipartColourway', 'clipart_id', 'id');
    }

    // clipart tags
    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag', 'clipart_tags', 'tag_id', 'clipart_id')->withPivot('tag_id', 'clipart_id');
    }

    // clipart owner
    public function owner()
    {
        return $this->hasOne('App\Models\User', 'id', 'owner_id');
    }

}
