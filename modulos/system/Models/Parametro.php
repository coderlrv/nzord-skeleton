<?php
namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;

class Parametro extends Model{
    
    protected $table = 'tab_parametro';
    
    protected $fillable = ['id', 'nome', 'descricao', 'valor', 'status'];
        
    protected $primaryKey='id';

    public $timestamps = false;    
    //--------------------------------------------------------------------------------
   /**
    *  Busca paramentro
    *
    * @param string $paramNome - nome do paramentro no banco de dados.
    * @param boolean | Closure $tranform - callback com argumento o paramentro do banco de dados 
    * @return void
    */
    public static function get($paramNome,$tranform=false,$returnNull=true){
        $params = self::where('nome',$paramNome)->first();
        if(!isset($params) && $returnNull) return null;

        if($tranform)
            return call_user_func($tranform,isset($params) ? $params->valor : null);
        else
            return $params->valor;
    }
    //--------------------------------------------------------------------------------
}