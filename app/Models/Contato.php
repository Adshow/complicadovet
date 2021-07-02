<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contato extends Model
{
    protected $table = 'contato';

    protected $fillable = ['telefone_1', 'tipo_telefone_1', 'telefone_2', 'tipo_telefone_2', 'email', 'pessoa_id'];

    public function pessoa()
    {
        return $this->belongsTo('App\Models\Pessoa');
    }


}