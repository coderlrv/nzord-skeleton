<?php
namespace Modulos\System;

use Modulos\System\Models\Basica;
use NZord\Controller\Controller;
use NZord\Controller\DataTables;
use NZord\Responses\ModalResponse;
use Slim\Http\Request;
use Slim\Http\Response;

class BasicaController extends Controller
{
    // --------------------------------------------------------------------------------
    public function index(Request $request, Response $response, $args = null)
    {
        $basica = [];
        try {
            $basica = Basica::selectGrid();
            $basica->orderBy('id', 'DESC');
            $basica->limit(1);
        } catch (\Exception $ex) {
            $this->app->flash->addMessage('error', $ex->getMessage());
        }
        return $this->app->view->render($response, '@twMod/basica.twig', [
            'act'    => 'index',
            'basica' => $basica->toArray(),
        ]);
    }
    // --------------------------------------------------------------------------------
    public function gridJson(Request $request, Response $response, $args = null)
    {
        $sql = Basica::selectGrid();
        $sql->orderBy('b.id', 'DESC');
        echo json_encode(DataTables::simple($_REQUEST, $sql->toSql()), false);
    }
    // --------------------------------------------------------------------------------
    public function form(Request $request, Response $response, $args = null)
    {
        $id     = $args['id'];
        $basica = [];
        try {
            $basica = Basica::find($id);
        } catch (\Exception $ex) {
            $this->app->flash->addMessage('error', $ex->getMessage());
        }
        $tabela = Basica::selectListTabela();
        $status = array(
            array('id' => 'A', 'nome' => 'Ativo'),
            array('id' => 'I', 'nome' => 'Inativo'),
        );

        return [
            'act'    => 'form',
            'basica' => $basica,
            'status' => $status,
            'tabela' => $tabela,
        ];
    }
    //-------------------------------------------------------------------------------------------------
    public function save($request, $response, $args = null)
    {
        $post = $request->getParsedBody();
        if (isset($post['id'])) {
            $save = Basica::find($post['id']);
        } else {
            $save = new Basica();
        }
        Logger($post);
        $save->fill($post);
        
        try {
            $save->save();
            return $this->responseJson('Cadastra realizado com sucesso!');
        } catch (\Exception $ex) {
            return $this->responseJson('Erro ao Gravar', 500, $ex);
        }
    }
    // --------------------------------------------------------------------------------
}
