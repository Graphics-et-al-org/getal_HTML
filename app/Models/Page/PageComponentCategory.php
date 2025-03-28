<?php

namespace App\Models\Page;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageComponentCategory extends Model
{
    use HasFactory;

    // Category for static components
    // explicitly define tables
    protected $table = 'page_component_category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'label',
        'description'
    ];

    public function components()
    {
        return $this->belongsToMany('App\Models\Page\PageComponent', 'page_component_category_components', 'page_component_category_id', 'page_component_id')->withPivot('page_component_category_id', 'page_component_id', 'order')->orderByPivot('order');
    }


    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return PageComponentCategory::new();
    }
}
