<?php

namespace Thanatos\Listeners;

use Thanatos\Events\ThanatosNotify;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWebNotification
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
     * @param  ThanatosNotify  $event
     * @return void
     */
    public function handle(ThanatosNotify $event)
    {
        //
    }
}
