<?php

namespace App\Providers;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        if (config('app.env') === 'production') {
            URL::forceScheme('https'); // Forces HTTPS on all URLs
        }

        // Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event) {
        //     $event->extendSocialite('auth0', \SocialiteProviders\Auth0\Provider::class);
        // });

         Event::listen(
        SocialiteWasCalled::class,
        function (SocialiteWasCalled $event) {
            $event->extendSocialite(
                'auth0',
                \SocialiteProviders\Auth0\Provider::class
            );
        }
    );

        // increase timeout
        Http::globalOptions([
            'timeout' => 600,
        ]);
    }
}
