<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{id}', function ($user, $id) {
    // If currecnt user is in the group, then allow to join the channel
    if ($user->groups->contains($id)) {
        return true;
    }
});

