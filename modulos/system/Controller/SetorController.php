<?php
namespace Modulos\System;

use Modulos\System\Models\Dpto;
use Modulos\System\Models\Setor;
use Modulos\System\Models\Unidade;
use Modulos\System\Models\Usuario;
use NZord\Controller\Controller;
use NZord\Controller\DataTables;
use Slim\Http\Request;
use Slim\Http\Response;

class SetorController extends Controller
{
    //--------------------------------------------------------------------------------
    public function index(Request $request, Response $response)
    {
        $setores = [];
        try {
            $setores = Setor::selectGrid('id ASC limit 1');
        } catch (\Exception $ex) {
            $this->app->flash->addMessage('error', $ex->getMessage());
        }
        return $this->app->view->render($response, '@twMod/setor.twig', ['setores' => $setores]);
    }
    //--------------------------------------------------------------------------------
    public function gridJson(Request $request, Response $response, $args = null)
    {
        $where = null;
        $sql   = "select
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
        		left join tab_status st on coalesce(st.codigo,st.code) = s.status";

        if ($request->getQueryParam('draw') != 0) {
            $sql .= ($where) ? ' where ' . $where : '';
        }
        echo json_encode(DataTables::simple($_REQUEST, $sql), false);
    }
    //--------------------------------------------------------------------------------
    public function form(Request $request, Response $response, $args = null)
    {
        if ($args) {
            $id = $args['id'];
        } else {
            $id = $request->getAttribute('id');
        }
        $setor       = Setor::find($id);
        $dpto        = Dpto::selectList(null, ' status = "A"');
        $unidade     = Unidade::selectList(null, 'status = "A"');
        $status      = array(array('id' => 'A', 'nome' => 'Ativo'), array('id' => 'I', 'nome' => 'Inativo'));
        $responsavel = Usuario::where('status', 'A')->get();

        return ['setor' => $setor, 'status' => $status, 'dpto' => $dpto, 'unidade' => $unidade, 'responsavel' => $responsavel];
    }
    //--------------------------------------------------------------------------------
    public function save($request, $response, $args)
    {
        $data = $request->getParsedBody();
        if (isset($data['id'])) {
            $save = Setor::find($data['id']);
        } else {
            $save = new Setor();
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
}
