<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patrocinador extends Model
{
    use HasFactory;

    public $table = 'patrocinadores';

    protected $fillable = [
        'nombre',
        'logo',
        'instagram',
        'tiktok',
        'facebook',
        'telefono',
    ];
}
