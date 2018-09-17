<?php
namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;
use NZord\Helpers\NQuery;

class Bairro extends Model{
    protected $table = 'tab_bairro';
    protected $fillable = ['id', 'nome', 'set_pref', 'set_saae', 'cod_pref', 'status'];
    protected $primaryKey = 'id';
    
    public $timestamps = false;

    /**
     *  Busca lista de bairro
     *  Alias = none 
     *      
     * @return NQuery
     */
    public static function selectList(){
        return new NQuery('select
                                id
                            ,concat(id," | ",nome) as nome
                            from tab_bairro');
    }
}