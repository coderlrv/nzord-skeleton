<?php
/**
 * Class Orgao | modulos/system/Models/Orgao.php
 *
 **/
namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;
use NZord\Helpers\NQuery;

class Orgao extends Model{
    
    protected $table = 'tab_orgao';

    protected $primaryKey = 'id';

    public $timestamps = false;

    public function scopeSelect($query){
        return $query->selectRaw('id, nome')->orderBy('id','desc');
    }

    /**
     *  Retorna lista de orgao para select.
     *  Alias = none
     * @return NQuery
     */
    public static function selectList(){
        return new NQuery('select
                        id
                    ,concat(sigla," - ", SUBSTRING(nome,1,91)) as nome
                    from tab_orgao');
    }
}