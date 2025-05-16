<?php

namespace App\Models\Analytics;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

// The analytics 'document'
class PageViewAnalytics extends Model
{
    use SoftDeletes;

    // explicitly define tables
    protected $table = 'page_view_analytics';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'page_id',
        'page_uuid',
        'session_id',
        'viewer_id',
        'visit_number',
        'visit_id',
        'viewer_ip',
        'redirect',
        'browser_name',
        'browser_version',
        'platform',
        'page_load_time'
    ];

    // relationship to events
    public function events(): HasMany
    {
        return $this->hasMany('App\Models\PageViewAnalyticsEvent', 'page_view_analytics_id', 'id');
    }





}
