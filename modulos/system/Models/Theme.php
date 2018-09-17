<?php
/**
 * Models Theme | modulos/system/Models/Theme.php
 *
 */
namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;
use NZord\Helpers\NQuery;

class Theme extends Model{
    
    protected $table = 'sys_config_theme';
    
    protected $fillable = ['id', 'nome', 'status'];
    
    protected $primaryKey = 'id';

    public $timestamps = false;
    
    //--------------------------------------------------------------------------------
    public static function selectListNome( $orderBy=null, $where=null ){        
        return DB::select('select
                           nome id
                           ,nome
                           FROM sys_config_theme'.
            ( ($where)? ' where '.$where:'').
            ( ($orderBy) ? ' order by '.$orderBy:''));
    }
    //--------------------------------------------------------------------------------
}