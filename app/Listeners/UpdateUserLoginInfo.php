<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Models\User;

class UpdateUserLoginInfo
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the login event.
     */
    public function handle(Login|Logout $event): void
    {
        if ($event instanceof Login) {
            $this->handleLogin($event);
        }

        if ($event instanceof Logout) {
            $this->handleLogout($event);
        }
    }

    /**
     * Handle user login
     */
    private function handleLogin(Login $event): void
    {
        if ($event->user instanceof User) {
            $event->user->updateLoginInfo(request()->ip());
        }
    }

    /**
     * Handle user logout
     */
    private function handleLogout(Logout $event): void
    {
        if ($event->user instanceof User) {
            $event->user->markOffline();
        }
    }
}
