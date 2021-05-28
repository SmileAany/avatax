<?php

namespace Smbear\Avatax\Events;

class SaveDataEvent
{
    public $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }
}