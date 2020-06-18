<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Carro extends Model
{
    protected $table = 't_carros';
    protected $primaryKey = 'id_car';
    
    //relacion
    public function usuario(){
        return $this->belongsTo('App\User','usuario_id');
    }
}
