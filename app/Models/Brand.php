<?php

namespace App\Models;

use Database\Factories\BrandFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Model;

#[UseFactory(BrandFactory::class)]
class Brand extends Model
{
    //
}
