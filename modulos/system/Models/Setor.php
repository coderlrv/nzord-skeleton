<?php
namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;
use NZord\Helpers\NQuery;

class Setor extends Model{
    
    protected $table = 'tab_setor';
    
    protected $fillable = ['id', 'orgao', 'unidade', 'setor', 'sigla', 'localiza', 'endereco', 'bairro', 'responsavel', 'cargo_id', 'telefone', 'email', 'detalhe', 'horario', 'lat', 'lon', 'status' ];
    
    protected $primaryKey='id';

    public $timestamps = false;
    
    /**
     * Lista de setor
     *  Alias = none
     * 
     * @return NQuery
     */
    public static function selectList(){
        return new NQuery('select
                                id
                                ,concat(setor," - ",id) as nome
                            FROM tab_setor');
    }
    //--------------------------------------------------------------------------------
    public static function selectGrid( $orderBy=null, $where=null ){
        return DB::select("select
                    		 s.id
                    		,s.sigla
                    		,s.setor
                    		,o.sigla as orgao
                    		,u.sigla as unidade
                    		,s.localiza
                    		,s.endereco
                    		,s.bairro
                    		,us.nome as responsavel
                    		,s.telefone
                    		,st.nome as status
                    		from tab_setor s
                    		left join tab_orgao o on (o.codigo = s.orgao)
                    		left join tab_unidade u on (u.id = s.unidade)
                    		left join tab_usuario us on us.id = s.responsavel
                    		left join tab_status st on coalesce(st.codigo,st.code) = s.status".
            ( ($where)? ' where '.$where:'').
            ( ($orderBy) ? ' order by '.$orderBy:''));
    }
    //--------------------------------------------------------------------------------
    public static function selectListOrgaoUni($orderBy=null, $where=null ){
        $sql = 'select
                    s.id
                    ,concat(s.setor," - ",o.sigla," | ",s.id) as nome
                    from tab_setor s
                    left join tab_orgao o on (o.id = s.orgao)
                    left join tab_unidade u on (u.id = s.unidade)'.
                ( ($where)
                    ? ' where '.$where 
                    : ' where s.status = "A" ').
                ( ($orderBy) 
                    ? ' order by '.$orderBy
                    : '');
        return DB::select($sql);
    }
    //--------------------------------------------------------------------------------
    public static function selectGeral($orderBy=null, $where=null ){
        return DB::select("select
							*
						FROM tab_setor".
            ( ($where)? ' where '.$where:'').
            ( ($orderBy) ? ' order by '.$orderBy:''));
    }
    //--------------------------------------------------------------------------------
    public static function selectOrgaoSetor($orderBy=null, $where=null ){
        return DB::select("select 
                        s.id
                        ,s.setor
                        ,s.orgao orgao_id
                        ,o.sigla as orgao
						from tab_setor s
						inner join tab_orgao o on (o.codigo = s.orgao)".
            ( ($where)? ' where '.$where:'').
            ( ($orderBy) ? ' order by '.$orderBy:''));
    }
    //--------------------------------------------------------------------------------
}