<?php
namespace Modulos\System;

use Illuminate\Database\Capsule\Manager as DB;
use Modulos\System\Models\Parametro;
use NZord\Controller\Controller;
use NZord\Controller\DataTables;
use Slim\Http\Request;
use Slim\Http\Response;

class ParametroController extends Controller
{
    //--------------------------------------------------------------------------------
    public function index(Request $request, Response $response)
    {
        $parametro = [];
        try {
            $parametro = DB::select("select p.id
                                ,p.nome
                                ,p.descricao
                                ,s.nome as status
                                from tab_parametro p
                                left join tab_status s on s.codigo = p.status");
        } catch (\Exception $ex) {
            $this->app->flash->addMessage('error', $ex->getMessage());
        }
        
        return $this->app->view->render($response, '@twMod/parametro.twig', ['parametro' => $parametro]);
    }
    //--------------------------------------------------------------------------------
    public function form(Request $request, Response $response, $args = null)
    {
        if ($args) {
            $id = $args['id'];
        } else {
            $id = $request->getAttribute('id');
        }
        $parametro = Parametro::find($id);
        $status    = array(array('id' => '1', 'nome' => 'Ativo'), array('id' => '0', 'nome' => 'Inativo'));

        return ['param' => $parametro, 'status' => $status];
    }
    //--------------------------------------------------------------------------------
    public function det(Request $request, Response $response, $args = null)
    {
        if ($args) {
            $id = $args['id'];
        } else {
            $id = $request->getAttribute('id');
        }
        $parametro = Parametro::find($id);
        $status    = array(array('id' => '1', 'nome' => 'Ativo'), array('id' => '0', 'nome' => 'Inativo'));

        return ['param' => $parametro, 'status' => $status];
    }
    //--------------------------------------------------------------------------------
    public function save($request, $response, $args)
    {
        $data = $request->getParsedBody();
        if (isset($data['id'])) {
            $save = Parametro::find($data['id']);
        } else {
            $save = new Parametro();
        }
        Logger($data);
        try {
            $save->fill($data);
            $save->save();
            return $this->responseJson('Sucesso');
        } catch (\Exception $ex) {
            return $this->responseJson('Erro ao Salvar', 500, $ex);
        }
    }
    //--------------------------------------------------------------------------------
    public function gridJson(Request $request, Response $response, $args = null)
    {
        $where = null;
        $sql   = "select
                p.id
                ,p.nome
                ,p.descricao
                ,s.nome as status
                from tab_parametro p
                left join tab_status s on s.codigo = p.status";

        if ($request->getQueryParam('draw') != 0) {
            $sql .= ($where) ? ' where ' . $where : '';
        }
        echo json_encode(DataTables::simple($_REQUEST, $sql), false);
    }
}
