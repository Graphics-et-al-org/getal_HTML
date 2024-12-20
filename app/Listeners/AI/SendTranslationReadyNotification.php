<?php

namespace App\Listeners\AI;

use App\Events\AI\TranslationReadyEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTranslationReadyNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TranslationReadyEvent $event): void
    {
        //
    }
}
