<?php
namespace Modulos\System;

use Modulos\System\Models\Parametro;
use Modulos\System\Models\RelModelo;
use Modulos\System\Models\RelTipo;
use NZord\Controller\Controller;
use NZord\Controller\DataTables;
use Slim\Http\Request;
use Slim\Http\Response;

class ModeloController extends Controller
{
    // --------------------------------------------------------------------------------
    public function index(Request $request, Response $response, $args = null)
    {
        $modelos = RelModelo::selectGrid()->orderBy('m.id', 'desc')->limit(1)->toArray();

        return $this->app->view->render($response, '@twMod/relModelo.twig', [
            'act'    => 'index',
            'modelo' => $modelos,
        ]);
    }
    // --------------------------------------------------------------------------------
    public function gridJson(Request $request, Response $response, $args = null)
    {
        $modelos = RelModelo::selectGrid()->orderBy('m.id', 'desc')->limit(1000);
        return $response->withJson(DataTables::simple($_REQUEST, $modelos->toSql()));
    }
    // --------------------------------------------------------------------------------
    public function form(Request $request, Response $response, $args = null)
    {
        $id     = getParam($args['id']);
        $modelo = [];

        if ($id) {
            $modelo = RelModelo::find($id);
            if (!$modelo) {
                $this->app->flash->addMessage('error', 'Não foi encontrado modelo.');
            }
        }

        $headers = Parametro::where('nome', 'like', '%cabec%')->get();
        $footers = Parametro::where('nome', 'like', '%rodap%')->get();
        $tabela  = RelTipo::where('status', 'A')->get();
        $status  = [
            ['id' => '1', 'nome' => 'Ativo'],
            ['id' => '0', 'nome' => 'Inativo'],
        ];
        $rotacoes = [
            ['id' => '1', 'nome' => 'Retrato'],
            ['id' => '2', 'nome' => 'Paisagem'],
        ];

        return $this->app->view->render($response, '@twMod/relModelo.twig', [
            'act'      => $args['act'],
            'modelo'   => $modelo,
            'status'   => $status,
            'headers'  => $headers,
            'footers'  => $footers,
            'rotacoes' => $rotacoes,
            'tabela'   => $tabela,
        ]);
    } //-------------------------------------------------------------------------------------------------
    public function save($request, $response, $args = null)
    {
        $post = $request->getParsedBody();

        if (isset($post['id'])) {
            $report = RelModelo::find($post['id']);
            if (!$report) {
                echo 'Registro não encontrato!';
                exit();
            }
        } else {
            $report = new RelModelo();
        }

        unset($post['lstVariaveis']);
        unset($post['files']);
        $post['cod_sql'] = str_replace("'", '"', $post['cod_sql']);

        try {
            Logger($post);

            if (validaCodigoSql($post['cod_sql'])) {

                //Aplica null caso nao tenha seleção
                $post['cabecalho'] = $post['cabecalho'] ?: null;
                $post['rodape']    = $post['rodape'] ?: null;

                $report->fill($post);
                $report->save();

                $this->app->flash->addMessage('success', ['title' => 'Sucesso!', 'text' => "Cadastro realizado com sucesso."]);
                return $this->redirectToback();
            } else {
                $this->app->flash->addMessage('error', ['title' => 'Error!', 'text' => "Sql não é valido! Verifique se não possi comandos restritos."]);
                return $this->redirectToback();
            }
        } catch (\Exception $ex) {
            $this->app->flash->addMessage('error', ['title' => 'Error!', 'text' => "Erro ao cadastrar."]);
            return $this->redirectToback();
        }
    }
    // --------------------------------------------------------------------------------
}
