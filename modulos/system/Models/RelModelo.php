<?php
namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;
use NZord\Helpers\NQuery;

class RelModelo extends Model{
    
    protected $table = 'tab_relModelo';
    
    protected $fillable = ['id', 'grupo', 'nome', 'chave', 'titulo', 'tabela', 'cod_sql', 'detalhe', 'cabecalho', 'rodape', 'rotacao', 'status' ];
    
    protected $primaryKey='id';

    public $timestamps = false;
    //--------------------------------------------------------------------------------
    public static function selectGrid(){
        return new NQuery('select
        		m.id
        		,m.chave
        		,m.titulo
        		,h.nome as cabecalho
        		,f.nome as rodape
        		,i.nome as status
        		from tab_relModelo m
        		left join tab_status i on i.codigo = m.status
        		left join tab_parametro h on h.id = m.cabecalho
        		left join tab_parametro f on f.id = m.rodape');
    }
    //--------------------------------------------------------------------------------
}