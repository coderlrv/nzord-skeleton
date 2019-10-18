<?php
namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;

class Unidade extends Model{
    
    protected $table = 'tab_unidade';
    
    protected $fillable = ['id', 'orgao', 'codlo', 'sigla', 'nome', 'status' ];
    
    protected $primaryKey='id';

    public $timestamps = false;
    
    //--------------------------------------------------------------------------------
    public static function selectList( $orderBy=null, $where=null ){
        return DB::select("select
							id
                            ,nome
						FROM tab_unidade".
            ( ($where)? ' where '.$where:'').
            ( ($orderBy) ? ' order by '.$orderBy:''));
    }
    //--------------------------------------------------------------------------------
}