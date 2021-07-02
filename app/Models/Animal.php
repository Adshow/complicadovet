<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    protected $table = 'animais';

    protected $fillable = ['id', 'nome', 'pessoa_id', 'raca_id', 'especie_id', 'historico_clinico', 'nascimento'];

    public function raca()
    {
        return $this->belongsTo('App\Models\Raca');
    }

    public function especie()
    {
        return $this->belongsTo('App\Models\Especie');
    }
}