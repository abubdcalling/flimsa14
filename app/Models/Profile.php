<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    protected $fillable = [
        'image',
        'first_name',
        'last_name',
        'email',
        'position',
        'website_link',
        'about',
        'phone',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
    ];
}
