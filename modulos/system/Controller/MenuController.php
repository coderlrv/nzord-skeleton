<?php
namespace Modulos\System;

use NZord\Controller\Controller;
use Modulos\System\Models\Menu;
use Slim\Http\Request;
use Slim\Http\Response;

class MenuController extends Controller
{
    //--------------------------------------------------------------------------------
    public function getMenuAccess(Request $request, Response $response)
    {
        $perfil = $this->app->session->get('userPerfil');
        $menu   = [];
        $whereM = null;
        if ($perfil != 1) {
            $whereM = 'and menuId in (127,128)';
        }
        try {
            $menu = Menu::selectAcesPerfil('menuOrder ASC', 'id_perfil = ' . $perfil . ' and image <> "" and link is not null ' . $whereM);
        } catch (\Exception $ex) {
            $this->app->flash->addMessage('error', $ex->getMessage());
        }
        return $menu;
    }
    //--------------------------------------------------------------------------------
    public function getMenuArr(Request $request, Response $response)
    {
        $perfil = $this->app->session->get('userPerfil');
        $arMenu = [];
        $arSub  = [];
        $menu   = Menu::selectAcesPerfil('menuOrder ASC', "p.id_perfil = $perfil and (menuLink = '' or menuLink is null) and menuidpai = 0");
        foreach ($menu as $mnu) {
            $arMenu[$mnu->id] = array('id' => $mnu->id
                , 'superior' => $mnu->superior
                , 'link' => $mnu->link
                , 'nome' => utf8_decode($mnu->nome)
                , 'divider' => $mnu->divider
                , 'hint' => $mnu->hint
                , 'image' => $mnu->image,
            );
            $dados = Menu::selectMenuAcesso('menuOrder ASC', "p.id_perfil = $perfil and menuidpai = " . $mnu->id);
            $arSub = [];
            foreach ($dados as $row) {
                $arSub[$row->id] = array('id' => $row->id
                    , 'nome' => utf8_decode($row->nome)
                    , 'order' => $row->morder
                    , 'link' => $row->link
                    , 'hint' => $row->hint
                    , 'image' => $row->image
                    , 'divider' => $row->divider,
                );
                $arSub3 = [];
                if ($row->link == '') {
                    $sDados = Menu::selectMenuAcesso('menuOrder ASC', "p.id_perfil = $perfil and menuidpai = " . $row->id);
                    foreach ($sDados as $srow) {
                        $arSub3[] = array('id' => $srow->id
                            , 'nome' => $srow->nome
                            , 'order' => $srow->morder
                            , 'link' => $srow->link
                            , 'hint' => $srow->hint
                            , 'image' => $srow->image
                            , 'divider' => $srow->divider,
                        );
                    }
                }
                $arSub[$row->id]['sub'] = $arSub3;
            }
            $arMenu[$mnu->id]['sub'] = $arSub;
        }
        return $arMenu;
    }

}
