<?php

namespace App\Models\Analytics;




use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompiledSnippetEvent extends Model
{
   

    // explicitly define tables
    protected $table = 'snippets_use_tracker';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'snippet_id',
        'page_id',
        'weight',
        'action',
        'old_value',
        'new_value',
    ];





    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    // protected static function newFactory()
    // {
    //     return PageComponentFactory::new();
    // }
}
