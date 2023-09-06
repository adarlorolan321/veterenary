<?php

namespace App\Models\Appointment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        "pet_id","extendedProps","start","end","allDay","url",
    ];

    protected $casts = [
        'extendedProps' => 'array',
    ];
}
