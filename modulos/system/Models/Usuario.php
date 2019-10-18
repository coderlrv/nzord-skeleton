<?php
namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;
use NZord\Helpers\NQuery;

class Usuario extends Model{
    
    protected $table = 'tab_usuario';
    
    protected $fillable = ['perfil', 'empresa', 'dpto', 'setor', 'acesso_dpto', 'matricula', 'nome', 'login', 'avatar', 'genero', 'status', 'access_at' ];
    
    protected $primaryKey='id';

    public $timestamps = false;

    public function checkPassword($user){
        if (!isset($user['password']) or !isset($this->senha)) {
            return false;
        } else {
            if (md5($user['password']) == $this->senha) {
                return true;
            } else {
                return false;
            }
        }
    }

    public static function select(){
        return new NQuery("select 
                            id 
                            ,concat(nome,' | ',id) as nome
                            from tab_usuario");
    }
    //--------------------------------------------------------------------------------
    public static function selectList( $orderBy=null, $where=null ){
        return DB::select("select 
                        id 
                        ,nome 
                        from tab_usuario".
            ( ($where)? ' where '.$where:'').
            ( ($orderBy) ? ' order by '.$orderBy:''));
    }
    //--------------------------------------------------------------------------------
    public static function selectPorSetor($idUsuario,$idSetor,$orderBy=null,$where=null){
        $sql = "select
                        u.id
                    ,COALESCE(concat('[Secretário(a) ',u.id,' - ',u.nome,' * ]' ),'N/Definido') as nome
                    from tab_setor s
                    left join tab_orgao o on o.id = s.orgao
                    left join tab_pessoa p on p.id = o.func_id
                    left join tab_usuario u on lpad(u.matricula,6,'0') = lpad(p.matricula,6,'0') and u.status = 'A'
                    where s.id = ? and u.status = 'A'
                union all
                select
                    u.id
                ,concat('[ - ',u.id,' - ',u.nome,' - ]') as nome
                from tab_usuario u
                where u.id = ? and u.status = 'A'
                union all
                select
                    u.id
                    ,concat(u.id,' - ',u.nome,' | ',u.matricula) as nome
                    from tab_usuario u
                    where u.setor = ? and u.status = 'A'";

        return DB::select($sql,[$idSetor,$idUsuario,$idSetor]);
    }

    /**
     *  Select lista de usuários para grid.
     *  Alias =
     *      tab_usuario = u
     *      tab_orgao   = o
     *      tab_setor   = s
     *      tab_perfil  = p
     *      n_equipe    = e
     *      tab_status  = st
     * @return NQuery
     */
    public static function selectGrid(){
        return new NQuery("select
                u.id
                ,p.nome as perfil
                ,o.sigla as dpto
                ,COALESCE(concat(s.id,' - ',s.setor),e.nome,'N/Definido') as setor
                ,concat(LPAD(matricula,5,'0'),' - ',u.nome) as nome
                ,u.login
                ,st.nome as status
                ,DATE_FORMAT((select max(data) from reg_sessao where user_id = u.id), '%d/%m/%Y  %H:%i') as acesso
                from tab_usuario u
                left join tab_orgao o on o.id = u.dpto
                left join tab_setor s on s.id = u.setor
                left join tab_perfil p on p.id_perfil = u.perfil
                left join n_equipe e on e.id = u.empresa
                left join tab_status st on st.code = u.status");
    }
    //--------------------------------------------------------------------------------  
    public static function selectUserForSetor( $id ){
        $dados = array();
        $sec = DB::select("select
						 u.id
						,COALESCE(u.nome,'N/Definido') as nome
						,case when u.avatar is null then 'images/avatar/avatar_1.png' else u.avatar end avatar 
						,u.matricula
                        ,u.login
                        ,1 secretario
						from tab_setor s
						left join tab_orgao o on o.id = s.orgao
						left join tab_pessoa p on p.id = o.func_id
						left join tab_usuario u on lpad(u.matricula,6,'0') = lpad(p.matricula,6,'0') and u.status = 'A'
						where s.id = $id
						order by p.nome ASC");
        
        $user = DB::select("select
    					 u.id
    					,u.nome
    					,case when u.avatar is null then 'images/avatar/avatar_1.png' else u.avatar end avatar
    					,u.matricula
                        ,u.login
                        ,0 secretario
    					from tab_usuario u
                        where u.setor = $id and u.status = 'A'
						order by u.nome ASC");
        foreach ($sec as $row){
            $dados[] = $row;
        }
        foreach ($user as $row){
            $dados[] = $row;
        }        
        return $dados;
    }
    //--------------------------------------------------------------------------------
    public static function selectListNiver( $orderBy=null, $where=null ){
        return DB::select("select 
                            p.id
                            , trim(p.nome) as nome
                            , p.matricula
							, concat(LPAD(DAY(p.data_nasc),'2','0'),'/',LPAD(MONTH(p.data_nasc),'2','0')) as data
							, DAY(p.data_nasc) as dia
                            , FLOOR(DATEDIFF(NOW(), p.data_nasc) / 365) AS idade
							from tab_pessoa p".
            ( ($where)? ' where '.$where:'').
            ( ($orderBy) ? ' order by '.$orderBy:''));
    }
    //--------------------------------------------------------------------------------  
    
}

