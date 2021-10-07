<?php

namespace Orlyapps\NovaPostmark\Contracts;

interface ReceiverContract
{
    public function getSalutation(): string;
    public function getSalutationWithFirstname(): string;
    public function getSalutationInformal(): string;
}
