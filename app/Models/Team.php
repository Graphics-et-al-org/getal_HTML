<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laratrust\Models\Team as LaratrustTeam;

class Team extends LaratrustTeam
{

    use HasFactory;

    public $guarded = [];

    
}
