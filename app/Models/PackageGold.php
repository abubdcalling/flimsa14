<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageGold extends Model
{
    use HasFactory;
    protected $fillable = [
        'image',
        'title',
        'no_of_client',
        'price',
        'vat_type',
    ];
}
