<?php

namespace Orlyapps\NovaPostmark\Contracts;

interface SenderContract
{
    public function getShortnameAttribute(): string;
    public function getSignatureImageAttribute(): string;
}
