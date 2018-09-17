<?php
namespace Modulos\System;

use Illuminate\Database\Capsule\Manager as DB;
use Modulos\System\Models\Perfil;
use Modulos\System\Models\Sessao;
use Modulos\System\Models\Usuario;
use NZord\Controller\Controller;
use Sinergi\BrowserDetector\Browser;
use Slim\Http\Request;
use Slim\Http\Response;

class SuasessaoController extends Controller
{
    //--------------------------------------------------------------------------------
    public function index(Request $request, Response $response)
    {
        $sesId  = $this->app->session->get('sessao');
        $user   = $this->app->session->get('user');
        $sessao = [];
        try {
            $sessao             = Sessao::selectGrid('r.id DESC', 'r.id = ' . $sesId);
            $user               = Usuario::find($user);
            $browser            = new Browser($sessao[0]->browser);
            $sessao[0]->browser = $browser->getName() . ' - ' . $browser->getVersion();
        } catch (\Exception $ex) {
            $this->app->flash->addMessage('error', $ex->getMessage());
        }
        return $this->app->view->render($response, '@twMod/suasessao.twig', ['sessao' => $sessao[0], 'usuario' => $user]);
    }
    //--------------------------------------------------------------------------------
    public function edit(Request $request, Response $response)
    {
        $id = $request->getAttribute('id');

        $genero = array(array('id' => 'F', 'nome' => 'Femenino'), array('id' => 'M', 'nome' => 'Masculino'));
        try {
            $usuario = Usuario::find($id);
            $perfil  = Perfil::find($usuario->perfil);
        } catch (\Exception $ex) {
            $this->app->flash->addMessage('error', $ex->getMessage());
        }
        return $this->app->view->render($response, '@twMod/suasessao.twig', ['usuario' => $usuario, 'genero' => $genero, 'act' => 'edit', 'perfil' => $perfil->nome]);
    }
    //--------------------------------------------------------------------------------
    public function del(Request $request, Response $response, $args = null)
    {
        if ($args) {
            $id = $args['id'];
        } else {
            $id = $request->getAttribute('id');
        }
        $flash              = new \Slim\Flash\Messages();
        $sessao             = Sessao::find($id);
        $sessao->data_encer = date('Y-m-d H:i:s');
        $sessao->status     = 'E';
        if ($sessao->save()) {
            $flash->addMessage('success', 'Sessão Finalizada com Sucesso!');
        } else {
            $flash->addMessage('error', 'Falahar ao finalizar sessão!');
        }
        echo '<script>
            window.location.reload(true);
        </script>';
    }
    //--------------------------------------------------------------------------------
    public static function geralFinalSessao()
    {
        $flash = new \Slim\Flash\Messages();
        try {
            $dados = DB::select('update reg_sessao set
								data_encer = now()
								,status = "I"
								where status = "A" and data <= DATE_SUB(now(), INTERVAL 3 HOUR)
								order by id DESC');
        } catch (\Exception $ex) {
            $flash->addMessage('error', $ex->getMessage());
            echo 'Erro no sql...';
        }

        if (@$dados) {
            $flash->addMessage('success', 'Sessão Finalizada com Sucesso!');
        } else {
            $flash->addMessage('error', 'Falahar ao finalizar sessão!');
        }

        echo '<script>
            window.location.reload(true);
        </script>';
    }
    //--------------------------------------------------------------------------------
    public function detAcesso(Request $request, Response $response)
    {
        $sessao = [];
        try {
            $sessao = Sessao::selectDetAcesso('timestamp DESC');
        } catch (\Exception $ex) {
            $this->app->flash->addMessage('error', $ex->getMessage());
        }
        return ['sessao' => $sessao];
    }
    //--------------------------------------------------------------------------------
    public function save($request, $response, $args)
    {
        $directory = './files/avatar';
        $data      = $request->getParsedBody();
        if (isset($data['id'])) {
            $usuario = Usuario::find($data['id']);
            if (!$usuario) {
                $this->app->flash->addMessage('error', "Usuario não encontrato!");
                return $response->withStatus(200)->withHeader('Location', $this->app['app']['url'] . 'system/usuario');
            }
        } else {
            $usuario = new Usuario();
        }
        $uploadedFiles = $request->getUploadedFiles();
        $uploadedFile  = $uploadedFiles['avatar'];
        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $filename        = moveUploadedFile($directory, $uploadedFile);
            $usuario->avatar = $directory . '/' . $filename;
            $response->write('uploaded ' . $filename . '<br/>');
        }

        $keys = array_keys($data);
        foreach ($keys as $k => $v) {
            $method = $v;
            $campo  = "\$data['$v']";
            eval("\$usuario->" . $method . ' = ' . $campo . ";");
        }

        if ($usuario->save()) {
            $this->app->flash->addMessage('success', "Cadastra realizado com sucesso.");
            return $response->withRedirect($this->app['app']['url'] . 'app/system/suasessao');
        }

        $this->app->flash->addMessage('error', "Erro ao cadastrar.");
        return $response->withRedirect($this->app['app']['url'] . 'app/system/suasessao');

    }
    //--------------------------------------------------------------------------------
}
