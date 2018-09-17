<?php
namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;

class Perfil extends Model{
    
    protected $table = 'tab_perfil';
    
    protected $primaryKey='id_perfil';

    public $timestamps = false;
    //--------------------------------------------------------------------------------
    public static function selectList( $orderBy=null, $where=null ){
        return DB::select("select
							id_perfil id
                            ,nome
						FROM tab_perfil".
            ( ($where)? ' where '.$where:'').
            ( ($orderBy) ? ' order by '.$orderBy:''));
    }
    //--------------------------------------------------------------------------------

}