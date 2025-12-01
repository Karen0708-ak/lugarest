<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    protected $table = 'Provincia'; 
    protected $primaryKey = 'Id';   
    public $timestamps = false;

    protected $fillable = ['Nombre'];

    public function lugares()
    {
        return $this->hasMany(LugaresT::class, 'IdProvi', 'Id');
    }
}
