<?php
namespace Modulos\System;

use Modulos\System\Models\Dpto;
use Modulos\System\Models\Perfil;
use Modulos\System\Models\Pessoa;
use Modulos\System\Models\Setor;
use Modulos\System\Models\Usuario;
use NZord\Controller\Controller;
use NZord\Controller\DataTables;
use NZord\Helpers\Validations\Validator as V;
use NZord\Responses\AlertModalResponse;
use Slim\Http\Request;
use Slim\Http\Response;

class UsuarioController extends Controller
{
    //--------------------------------------------------------------------------------
    public function index(Request $request, Response $response)
    {
        $usuarios = Usuario::selectGrid()->orderBy('u.id', 'desc')->limit(1)->toArray();
        return $this->app->view->render($response, '@twMod/usuario.twig', ['usuarios' => $usuarios]);
    }

    //--------------------------------------------------------------------------------
    public function gridJson(Request $request, Response $response, $args = null)
    {
        $this->usuarios = Usuario::selectGrid()->orderBy('u.id', 'desc')->limit(1000);
        return $response->withJson(DataTables::simple($_REQUEST, $this->usuarios->toSql()));
    }
    //--------------------------------------------------------------------------------
    public function form(Request $request, Response $response, $args = null)
    {
        $id   = getParam($args['id']);
        $edit = false;

        if ($id) {
            $usuario = Usuario::find($id);
            if (!$usuario) {
                return new AlertModalResponse('Error', 'Não foi encontrado usuário!');
            }

            $edit = true;
        } else {
            $usuario = new Usuario();
        }

        $status = [
            ['id' => 'A', 'nome' => 'Ativo'],
            ['id' => 'I', 'nome' => 'Inativo'],
            ['id' => 'C', 'nome' => 'Cancelado'],
        ];

        $perfis = Perfil::selectList(null, 'status = "A"');
        $dpto   = Dpto::selectList(null, 'status = "A"');
        $setor  = Setor::selectList()->where('status', 'A')->toArray();
        $func   = Pessoa::selectListMat('nome ASC', 'func = 1 and status = "A"');
        $pessoa = Pessoa::where('matricula', $usuario['matricula'])->first();

        return [
            'usuario' => $usuario,
            'perfis'  => $perfis,
            'dptos'   => $dpto,
            'setores' => $setor,
            'funcs'   => $func,
            'status'  => $status,
            'pessoa'  => $pessoa,
            'edit'    => $edit,
        ];
    }
    //--------------------------------------------------------------------------------
    public function det(Request $request, Response $response, $args = null)
    {
        if ($args) {
            $id = $args['id'];
        } else {
            $id = $request->getAttribute('id');
        }
        $parametro = Usuario::find($id);
        $status    = array(array('id' => '1', 'nome' => 'Ativo'), array('id' => '0', 'nome' => 'Inativo'));

        return ['param' => $parametro, 'status' => $status];
    }
    //--------------------------------------------------------------------------------
    public function save($request, $response, $args)
    {
        $data = $request->getParsedBody();
        Logger($data);

        //Regras geral
        $rules = [
            'perfil'    => V::notBlank()->numeric()->setName('Perfil'),
            'dpto'      => V::notBlank()->numeric()->setName('Departamento'),
            'setor'     => V::notBlank()->numeric()->setName('Setor'),
            'matricula' => V::notBlank()->numeric()->setName('Matricula'),
            'nome'      => V::notBlank()->setName('Nome'),
            'login'     => V::notBlank()->setName('Login'),
            'status'    => V::notBlank()->in(['A', 'I', 'C'])->setName('Status'),
        ];

        $v = $this->app->validator->validate($request, $rules);
        if (!$v->isValid()) {
            return $v->responseJsonErrors();
        }

        try {
            if ($data['id']) {
                $usuario = Usuario::find($data['id']);

                if (!$usuario) {
                    return $this->responseJson('Usuário não foi encontrado!', 400);
                }

            } else {
                $usuario = new Usuario();
            }

            $usuario->fill($data);

            if (isset($data['senha']) && !empty($data['senha'])) {
                $usuario->senha = md5($data['senha']);
            }
            $usuario->save();

            return $this->responseJson('Usuário salvo com sucesso!');
        } catch (\Exception $ex) {
            return $this->responseJson('Erro ao gravar', 500, $ex);
        }
    }
    //--------------------------------------------------------------------------------
    // Buscar usuario por Setores
    public function select(Request $request, Response $response, $args = null)
    {
        $session = $request->getAttribute('session');
        $q       = $request->getQueryParam('q');
        parse_str($q, $params);

        if ($params['idSetor']) {
            $usuario = Usuario::selectPorSetor($session->get('user'), $params['idSetor']);
        } else {
            $usuario = [];
        }

        return $response->withHeader('Content-Type', 'application/json')->withJson($usuario, 200);
    }
}
