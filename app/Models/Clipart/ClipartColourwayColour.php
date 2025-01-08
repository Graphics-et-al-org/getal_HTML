<?php

namespace App\Models\Clipart;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClipartColourwayColour extends Model
{
    use HasFactory;
    //
    protected $table = 'clipart_colourways_colour';

    protected $fillable = [
        'name', 'colour_code'
    ];

    public function colourways()
    {
        return $this->hasMany('App\Models\Clipart\ClipartColourway', 'colour_id', 'id');
    }


}
