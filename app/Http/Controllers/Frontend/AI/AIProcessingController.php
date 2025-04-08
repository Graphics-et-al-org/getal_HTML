<?php

namespace App\Http\Controllers\Frontend\AI;

use App\Http\Controllers\Controller;
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
        $pageData = json_decode(Page::where('uuid', $request->uuid)->first()->data);
        $used_icons = array_map(function ($item) {
            return $item->best_image;
        }, $pageData->paired_images);
       // dd($used_icons);

        //
        $payload = array("keypoint_text" =>  $request->keypoint_text, 'used_images' => implode(',',$used_icons));
     //   dd($payload);
        $apiURL = env('AI_MAIN_FUNC_URL');
      //  $url =  env('SVG_PROCESS_URL');
        // $response = Http::withHeaders([
        //     'Accept' => 'application/json',
        // ])->post($url, [
        //     'svg' => $this->_clipart->colourways->where('colour_id', '=', $baseline_colour_id)->first()->data,
        //     'name' => $this->_clipart->name,
        //     'description' => $this->_clipart->preferred_description,
        //     'tags' => $this->_clipart->tags->pluck('text')->join(','),
        // ]);
        // or else some real data
        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->post($apiURL, [
            'keypoint_text' =>  $request->keypoint_text,
            'used_images' => implode(',',$used_icons),
        ]);

       // dd($response);
        if ($response->successful()) {
            $response_content = $response->json();
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
