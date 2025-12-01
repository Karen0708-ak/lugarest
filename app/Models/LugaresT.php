<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LugaresT extends Model
{
    protected $table = 'LugarTuristico2';
    protected $primaryKey = 'IdLugar';    
    public $timestamps = false;

    protected $fillable = [
        'NombreLugar',
        'IdProvi',
        'IdTipoA',
        'Latitud',
        'Longitud',
        'Descripcion',
        'AnioCreacion',
        'Accesibilidad'
    ];

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'IdProvi', 'Id');
    }

    public function tipo()
    {
        return $this->belongsTo(Tipos::class, 'IdTipoA', 'Id');
    }
}
