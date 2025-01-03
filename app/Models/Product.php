<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Config\Configuration;

class Product extends Model
{
    protected $fillable = [
        Configuration::PRODUCT_PROPERTY_NAME,
        Configuration::PRODUCT_PROPERTY_PRICE,
    ];
}
