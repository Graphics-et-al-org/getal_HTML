<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use App\Models\User;

Breadcrumbs::for('admin.users.index', function ($trail) {
    $trail->push("User admin", route('admin.users.index'));
});


Breadcrumbs::for('admin.users.new', function ($trail) {
    $trail->parent('admin.users.index');
    $trail->push('Create local user', route('admin.users.new'));
});

// Breadcrumbs::for('admin.auth.user.show', function ($trail, $id) {
//     $trail->parent('admin.auth.user.index');
//     $trail->push(__('menus.backend.access.users.view'), route('admin.auth.user.show', $id));
// });

Breadcrumbs::for('admin.users.edit', function ($trail, $id) {
    $trail->parent('admin.users.index');
    $trail->push('Update '.User::find($id)->name, route('admin.users.edit', $id));


});

// Breadcrumbs::for('admin.auth.user.account.sneakyregister', function ($trail, $id) {
//     $trail->parent('admin.auth.user.index');
//     $trail->push(__('buttons.backend.access.users.sneakyregister'), route('admin.auth.user.account.sneakyregister', $id));
// });

// Breadcrumbs::for('admin.auth.user.change-password', function ($trail, $id) {
//     $trail->parent('admin.auth.user.index');
//     $trail->push(__('menus.backend.access.users.change-password'), route('admin.auth.user.change-password', $id));
// });
