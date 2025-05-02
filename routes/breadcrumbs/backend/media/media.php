<?php

use App\Models\Clipart\Clipart;
use Diglactic\Breadcrumbs\Breadcrumbs;
use App\Models\User;

Breadcrumbs::for('admin.media.index', function ($trail) {
    $trail->push("Assets admin", route('admin.media.index'));
});


Breadcrumbs::for('admin.media.create', function ($trail) {
    $trail->parent('admin.media.index');
    $trail->push('Create media', route('admin.media.create'));
});



Breadcrumbs::for('admin.media.edit', function ($trail, $id) {
    $trail->parent('admin.media.index');
    $trail->push('Update '.Clipart::find($id)->name, route('admin.media.edit', $id));

});
