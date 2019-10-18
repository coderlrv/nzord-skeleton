<?php
/**
 * Class Empresa | modulos/system/Models/Empresa.php
 *
 */
namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;
use NZord\Helpers\NQuery;

class Empresa extends Model{
    protected $table = 'tab_empresa';
    
    protected $primaryKey='id';

    public $timestamps = false;

    public static function selectList(){
        return new NQuery('select id, razao as nome from tab_empresa');
    }
}