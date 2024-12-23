<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;

use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      //  dd('hi');
        // Registers the broadcasting routes
      //  Broadcast::routes();
     // Define custom logging for broadcasting authorization attempts
    //  Broadcast::channel('translation-status.{email}', function ($user, $email) {


    //     return $user->email === $email; // Authorization logic
    // });

        // Load the channels file where you define channel authorization logic
        require base_path('routes/channels.php');
    }
}
