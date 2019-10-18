<?php
/**
 * Class Dpto | modulos/system/Models/Dpto.php
 *
 */

namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;

class Dpto extends Model{
    
    protected $table = 'tab_orgao';
    
    protected $primaryKey='id';

    public $timestamps = false;
    
    //--------------------------------------------------------------------------------
    public static function selectList( $orderBy=null, $where=null ){
        return DB::select("select
							id
                            ,nome
						FROM tab_orgao".
            ( ($where)? ' where '.$where:'').
            ( ($orderBy) ? ' order by '.$orderBy:''));
    }
    //--------------------------------------------------------------------------------
}