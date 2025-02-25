<?php

namespace App\Jobs\AI;

use romanzipp\QueueMonitor\Traits\IsMonitored;
use App\Models\Clipart\ClipartColourwayColour;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Intervention\Image\ImageManager;


class SubmitSvgForProcesing implements ShouldQueue
{
    use Queueable, InteractsWithQueue, Queueable, SerializesModels, IsMonitored;

    protected $_clipart;

    /**
     * Create a new job instance.
     */
    public function __construct($clipart)
    {
        $this->_clipart = $clipart;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $baseline_colour_id = ClipartColourwayColour::where('name', '=', 'baseline')->first()->id;
        $url =  env('SVG_PROCESS_URL');
        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->post($url, [
            'svg' => $this->_clipart->colourways->where('colour_id', '=', $baseline_colour_id)->first()->data,
            'name' => $this->_clipart->name,
            'description' => $this->_clipart->preferred_description,
            'tags' => $this->_clipart->tags->pluck('text')->join(','),
        ]);
        if ($response->successful()) {  //
            $data = $response->json();
            //  thumbnail
            $manager = new ImageManager(new Driver());
            $this->_clipart->thumb = $manager->read($data['png_base64'])->toPng();
            //  other parameters
            $this->_clipart->gpt4_description = $data['gpt4_description'];
            $this->_clipart->clip_image_embedding_b64 = $data['clip_image_embedding_b64'];
            $this->_clipart->bert_text_embedding_b64 = $data['bert_text_embedding_b64'];
            $this->_clipart->save();
        }
        return;
    }
}
