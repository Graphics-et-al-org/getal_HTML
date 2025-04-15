<?php

namespace App\Models\Page\Compiled;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

// Model to define the page componetts used in a compiled page

class CompiledPageComponent extends Model
{
    use SoftDeletes;

    // explicitly define tables
    protected $table = 'compiled_page_components';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'compiled_page_id',
        'from_page_template_components_id',
        'type',
        'order',
        'content',
    ];

    // Page relationship
    public function page(): BelongsTo
    {
        return $this->belongsTo('App\Models\Page\Compiled\CompiledPage', 'compiled_page_id', 'id');
    }

    // Page relationship
    public function snippets(): HasMany
    {
        return $this->HasMany('App\Models\Page\Compiled\CompiledPageSnippet', 'compiled_page_components_id', 'id')->orderBy('order', 'asc');
    }

    public function from_template(): BelongsTo
    {
        return $this->belongsTo('App\Models\Page\PageTemplateComponent', 'from_page_template_component_id', 'id');
    }

}
