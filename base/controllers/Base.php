<?php
namespace Base\Controller;

use Slim\Http\Response;
use Slim\Http\Request;

class BaseController extends \NZord\Controller\Controller
{   
    /**
     * Rota principal
     *
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function index(Request $request,Response $response){
        $this->session->delete('s_pagina');

        $setor     = \Modulos\System\Models\Setor::where('id', '=', $this->session->get('userSetor'))->where('detalhe', '<>', '""')->get();
        $inst      = \Modulos\System\Models\Institucional::get();
        $setorDesc = \Modulos\System\Models\Setor::selectOrgaoSetor(null, 's.id = ' . $this->session->get('userSetor'));
        $pessoas   = \Modulos\System\Models\Usuario::selectUserForSetor($this->session->get('userSetor'));
        $birthday  = \Modulos\System\Models\Usuario::selectListNiver('dia ASC', "p.func = 1 and p.status = \"A\" and MONTH(p.data_nasc) = MONTH(CURDATE())");
        $msgBday   = \Modulos\System\Models\Parametro::where('nome', 'msgDiaAniversario')->get();
    
        return $this->view->render($response, 'base.html.twig', array(
            'setor'     => $setor,
            'inst'      => $inst,
            'setorDesc' => $setorDesc[0],
            'pessoas'   => $pessoas,
            'birthday'  => $birthday,
            'msgBday'   => $msgBday[0],
        ));
    }
}
