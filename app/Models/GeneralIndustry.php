<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralIndustry extends Model
{
    use HasFactory;

    protected $table = "general_industries";
    protected $primaryKey = "general_industry_id";
}
