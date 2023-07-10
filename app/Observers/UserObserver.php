<?php

namespace App\Observers;

use App\User;
use Illuminate\Support\Facades\DB;

class UserObserver
{
    public function created(User $user)
    {
        DB::transaction(function() use ($user) {
           $user->roles()->attach(3);
        });
    }

    public function deleting(User $user)
    {
        DB::transaction(function() use ($user) {
            $user->roles()->detach();
        });
    }
}
