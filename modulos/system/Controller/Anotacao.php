<?php
namespace Modulos\System;

use Modulos\System\Models\Anotacao;
use NZord\Controller\Controller;
use Slim\Http\Request;
use Slim\Http\Response;

class AnotacaoController extends Controller
{
    //--------------------------------------------------------------------------------
    public function index(Request $request, Response $response)
    {
        $userId = $this->app->session->get('user');
        $notes  = [];
        try {
            $notes = Anotacao::selectTodo('data DESC', 'user_id = ' . $userId);
        } catch (\Exception $ex) {
            $this->app->flash->addMessage('error', $ex->getMessage());
        }
        return ['notes' => $notes];
        //return $this->app->view->render($response, '@twMod/anotacao.twig',['notes'=>$notes]);
    }
    //--------------------------------------------------------------------------------
    public function form(Request $request, Response $response, $args = null)
    {
        $userId = $this->app->session->get('user');
        $notes  = null;
        $id     = @$args['id'];
        if ($id) {
            $notes = Anotacao::find($id);
        }
        $class = [array('id' => 'default', 'nome' => 'default')
            , array('id' => 'primary', 'nome' => 'primary')
            , array('id' => 'success', 'nome' => 'success')
            , array('id' => 'info', 'nome' => 'info')
            , array('id' => 'warning', 'nome' => 'warning')
            , array('id' => 'danger', 'nome' => 'danger')];

        return ['note' => $notes, 'class' => $class];
    }
    //--------------------------------------------------------------------------------
    public function save($request, $response, $args)
    {
        $data = $request->getParsedBody();
        if (isset($data['id'])) {
            $note = Anotacao::find($data['id']);
        } else {
            $note = new Anotacao();
        }

        $note->fill($data);
        try {
            $note->save();
            return $this->responseJson('Movimentação salva com sucesso!');
        } catch (\Exception $ex) {
            return $this->responseJson('Erro ao gravar', 500, $ex);
        }
    }
}
