<?php

namespace Orlyapps\NovaPostmark\Contracts;

interface ReceiverContract
{
    public function getReceiverAddressAttribute(): string;
    public function getReceiverEmailAttribute(): array;
    public function getLetterInfoAttribute(): string;
    public function getSalutation(): string;
    public function getSalutationWithFirstname(): string;
    public function getSalutationInformal(): string;
}
