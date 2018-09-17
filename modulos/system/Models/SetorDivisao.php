<?php
namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;

class SetorDivisao extends Model{
    
    protected $table = 'tab_setor_divisao';
    
    protected $fillable = ['id', 'setor', 'sigla', 'nome', 'responsavel', 'detalhe', 'status' ];
    
    protected $primaryKey='id';

    public $timestamps = false;

}