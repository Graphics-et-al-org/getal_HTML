<?php

namespace App\Models\Page;


use App\Models\User as User;
use Database\Factories\PageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

// This is the page templates components. We use
class PageTemplateComponentTemplate extends Model
{
    use  SoftDeletes;


    // explicitly define tables
    protected $table = 'page_template_component_templates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'template_id',
        'page_template_component_id',
        'order'
    ];




}
