<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipos extends Model
{
    protected $table = 'TipoAtraccion'; 
    protected $primaryKey = 'Id';       
    public $timestamps = false;

    protected $fillable = ['Nombre'];

    public function lugares()
    {
        return $this->hasMany(LugaresT::class, 'IdTipoA', 'Id');
    }
}
