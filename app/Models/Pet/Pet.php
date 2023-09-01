<?php

namespace App\Models\Pet;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id","type","breed","name","age","gender","weight","dob",
    ];


public function user()
    {
        return $this->belongsTo(User::class);
    }

}
    

