<?php

namespace App\Jobs\AI;

use Illuminate\Support\Str;
use App\Traits\AblyFunctions;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use romanzipp\QueueMonitor\Traits\IsMonitored;

use function Illuminate\Log\log;

class SubmitTextForTranslation implements ShouldQueue
{
    use Queueable, InteractsWithQueue, Queueable, SerializesModels, AblyFunctions, IsMonitored;

    protected $_params;
    protected $_uuid;
    protected $_user_id;
    protected $_inputStr;
    protected $_simulated;
    protected $_template_id;

    //👇 Making the timeout larger
    public $timeout = 300;

    /**
     * Create a new job instance.
     */
    public function __construct($inputStr, $uuid, $user_id, $simulated)
    {

        $this->_inputStr = $inputStr;
        $this->_uuid = $uuid;
        //$this->_template_id = $template_id;
        $this->_user_id = $user_id;
        $this->_simulated = $simulated;
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
        $payload = array("doctor_text" => $this->_inputStr);
        $apiURL = env('AI_MAIN_FUNC_URL');

        if ($this->_simulated) {
            // some dummy data if simulated
            $response_content = array(
                'title' => 'Feeling Down and Tired Help',
                'summary' => 'You have been feeling low, tired, and unmotivated for about two weeks. This may be due to mild to moderate depression. We are starting treatment with medication and therapy to help you feel better. Follow these steps and let us know if things get worse.',
                'keypoints' =>
                array(
                    0 => 'Take your medication (escitalopram 10 mg) once daily as prescribed.',
                    1 => 'Start therapy (CBT) to talk about your feelings and get support.',
                    2 => 'Try to improve your sleep by going to bed and waking up at the same time.',
                    3 => 'If you can’t sleep, let us know. We may give you a low-dose sleep aid.',
                    4 => 'Come back in 2-3 weeks or sooner if you feel worse.',
                    5 => 'Tell us if you have side effects or changes in your mood.',
                ),
                'paired_images' =>
                array(
                    0 =>
                    array(
                        'keypoint' => 'Take your medication (escitalopram 10 mg) once daily as prescribed.',
                        'tags' => 'oral medication, daily use',
                        'best_image' => '18',
                        'similarity_score' => 999.0,
                    ),
                    1 =>
                    array(
                        'keypoint' => 'Start therapy (CBT) to talk about your feelings and get support.',
                        'tags' => 'therapy, mental health support',
                        'best_image' => '33',
                        'similarity_score' => 999.0,
                    ),
                    2 =>
                    array(
                        'keypoint' => 'Try to improve your sleep by going to bed and waking up at the same time.',
                        'tags' => 'sleep improvement, routine',
                        'best_image' => '298',
                        'similarity_score' => 999.0,
                    ),
                    3 =>
                    array(
                        'keypoint' => 'If you can’t sleep, let us know. We may give you a low-dose sleep aid.',
                        'tags' => 'sleep aid, report issues',
                        'best_image' => '1572',
                        'similarity_score' => 999.0,
                    ),
                    4 =>
                    array(
                        'keypoint' => 'Come back in 2-3 weeks or sooner if you feel worse.',
                        'tags' => 'follow-up, worsening symptoms',
                        'best_image' => '-1',
                        'similarity_score' => 999.0,
                    ),
                    5 =>
                    array(
                        'keypoint' => 'Tell us if you have side effects or changes in your mood.',
                        'tags' => 'side effects, mood changes',
                        'best_image' => '982',
                        'similarity_score' => 999.0,
                    ),
                ),
            );
        } else {
            // or else some real data
            $response = Http::withHeaders([
                'Accept' => 'application/json',
            ])->post($apiURL, $payload);
            if ($response->successful()) {
                $response_content = $response->json();
            } else {
                // log teh failure, send feedback
                $this->sendMessage('translation-status.' . $this->_uuid, json_encode(['message' => 'failure']));
                return;
            }
        }
        // build an output


        $this->sendMessage('translation-status.' . $this->_uuid, json_encode(['message' => 'success', 'uuid' => $result_uuid, 'content' => $response_content]));
        return;
    }
}
