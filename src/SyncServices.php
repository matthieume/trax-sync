<?php

namespace Trax\Sync;

class SyncServices
{
    /**
     * Pusher instance.
     */
    protected $pusher;
    

    /**
     * Pusher.
     */
    protected function pusher()
    {
        return new Pusher();
    }

}
