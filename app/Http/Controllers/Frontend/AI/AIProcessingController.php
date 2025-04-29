<?php

namespace App\Http\Controllers\Frontend\AI;

use App\Http\Controllers\Controller;
use App\Models\Clipart\Clipart;
use App\Models\Page\Compiled\CompiledPage;
use App\Models\Page\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIProcessingController extends Controller
{

    //Generate Keypoint Icon from text
    // This function will take the text and generate an icon
    // It will send the text to the AI service and get the icon back
    public function generate_keypoint_icon(Request $request)
    {
        $page = CompiledPage::where('uuid', $request->uuid)->first();
        // get the used icons for keypoints so we don't reuse
        $used_icons = [];
        foreach ($page->components as $component) {
            foreach ($component->snippets()->keypoints()->get() as $keypoint) {
                $used_icons[] = $keypoint->paired_image_id;
            }
        }

        //   dd($payload);
        $apiURL = env('AI_MAIN_FUNC_URL');

        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->post($apiURL, [
            'keypoint_text' =>  $request->keypoint_text,
            'used_images' => implode(',', $used_icons),
        ]);

        // dd($response);
        if ($response->successful()) {
            $response_content = $response->json();
            $colourway_uuid = ($response_content['best_image']>0)?Clipart::find($response_content['best_image'])->baseline()->uuid:'';
            $response_content['colourway_uuid'] = $colourway_uuid;
            $response_content['best_image'] = null;
        } else {

            // log teh failure, send feedback
            $response_content = array(
                "status" => "error",
                "message" => "Error in AI processing"
            );
            return response()->json($response_content);
        }
        return response()->json($response_content);
    }
}
