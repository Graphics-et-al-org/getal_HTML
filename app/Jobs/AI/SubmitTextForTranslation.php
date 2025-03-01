<?php

namespace App\Jobs\AI;

use Illuminate\Support\Str;
use App\Traits\AblyFunctions;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use romanzipp\QueueMonitor\Traits\IsMonitored;

class SubmitTextForTranslation implements ShouldQueue
{
    use Queueable, InteractsWithQueue, Queueable, SerializesModels, AblyFunctions, IsMonitored;

    protected $_params;
    protected $_uuid;
    protected $_user_id;
    protected $_inputStr;
    protected $_template_id;

    //👇 Making the timeout larger
    public $timeout = 300;

    /**
     * Create a new job instance.
     */
    public function __construct($inputStr, $uuid, $user_id)
    {

        $this->_inputStr = $inputStr;
        $this->_uuid = $uuid;
        //$this->_template_id = $template_id;
        $this->_user_id = $user_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        // dd('handling');
        $result_uuid = Str::uuid()->toString();
        //
        $payload = array("extracted_text" => $this->_inputStr);
        $apiURL = env('AI_API_SESSION_URL');

        //$client = new \GuzzleHttp\Client(['verify' => false]);
        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->post($apiURL, $payload);

        $status = $response->getStatusCode();
        //
        //If status is 200, there's a useful response
        if ($response->successful()) {
            // get the results from the uuid
            $resultsURL =  env('AI_API_RESULT_URL');
            $response = Http::withHeaders([
                'Accept' => 'application/json',
            ])->post($resultsURL, $payload);

            $status = $response->getStatusCode();
        } else {
        }

        // simulate a long function
        //sleep(10);
        $this->sendMessage('translation-status.' . $this->_uuid, json_encode(['message' => 'success', 'uuid' => $result_uuid]));
        return;
    }
}
