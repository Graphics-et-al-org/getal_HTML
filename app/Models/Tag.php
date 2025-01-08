<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //
    use HasFactory;

    // explicitly define tables
    protected $table = 'tags';

    protected $fillable = [
        'text',
        'primary',
    ];

    public function scopeAlphaordered($query)
    {
        return $query->orderBy('text', 'asc');
    }

    public function scopeOnlyprimary($query)
    {
        return $query->whereNotNull('primary');
    }

    // clipart tags
    public function clipart()
    {
        return $this->belongsToMany('App\Models\Graphics\Clipart', 'clipart_tags', 'clipart_id', 'tag_id');
    }
}
