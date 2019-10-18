<?php
/**
 * Models Basica | modulos/system/Models/Basica.php
 *
 */

namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;
use NZord\Helpers\NQuery;

class Basica extends Model{
    
    protected $table = 'tab_basica';
    
    protected $fillable = ['id', 'sigla', 'tabela', 'valor', 'status'];
    
    protected $primaryKey='id';

    public $timestamps = false;
    
    //--------------------------------------------------------------------------------
    public static function getValues($nomeTabela){
        return DB::select('select id, valor as nome from tab_basica where tabela = "'.$nomeTabela.'" and status = "A"');
    }
    //--------------------------------------------------------------------------------
    public static function getIdBySigla($tabela,$sigla,$default=null){
        $dados = DB::select('select id, valor as nome from tab_basica where sigla like \''.$sigla.'%\' and tabela = "'.$tabela.'"');
        if( $dados ){
            return $dados[0]->id;
        }else{
            return $default;
        }
    }
    //--------------------------------------------------------------------------------
    public static function selectGrid(){
        return new NQuery("select
                        b.id
                        ,concat(b.sigla,' - ', b.valor) as dados
                        ,b.tabela
                        ,b.valor
                        ,s.nome as status
                        from tab_basica b
                        join tab_status s on s.code = b.status");
    }
    //--------------------------------------------------------------------------------
    public static function selectListTabela(){
        return DB::select("select
                        tabela id
                        ,tabela nome
                        from tab_basica
                        group by tabela");
    }
    //-------------------------------------------------------------------------------- 
}