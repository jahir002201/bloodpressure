<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodPressure extends Model
{
    use HasFactory;



    // Specify which attributes are mass assignable
    protected $fillable = ['user_id', 'systolic', 'diastolic'];
}
