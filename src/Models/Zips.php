<?php

namespace Smbear\Avatax\Models;

use Illuminate\Database\Eloquent\Model;

class Zips extends Model
{
    protected $table = 'zips';

    protected $fillable = [
        'zip',
        'states_code'
    ];
}