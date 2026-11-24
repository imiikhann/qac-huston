<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Admin panel real-time notifications (new tickets / new customer messages).
// Any authenticated panel user may listen.
Broadcast::channel('admin.tickets', function ($user) {
    return (bool) $user;
});
