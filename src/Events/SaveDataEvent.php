<?php

namespace Smbear\Avatax\Events;

class SaveDataEvent
{
    public $data;

    public $type;

    public function __construct(array $data,string $type)
    {
        $this->data = $data;

        $this->type = $type;
    }
}