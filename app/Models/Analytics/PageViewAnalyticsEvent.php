<?php

namespace App\Models\Analytics;




use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageViewAnalyticsEvent extends Model
{
    use SoftDeletes;

    // explicitly define tables
    protected $table = 'page_view_analytics_events';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'page_view_analytics_id',
        'event_type',
        'data',
    ];

    // User relationship
    public function page_view_analytic(): BelongsTo
    {
        return $this->belongsTo('App\Models\Analytics\PageViewAnalytics', 'page_view_analytics_id', 'id');
    }




}
