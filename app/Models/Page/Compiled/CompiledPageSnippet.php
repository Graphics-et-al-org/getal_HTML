<?php

namespace App\Models\Page\Compiled;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

// Model to define the page componetts used in a compiled page

class CompiledPageSnippet extends Model
{
    use SoftDeletes;

    // explicitly define tables
    protected $table = 'compiled_page_snippets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // 'page_id',
        'uuid',
        'type',
        'order',
        'content',
        'compiled_page_components_id',
        'from_template_id',
        'paired_image_id'
    ];

    // Page relationship
    public function component(): HasOne
    {
        return $this->hasOne('App\Models\Page\Compiled\CompiledPageComponent', 'id', 'compiled_page_components_id');
    }

    public function from_template(): HasOne
    {
        return $this->hasOne('App\Models\Page\PageTemplateComponent', 'id', 'from_template_id');
    }


    public function scopeKeypoints($query){
        return $query->where('type', '=', 'keypoint');
    }

    public function scopeSnippets($query){
        return $query->where('type', '=', 'snippet');
    }


}
