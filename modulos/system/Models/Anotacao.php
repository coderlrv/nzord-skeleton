<?php
/**
 * Class Anotacao | modulos/system/Models/Anotacao.php
 *
 */
namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;

class Anotacao extends Model{
    
    protected $table = 'cad_anotacao';
    
    protected $fillable = ['id', 'data', 'class', 'titulo', 'texto', 'user_id', 'sessao' ];
    
    protected $primaryKey='id';

    public $timestamps = false;
    
    //--------------------------------------------------------------------------------
    public static function selectToDo($orderBy=null, $where=null){
        return DB::select("select
                        id
                        ,data
                        ,titulo label
                        ,texto detalhe
                        ,class
                        ,concat('notes(',id,')') edit
                    FROM cad_anotacao".
                ( ($where)? ' where '.$where:'').
                ( ($orderBy) ? ' order by '.$orderBy:''));
    }
    //--------------------------------------------------------------------------------
}