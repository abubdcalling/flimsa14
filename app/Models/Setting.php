<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = [
        'icon',
        'system_name',
        'system_title',
        'system_address',
        'email',
        'phone',
        'opening_hour',
        'description',
    ];
    
}
