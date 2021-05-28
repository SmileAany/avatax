<?php

namespace Smbear\Avatax\Models;

use Illuminate\Database\Eloquent\Model;

class AvataxRecords extends Model
{
    const UPDATED_AT = NULL;

    protected $table = 'avatax_records';

    protected $fillable = [
        'user_id',
        'document_id',
        'address',
        'from',
        'order',
        'status',
        'response'
    ];
}