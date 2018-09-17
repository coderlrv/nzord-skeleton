<?php
namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;
use NZord\Helpers\NQuery;

class Modulo extends Model{    
    protected $table = 'sys_modulos';    
    protected $fillable = ['id', 'nome', 'detalhe', 'menu'];    
    protected $primaryKey='id';
    public $timestamps = false;
    //--------------------------------------------------------------------------------     
    public static function selectGrid(){
        return new NQuery("select
                		 id
                        ,nome
                        ,detalhe
                        ,menu
                		from sys_modulos");
    }
    //--------------------------------------------------------------------------------
   
}