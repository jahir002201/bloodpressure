<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bloodpressure extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'systolic', 'diastolic','category'];
}
