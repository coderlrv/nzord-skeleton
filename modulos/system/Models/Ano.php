<?php
/**
 * Class Ano | modulos/system/Models/Ano.php
 *
 */

namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;

class Ano extends Model{
    
    protected $table = 'tab_ano';

    protected $primaryKey='id';
    
    protected $fillable = ['id','ano'];

    public $timestamps = false;
    
    //--------------------------------------------------------------------------------
    public function scopeGetAnos($query){
        return $query->selectRaw('id, ano as nome')->orderBy('id','desc');
    }
    //--------------------------------------------------------------------------------
    public static function selectList($orderBy=null, $where=null){
        return DB::select("select
						 id
						,ano nome
						from tab_ano".
            ( ($where)? ' where '.$where:'').
            ( ($orderBy) ? ' order by '.$orderBy:''));
    }
    //------------------------------------------------------------------------------------------------
}