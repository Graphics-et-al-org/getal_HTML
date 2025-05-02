<?php

use Diglactic\Breadcrumbs\Breadcrumbs;

Breadcrumbs::for('admin.dashboard', function ($trail) {
    $trail->push("Home", route('admin.dashboard'));
});

 require __DIR__.'/auth.php';
 require __DIR__.'/clipart.php';
 require __DIR__.'/media.php';

