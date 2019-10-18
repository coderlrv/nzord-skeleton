<?php
/**
 * Class Log | modulos/system/Models/Log.php
 *
 */
namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;
use NZord\Helpers\NQuery;

class Log extends Model{
    
    protected $table = 'sys_log';
    
    protected $primaryKey='id';

    public $timestamps = false;
    
    /**
     *  Select  logs para grid
     *  Alias =
     *      sys_log = a
     *      tab_usuario = u
     * @return NQuery
     */
    public static function selectGrid(){
        return new NQuery('SELECT 
            				 a.id
            				,a.data
            				,a.ip
            				,a.tela
            				,SUBSTRING(a.detalhe,1,80) as acao
            				,e.nome
            		FROM sys_log a 
            		left join tab_usuario e on e.id = a.user');
    }
}