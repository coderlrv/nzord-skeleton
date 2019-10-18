<?php
namespace Modulos\System;

use Modulos\System\Models\Feriado;
use Modulos\System\Models\Parametro;
use NZord\Controller\Controller;
use NZord\Controller\DataTables;
use NZord\Responses\ModalResponse;
use Slim\Http\Request;
use Slim\Http\Response;

class FeriadoController extends Controller
{
    // --------------------------------------------------------------------------------
    public function index(Request $request, Response $response, $args = null)
    {
        $feriado = [];
        try {
            $feriado = Feriado::selectGrid();
            $feriado->orderBy('id', 'DESC');
            $feriado->limit(1);
        } catch (\Exception $ex) {
            $this->app->flash->addMessage('error', $ex->getMessage());
        }
        return $this->app->view->render($response, '@twMod/feriado.twig', [
            'act'     => 'index',
            'feriado' => $feriado->toArray(),
        ]);
    }
    // --------------------------------------------------------------------------------
    public function gridJson(Request $request, Response $response, $args = null)
    {
        $sql = Feriado::selectGrid();
        $sql->orderBy('id', 'DESC');
        echo json_encode(DataTables::simple($_REQUEST, $sql->toSql()), false);
    }
    // --------------------------------------------------------------------------------
    public function form(Request $request, Response $response, $args = null)
    {
        $id      = $args['id'];
        $feriado = [];
        try {
            $feriado = Feriado::find($id);
        } catch (\Exception $ex) {
            $this->app->flash->addMessage('error', $ex->getMessage());
        }
        $tipo = array(
            array('id' => 'Municipal', 'nome' => 'Municipal'),
            array('id' => 'Nacional', 'nome' => 'Nacional'),
            array('id' => 'Estadual', 'nome' => 'Estadual'),
            array('id' => 'PontoFacultativo', 'nome' => 'PontoFacultativo'),
        );

        return [
            'act'     => 'form',
            'feriado' => $feriado,
            'tipo'    => $tipo,
        ];
    }
    //---------------------------------------------------------------------------------
    public function save($request, $response, $args = null)
    {
        $post = $request->getParsedBody();
        if (isset($post['id'])) {
            $save = Feriado::find($post['id']);
        } else {
            $save = new Feriado();
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
    public function importFeriado(Request $request, Response $response, $args = null)
    {
        $url = Parametro::get('urlWebServicePontoFeriado');
        echo '<div class="col-xs-12 well" style=" height: 420px; overflow: auto; background-color: #fff;">';
        $ano   = date('Y');
        $dados = utf8_decode(file_get_contents("$url&start=" . $ano . "-01-01&end=" . $ano . "-12-31"));
        $dados = json_decode($dados, true);
        $t     = 0;
        $i     = 0;
        $a     = 0;
        foreach ($dados as $r) {
            $t++;
            $data  = $r['data'];
            $desc  = $r['descricao'];
            $dados = Feriado::where('data', $data)->get();
            if (@$dados[0]->id != null) {
                $a++;
                $fer = Feriado::find($dados[0]->id);
            } else {
                $i++;
                $fer = new Feriado();
            }
            $insert = array('data' => $data, 'descricao' => $desc);
            $fer->fill($insert);
            $fer->save();
            echo '<div class="col-xs-3 bg-info" style="min-height:70px;"><b>' . $data . '</b> | ' . $desc . '</div>';
        }
        echo '</div>';
        echo 'Total de registros: <b>' . $t . '</b> | Registros Atualizados: <b>' . $a . '</b> | Registros Inseridos: <b>' . $i . '</b>';

    }
    // --------------------------------------------------------------------------------
}
