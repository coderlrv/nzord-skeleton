<?php
namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;
use NZord\Helpers\NQuery;

class Cidade extends Model{
    
    protected $table = 'tb_cidades';
    
    protected $fillable = [];
    
    protected $primaryKey='id';

    public $timestamps = false;

    /**
     * Lista de cidade para select
     *
     * @return void
     */
    public static function selectList(){
        return new NQuery("select
                            id
                            ,concat(nome,' - ',uf) as nome
                            from tb_cidades");
    }
}