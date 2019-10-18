<?php
namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;

class Sessao extends Model{
    
    protected $table = 'reg_sessao';
    
    protected $primaryKey='id';

    public $timestamps = false;
    
    //--------------------------------------------------------------------------------
    public static function selectGrid( $orderBy=null, $where=null ){
        return DB::select("select 
                            		r.id
                                    ,r.session_id
                            		,r.data as data_logon
                            		,r.tipo
                            		,(select count(*) from useronline WHERE sessao = r.id ) as ses
                            		,COALESCE(concat(r.dpto,' - ',o.sigla,' | ',r.setor,' - ',s.setor ),concat(r.empresa,' - ',e.nome)) as local
                            		,concat(r.perfil,' - ',p.nome) as perfil
                            		,concat(LPAD(r.user_id,4,'0'),' - ',u.nome) as nome
                            		,u.login
                            		,r.remo_ip
                            		,r.browser
                            		,r.url
                            		,case r.status 
                            			when 'A' then '<span class=\"label label-success\" style=\"font-size: 90%\"><b>Ativa</b></span>'
                            			when 'E' then '<span class=\"label label-warning\" style=\"font-size: 90%\"><b>Encerrado</b></span>'
                            			when 'I' then '<span class=\"label label-danger\" style=\"font-size: 90%\"><b>Enc. Forçado</b></span>'
                            			end as status
                            		from reg_sessao r
                            		left join tab_usuario u on u.id = r.user_id
                            		left join tab_setor s on s.id = r.setor
                            		left join tab_orgao o on o.id = r.dpto
                            		left join n_equipe e on e.id = r.empresa
                            		left join tab_perfil p on p.id_perfil = r.perfil".
            ( ($where)? ' where '.$where:'').
            ( ($orderBy) ? ' order by '.$orderBy:''));
    }
    //--------------------------------------------------------------------------------
    public static function selectDetAcesso( $orderBy=null, $where=null ){
        return DB::select("SELECT 
                        sessao
                        , timestamp
                        , arquivo 
                        FROM useronline 
                        group by sessao, arquivo".
            ( ($where)? ' where '.$where:'').
            ( ($orderBy) ? ' order by '.$orderBy:''));
    }
    //--------------------------------------------------------------------------------
    public static function getSessaoAtiva( $id ){
        return DB::select('SELECT id, status                        
                        FROM reg_sessao
                        where id = ? and status = "A"', array($id) );
    }
    //--------------------------------------------------------------------------------
}