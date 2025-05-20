<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Customer;
use App\Models\Admin;

Broadcast::channel('inbox.admin.{id}', function ($admin, $id) {
    return $admin->id == $id;
}, ['guards' => ['admin']]); // ini penting!


Broadcast::channel('inbox.customer.{id}', function ($user, $id) {
    return auth('web')->check() && auth('web')->id() == $id;
});

