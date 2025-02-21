<?php

use App\Models\Clipart\Clipart;
use Diglactic\Breadcrumbs\Breadcrumbs;
use App\Models\User;

Breadcrumbs::for('admin.clipart.index', function ($trail) {
    $trail->push("Assets admin", route('admin.clipart.index'));
});


Breadcrumbs::for('admin.clipart.create', function ($trail) {
    $trail->parent('admin.clipart.index');
    $trail->push('Create asset', route('admin.clipart.create'));
});



Breadcrumbs::for('admin.clipart.edit', function ($trail, $id) {
    $trail->parent('admin.clipart.index');
    $trail->push('Update '.Clipart::find($id)->name, route('admin.clipart.edit', $id));

});
