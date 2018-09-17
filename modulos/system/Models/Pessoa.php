<?php
namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;
use NZord\Helpers\NQuery;

class Pessoa extends Model{
    
    protected $table = 'tab_pessoa';
    
    protected $fillable = ['id', 'matricula', 'nome', 'fantasia', 'cpf_cnpj', 'documento', 'data_nasc', 'ecivil', 'telefone', 'celular', 'mail', 'status', 'func', 'user_id', 'ip_user', 'criado'];
    
    protected $primaryKey='id';

    public $timestamps = false;
    //--------------------------------------------------------------------------------
    public function setDataNascAttribute($value){
        $value = str_replace('/', '-', $value);
        $this->attributes['data_nasc'] = date('Y-m-d', strtotime($value));
    }
    //--------------------------------------------------------------------------------
    public static function selectList( $orderBy=null, $where=null ){
        return DB::select("select
							id
                            ,nome
						FROM tab_pessoa".
            ( ($where)? ' where '.$where:'').
            ( ($orderBy) ? ' order by '.$orderBy:''));
    }
    //--------------------------------------------------------------------------------
    public static function selectListMat( $orderBy=null, $where=null ){
        return DB::select("select
							matricula id
                            ,nome
						FROM tab_pessoa".
            ( ($where)? ' where '.$where:'').
            ( ($orderBy) ? ' order by '.$orderBy:''));
    }
    //--------------------------------------------------------------------------------   
    public static function selectGrid(){
        return new NQuery("select
                		 p.id
                		,concat(case when p.func = '1' then 'Func.: ' else '' end, coalesce(p.matricula, null) ) as mat
                		,p.nome
                		,p.cpf_cnpj
                		,p.documento
                		,concat(p.telefone ,p.celular) as fone
                		,p.mail
                		,s.nome as status
                		,p.criado as data_cad
                		from tab_pessoa p
                		join tab_status s on s.codigo = ( case when p.status = 'A' then '1' else '0' end)");
    }
    //--------------------------------------------------------------------------------
    public static function selectAll( $orderBy=null, $where=null ){
        return DB::select("select
							*
						FROM tab_pessoa".
            ( ($where)? ' where '.$where:'').
            ( ($orderBy) ? ' order by '.$orderBy:''));
    }
    //--------------------------------------------------------------------------------
    public static function updateFuncPessoa(){
        return DB::table('tab_pessoa')
                ->where('func', 1)
                ->update(['func' => 0 ]);
    }
    //--------------------------------------------------------------------------------

}