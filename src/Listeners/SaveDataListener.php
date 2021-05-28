<?php

namespace Smbear\Avatax\Listeners;

use Smbear\Avatax\Events\SaveDataEvent;
use Smbear\Avatax\Models\AvataxRecords;

class SaveDataListener
{
    public function handle(SaveDataEvent $event)
    {
        AvataxRecords::create($event->data);
    }
}