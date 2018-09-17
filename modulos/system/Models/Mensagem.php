<?php
/**
 * Class Mensagem | modulos/system/Models/Mensagem.php
 *
 */
namespace Modulos\System\Models;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model;

class Mensagem extends Model{
    
    protected $table = 'mensagem';
    
    protected $primaryKey='id';

    public $timestamps = false;

    //--------------------------------------------------------------------------------
    public static function selectGrid( $orderBy=null, $where=null ){
        return DB::select("select
								 m.id
								,m.data
								,concat(u.id,' - ',COALESCE(u.nome,u.login)) as de
								,m.assunto as assunto
								,(select count(*)
									from mensagem_anexos where mensagem = m.id ) as anexo
								,t.span tipo
								,c.span sigilo
								,x.status
								,u.avatar
								,REPLACE(SUBSTRING(m.texto,1,43),'<p>','') as texto
								from mensagem m
				inner join mensagem_user x on m.id = x.mensagem
				inner join tab_usuario u on u.id = m.remetente
				inner join mensagem_tipo t on t.id = m.tipo
				inner join mensagem_classe c on c.id = m.sigilo".
            ( ($where)? ' where '.$where:'').
            ( ($orderBy) ? ' order by '.$orderBy:''));
    }
    //--------------------------------------------------------------------------------
    public static function selectRead( $orderBy=null, $where=null ){
        return DB::select("select
								 m.id
								,m.data
								,concat(u.id,' - ',COALESCE(u.nome,u.login)) as de
                                ,( select GROUP_CONCAT(
    								concat(u.id, ' - ', COALESCE(u.nome,u.login)) ) AS destino
    								 from mensagem_user n
    								 inner join tab_usuario u on u.id = n.destino where n.mensagem = m.id
    												)  as para
								,m.assunto as assunto
								,(select group_concat( concat(id,';',nome) )
									from mensagem_anexos where mensagem = m.id ) as anexo
								,t.span tipo
								,c.span sigilo
								,x.status
								,u.avatar
								,m.texto as texto
								from mensagem m
				inner join mensagem_user x on m.id = x.mensagem
				inner join tab_usuario u on u.id = m.remetente
				inner join mensagem_tipo t on t.id = m.tipo
				inner join mensagem_classe c on c.id = m.sigilo".
            ( ($where)? ' where '.$where:'').
            ( ($orderBy) ? ' order by '.$orderBy:''));
    }
    //--------------------------------------------------------------------------------
    public static function selectQtde( $orderBy=null, $where=null ){
        return DB::select("select
                                count(*) as msgs,
                                sum(case when x.status = 0 then 1 else 0 end) as nlida,
                                (select count(*) from mensagem where remetente = x.destino) as enviado,
                                (select count(*) from tab_usuario where status = 'A' ) as contatos
                from mensagem m
                left join mensagem_user x on m.id = x.mensagem".
            ( ($where)? ' where '.$where:'').
            ( ($orderBy) ? ' order by '.$orderBy:''));
    }
    //--------------------------------------------------------------------------------
    public static function selectGridEnv( $orderBy=null, $where=null ){
        return DB::select("select
								 m.id
								,m.data
								,( select GROUP_CONCAT(
								concat(u.id, ' - ', COALESCE(u.nome,u.login)) ) AS destino
								 from mensagem_user n
								 inner join tab_usuario u on u.id = n.destino where n.mensagem = m.id
												)  as para
								,m.assunto as assunto
								,( select count(*)
									from mensagem_anexos where mensagem = m.id ) as anexo
								,t.span tipo
								,c.span sigilo
								,x.status
								,u.avatar
								,REPLACE(SUBSTRING(m.texto,1,43),'<p>','') as texto
								from mensagem m
                inner join mensagem_user x on m.id = x.mensagem
				inner join tab_usuario u on u.id = m.remetente
				inner join mensagem_tipo t on t.id = m.tipo
				inner join mensagem_classe c on c.id = m.sigilo".
            ( ($where)? ' where '.$where:'').
            ( ($orderBy) ? ' order by '.$orderBy:''));
    }
    //--------------------------------------------------------------------------------
    public static function selectContatos( $orderBy=null, $where=null ){
        return DB::select("select
                                u.id
                                ,concat(u.perfil,' - ',p.nome) as perfil
                                ,o.sigla as dpto
                                ,COALESCE(concat(s.id,' - ',s.setor),e.nome,'N/Definido') as setor
                                ,concat(LPAD(matricula,5,'0'),' - ',u.nome) as nome
                                ,u.login
                                ,case
                                when u.status = 'A' then '<span class=\"label label-success\" style=\"font-size: 90%\">Ativo</span>'
                                when u.status = 'C' then '<span class=\"label label-danger\" style=\"font-size: 90%\">Cancelado</span>'
                                else '<span class=\"label label-warning\" style=\"font-size: 90%\">Inativo</span>' end as status
                                ,(select max(data) from reg_sessao where user_id = u.id) as acesso
                                , case
                                when r.id is not null then '<span class=\"label label-success\" style=\"font-size: 90%\">SIM</span>'
                                else '<span class=\"label label-default\" style=\"font-size: 90%\">Não</span>' end as online
                                , case when u.avatar is null then 'images/avatar/avatar_1.png' else u.avatar end avatar
                        from tab_usuario u
                        left join tab_orgao o on o.id = u.dpto
                        left join tab_setor s on s.id = u.setor
                        left join tab_perfil p on p.id_perfil = u.perfil
                        left join n_equipe e on e.id = u.empresa
                        left join reg_sessao r on u.id = r.user_id and r.status = 'A'".
            ( ($where)? ' where '.$where:'').
            ( ($orderBy) ? ' order by '.$orderBy:''));
    }
    //--------------------------------------------------------------------------------    
    /**
     *  Envia messagem padrão
     *
     * @param $remetente - Quem enviou
     * @param $destino - Para quem a messagem
     * @param $modelo - Modelo da messagem
     * @param array $var - Valores do modelo
     * @param integer $perfil
     * @return void
     */
    public static function sendMsgDefault($remetente,$destino,$modelo=null,$var=array(),$perfil=3){
        $mod = TabMensagem::find($modelo);
        $texto = $mod->detalhe;
        $assunto = $mod->titulo;
        
        $key = array_keys($var);
        foreach( $key as $k => $v){
            $busca[$k] = '{'.strtoupper($v).'}';
        }
        $texto = str_replace($busca, $var, $texto);
        
        $msg = new Mensagem();
        $msg->remetente = $remetente;
        $msg->destino = $destino;
        $msg->assunto = $assunto;
        $msg->texto = $texto;
        $msg->data = date('Y-m-d H:i:s');
        $msg->perfil = $perfil;

        if( $msg->save() ){
            $newId = $msg->id;

            if( $newId != null ){
            
                $destino = explode(',',$destino);
                foreach ( $destino as $destUser ) {

                    $muser = new MensagemUser();
                    $muser->mensagem = $newId;
                    $muser->remetente = $remetente;
                    $muser->destino = $destUser;
                    $muser->status = 0;
                    $muser->data = date('Y-m-d H:i:s');
                    $muser->save();
                }
            }
        }
    }
    //--------------------------------------------------------------------------------
}