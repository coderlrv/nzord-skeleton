<?php
namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;
use NZord\Helpers\NQuery;

class Feriado extends Model{    
    protected $table = 'tab_feriado';    
    protected $fillable = ['id', 'data', 'tipo', 'descricao'];    
    protected $primaryKey='id';
    public $timestamps = false;
    //--------------------------------------------------------------------------------
    public function setDataAttribute($value){
        $value = str_replace('/', '-', $value);
        $this->attributes['data'] = date('Y-m-d', strtotime($value));
    }
    //--------------------------------------------------------------------------------     
    public static function selectGrid(){
        return new NQuery("select
                		 id
                        ,data
                        ,tipo
                        ,descricao
                		from tab_feriado");
    }
    //--------------------------------------------------------------------------------
   
}