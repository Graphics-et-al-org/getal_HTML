<?php

namespace App\Http\Controllers\Frontend\Pages\Analytics;


use App\Http\Controllers\Controller;
use App\Models\Analytics\PageViewAnalytics;
use App\Models\Analytics\PageViewAnalyticsEvent;
use App\Models\Page\Compiled\CompiledPage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CompiledPagesAnalyticsController extends Controller
{

    public function get_flush($uuid, Request $request)
    {

        // save the analytic
        $analytic = new PageViewAnalytics([
            'page_id' => CompiledPage::where('uuid', $uuid)->first()->id,
            'session_id' => $request->sessionid,
            'viewer_id' => $request->viewerId,
            'visit_number' => $request->visitNumber,
            'visit_id' => $request->visitId,
            'viewer_ip' => $request->ip(),
            'redirect' => ($request->redirect == 'yes'),
            'browser_name' => $request->browserName,
            'browser_version' => $request->browserVersion,
            'platform' => $request->platform,
            'page_load_time' => $request->pageLoadTime,
        ]);
        $analytic->save();

        // extract and save the events for this analytic
        foreach ($request->events as $event) {
            PageViewAnalyticsEvent::create(
                [
                    'page_view_analytics_id' => $analytic->id,
                    'event_type' => $event['name'],
                    // data is everything *except* name
                    'data'=>json_encode(array_diff_key($event, array_flip(["name"])))
                ]
            );
        }
        return response()->json(['status' => '0'], 200);
    }

    // Report on a page
    public function report(Request $request)
    {
        abort(501, 'Not implemented yet.');
    }
}
