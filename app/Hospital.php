<?php

namespace App;
use App\User;

use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    protected $fillable = [
        'name', 'location'
    ];
}
