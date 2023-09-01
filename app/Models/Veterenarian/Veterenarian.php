<?php

namespace App\Models\Veterenarian;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Veterenarian extends Model
{
    use HasFactory;

    protected $fillable = [
        "first_name","last_name","email","phone_number","specialization",
    ];
}
