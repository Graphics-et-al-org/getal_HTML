<?php

use App\Providers\ComposerServiceProvider;
use App\Providers\HelperServiceProvider;
use App\Providers\ObserverServiceProvider;
use App\Services\AnnouncementService;

return [
    App\Providers\AppServiceProvider::class,
    \SocialiteProviders\Manager\ServiceProvider::class,
    HelperServiceProvider::class,
    ComposerServiceProvider::class,
    ObserverServiceProvider::class,
   // AnnouncementService::class,
];
