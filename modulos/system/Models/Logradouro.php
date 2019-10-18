<?php
namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;
use NZord\Helpers\NQuery;

class Logradouro extends Model{
    protected $table = 'tab_logradouro';
    protected $fillable = ['id', 'prefixo', 'nome', 'cod_pref', 'cod_saae', 'status'];
    protected $primaryKey = 'id';
    
    public $timestamps = false;

    /**
     *  Busca lista de lougradouro grid
     *  Alias =  
     *          tab_logradouro = l
     *          tab_status = s
     * @return NQuery
     */
    public static function selectGrid(){
        return new NQuery("select
                		 l.id
                        ,l.prefixo
                        ,l.nome
                        ,l.cod_pref
                        ,l.cod_saae
                        ,s.nome as status
                		from tab_logradouro l
                        left join tab_status s on s.code = l.status");
    }
   
    /**
     *  Busca lista de lougradouro
     *  Alias = none 
     *      
     * @return NQuery
     */
    public static function selectList(){
        return new NQuery('select
                            id
                            ,concat(id," | ",prefixo," ",nome) as nome
                            from tab_logradouro');
    }
}