<?php

namespace Orlyapps\NovaPostmark;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Orlyapps\NovaPostmark\NovaPostmark
 */
class NovaPostmarkFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'nova-postmark';
    }
}
