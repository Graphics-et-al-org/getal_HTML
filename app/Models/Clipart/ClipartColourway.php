<?php

namespace App\Models\Clipart;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClipartColourway extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'clipart_colourways';

    protected $fillable = [
        'clipart_id',
        'data',
        'colour_id'
    ];

    // one to many relationship with clipart
    public function clipart()
    {
        return $this->hasOne('App\Models\Clipart\Clipart', 'id', 'clipart_id');
    }

    // one to many relationship with colour
    public function colour()
    {
        return $this->hasOne('App\Models\Clipart\ClipartColourwayColour', 'id', 'colour_id');
    }

    // path- used for direct link to colourway
    public function path()
    {
        return \url('/colourway') . '/' . $this->id;
    }
}
