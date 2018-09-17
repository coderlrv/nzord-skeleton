<?php
namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;

class Menu extends Model{
    
    protected $table = 'n_menu';
    
    protected $primaryKey='menuId';

    public $timestamps = false;
    
    //--------------------------------------------------------------------------------
    public static function selectAcesPerfil( $orderBy=null, $where=null ){
        return DB::select("select
								 m.menuId as id
								,m.menuidpai as superior
								,coalesce(m.link,m.menulink) as link
								,concat(m.menuorder,'.',m.menunome) as nome
								,m.divider
								,m.externo
								,m.hint
								,m.image
								FROM n_menu m
								left join n_perfil_menu p on p.id_menu = m.menuId
								".
            ( ($where)? ' where '.$where:'').
            ( ($orderBy) ? ' order by '.$orderBy:''));
    }
    //--------------------------------------------------------------------------------
    public static function selectMenuAcesso( $orderBy=null, $where=null ){
        return DB::select("select
									m.menuId as id
									,m.menuNome as nome
									,coalesce(m.link,m.menuLink) as link
									,m.menuOrder as morder
									,m.hint
									,m.image
                                    ,m.divider
									from n_menu m
									left join n_perfil_menu p on p.id_menu = m.menuId
								".
            ( ($where)? ' where '.$where:'').
            ( ($orderBy) ? ' order by '.$orderBy:''));
    }
    //--------------------------------------------------------------------------------

}