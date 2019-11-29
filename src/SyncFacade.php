<?php

namespace Trax\Sync;

use Illuminate\Support\Facades\Facade;

class SyncFacade extends Facade
{

    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor()
    {
        return 'Trax\Sync\SyncServices';
    }

}
