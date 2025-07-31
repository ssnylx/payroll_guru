<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoundationSetting extends Model
{
    protected $fillable = [
        'name',
        'address',
        'logo_path',
    ];
}
