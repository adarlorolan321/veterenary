<?php

namespace App\Models\Medicalrecord;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        "animal_id","vet_id","record_date","diagnosis","treatment","notes",
    ];
}
