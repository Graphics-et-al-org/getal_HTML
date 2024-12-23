<?php

namespace App\Jobs\AI;

use App\Traits\AblyFunctions;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class SubmitTextForTranslation implements ShouldQueue
{
    use Queueable, InteractsWithQueue, Queueable, SerializesModels, AblyFunctions;

    protected $_params;
    protected $_uuid;
    protected $_user_id;
    protected $_documentForSummary;
    protected $_template_id;

    /**
     * Create a new job instance.
     */
    public function __construct($params, $template_id, $uuid, $user_id)
    {

        $this->_params = $params;
        $this->_uuid = $uuid;
        $this->_template_id = $template_id;
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
        // simulate a long function
        //sleep(10);
        $this->sendMessage('translation-status.'. $this->_uuid, json_encode(['message'=>'success', 'uuid'=>$result_uuid]));
        return;
    }
}
