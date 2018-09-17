<?php
namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;

class RelTipo extends Model{
    
    protected $table = 'tab_relTipo';
    
    protected $fillable = ['id', 'grupo', 'nome', 'codsql', 'status' ];
    
    protected $primaryKey='id';

    public $timestamps = false;
    //--------------------------------------------------------------------------------
    public static function selectGrid( $orderBy=null, $where=null ){
        return DB::select('select
        		m.id
        		,m.chave
        		,m.titulo
        		,h.nome as cabecalho
        		,f.nome as rodape
        		,i.nome as status
        		from tab_relModelo m
        		left join tab_status i on i.codigo = m.status
        		left join tab_parametro h on h.id = m.cabecalho
        		left join tab_parametro f on f.id = m.rodape'.
        ( ($where)? ' where '.$where:'').
        ( ($orderBy) ? ' order by '.$orderBy:''));
    }
    //--------------------------------------------------------------------------------
}