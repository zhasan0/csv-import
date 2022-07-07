<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mobile extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'brand_option_id',
        'model',
        'announced',
        'weight',
        'chipset',
        'gpu',
        'release_date',
        'safe_name',
        'battery_capacity_mah',
    ];
}
