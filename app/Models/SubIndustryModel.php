<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubIndustryModel extends Model
{
    use HasFactory;
    protected $table = "sub_industries";
    protected $primaryKey = "sub_industry_id";
}
