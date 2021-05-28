<?php

namespace Smbear\Avatax\Contracts;

interface AvataxInterface
{
    public function setLocal($local = 'en') : object;

    public function createTransaction(string $type): array;
}