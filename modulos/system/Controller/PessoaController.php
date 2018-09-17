<?php
namespace Modulos\System;

use Modulos\System\Models\Basica;
use Modulos\System\Models\Parametro;
use Modulos\System\Models\Pessoa;
use NZord\Controller\Controller;
use NZord\Controller\DataTables;
use Slim\Http\Request;
use Slim\Http\Response;

class PessoaController extends Controller
{
    // --------------------------------------------------------------------------------
    public function index(Request $request, Response $response, $args = null)
    {
        $pessoa = [];
        try {
            $pessoa = Pessoa::selectGrid();
            $pessoa->orderBy('id', 'DESC');
            $pessoa->limit(1);
        } catch (\Exception $ex) {
            $this->app->flash->addMessage('error', $ex->getMessage());
        }
        return $this->app->view->render($response, '@twMod/pessoa.twig', [
            'act'    => 'index',
            'pessoa' => $pessoa->toArray(),
        ]);
    }
    // --------------------------------------------------------------------------------
    public function gridJson(Request $request, Response $response, $args = null)
    {
        $sql = Pessoa::selectGrid();
        $sql->orderBy('id', 'DESC');
        echo json_encode(DataTables::simple($_REQUEST, $sql->toSql()), false);
    }
    // --------------------------------------------------------------------------------
    public function form(Request $request, Response $response, $args = null)
    {
        $id     = $args['id'];
        $pessoa = [];
        try {
            $pessoa = Pessoa::find($id);
        } catch (\Exception $ex) {
            $this->app->flash->addMessage('error', $ex->getMessage());
        }
        $ecivil = Basica::getValues('E_civil');
        $status = array(
            array('id' => 'A', 'nome' => 'Ativo'),
            array('id' => 'I', 'nome' => 'Inativo'),
        );
        $func = array(
            array('id' => '1', 'nome' => 'Sim'),
            array('id' => '0', 'nome' => 'Nao'),
        );

        return [
            'act'    => 'form',
            'pessoa' => $pessoa,
            'status' => $status,
            'func'   => $func,
            'ecivil' => $ecivil,
        ];
    }
    //---------------------------------------------------------------------------------
    public function save($request, $response, $args = null)
    {
        $post = $request->getParsedBody();
        if (isset($post['id'])) {
            $save = Pessoa::find($post['id']);
        } else {
            $save = new Pessoa();
        }
        Logger($post);
        $save->fill($post);
        try {
            $save->save();

            return $this->responseJson('Cadastro realizado com sucesso!');
        } catch (\Exception $ex) {
            return $this->responseJson('Erro ao Gravar', 500, $ex);
        }
    }
    // --------------------------------------------------------------------------------
    public function importPes(Request $request, Response $response, $args = null)
    {
        $url = Parametro::get('urlWebServiceRhGer');
        echo 'Total de Registros no banco desmarcados como Func <b>' . Pessoa::updateFuncPessoa() . '</b><br>';
        echo '<div class="col-xs-12 well" style=" height: 460px; overflow: auto; background-color: #fff;">';
        $dados = utf8_decode(file_get_contents("$url"));
        $dados = json_decode($dados, true);
        $r     = 0;
        $i     = 0;
        $a     = 0;
        foreach ($dados as $e) {
            $r++;
            $ca = Pessoa::selectAll(null, 'cpf_cnpj = "' . $e['CPF'] . '"');
            if (@$ca[0]->id != null) {
                $pes = Pessoa::find($ca[0]->id);
                $a++;
                $altera = array('nome' => $e['NOME']
                    , 'matricula' => $e['REGISTRO']
                    , 'documento' => $e['DOCUMENTO']
                    , 'data_nasc' => $e['DATA_NASC']
                    , 'celular' => $e['CELULAR']
                    , 'mail' => $e['MAIL']
                    , 'func' => 1);
                $pes->fill($altera);
                $pes->save();
                echo '<span class="col-xs-6">Existe ID: ' . $ca[0]->id . ' | ' . $ca[0]->matricula . ' - ' . $e['REGISTRO'] . ' | ' . $ca[0]->nome . ' | ' . $ca[0]->func . '</span>';
            } else {
                $pes = new Pessoa();
                $i++;
                $ecivil = Basica::where('tabela', 'E_Civil')->where('valor', 'like', '%' . $e['ESTADOCIVIL'] . '%')->get();
                if ($ecivil) {
                    $ecivil = $ecivil[0]['id'];
                } else {
                    $ecivil = 48;
                }
                $insert = array('matricula' => $e['REGISTRO']
                    , 'nome' => $e['NOME']
                    , 'cpf_cnpj' => $e['CPF']
                    , 'documento' => $e['DOCUMENTO']
                    , 'data_nasc' => $e['DATA_NASC']
                    , 'ecivil' => $ecivil
                    , 'telefone' => $e['TELEFONE']
                    , 'celular' => $e['CELULAR']
                    , 'mail' => $e['MAIL']
                    , 'func' => 1
                    , 'status' => 'A'
                    , 'user_id' => $this->app->session->get('user')
                    , 'ip_user' => $this->app->session->get('remoteIp')
                    , 'criado' => date('Y-m-d h:i:s'));
                $pes->fill($insert);
                $pes->save();
                echo '<span class="bg-success col-xs-6">Registrado inserido no banco: ' . $e['REGISTRO'] . ' - ' . $e['NOME'] . ' | ' . $e['CPF'] . '</span>';
            }
            unset($ca);
        }
        echo '</div>';
        echo 'Total de registros: <b>' . $r . '</b> | Registros Atualizados: <b>' . $a . '</b> | Registros Inseridos: <b>' . $i . '</b>';

    }
}
