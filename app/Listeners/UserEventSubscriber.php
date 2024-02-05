<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserEventSubscriber
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //
    }

    /**
     * Handle user login events.
     */
    public function onUserLogin($event)
    {
        // Registra o acesso do usuário logado
        auth()->user()->registerAccess();
        
        //Registra o token de acesso do usuário
        $tokenAccess = bcrypt(date('YmdHms'));

        $user = auth()->user();
        $user->token_access = $tokenAccess;
        $user->save();

        //Registrando a sessão com o mesmo token
        session()->put('access_token', $tokenAccess);
    }
 
 
    /**
     * Handle user logout events.
     */
    public function onUserLogout($event)
    {
        // Se quiser implementar algo pós logout é neste estágio
        //dd($event);
    }
 
 
    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Login',
            'App\Listeners\UserEventSubscriber@onUserLogin'
        );
 
        $events->listen(
            'Illuminate\Auth\Events\Logout',
            'App\Listeners\UserEventSubscriber@onUserLogout'
        );
    }
}
