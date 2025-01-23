<?php
use Diglactic\Breadcrumbs\Breadcrumbs;

Breadcrumbs::for('frontend.index', function ($trail) {
    $trail->push('Home', route('frontend.index'));
});

