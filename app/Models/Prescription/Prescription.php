<?php

namespace App\Models\Prescription;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        "appointment_id","medication_id","quantity","frequency","start_date","end_date",
    ];
}
