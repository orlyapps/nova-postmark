<?php

namespace Orlyapps\NovaPostmark\Traits;

use Orlyapps\NovaPostmark\Models\Letter;

/**
 *
 */
trait InteractWithLetters
{
    public function letters()
    {
        return $this->morphMany(Letter::class, 'receiver');
    }
}
