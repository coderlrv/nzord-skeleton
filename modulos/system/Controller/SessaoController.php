<?php
namespace Modulos\System;

use Illuminate\Database\Capsule\Manager as DB;
use Modulos\System\Models\Sessao;
use Modulos\System\Models\Useronline;
use NZord\Controller\Controller;
use Sinergi\BrowserDetector\Browser;
use Slim\App as Slim;
use Slim\Http\Request;
use Slim\Http\Response;

class SessaoController extends Controller
{
    //--------------------------------------------------------------------------------
    public function index(Request $request, Response $response)
    {
        $sessao = [];
        try {
            $sessao = Sessao::selectGrid('r.id DESC', 'r.status = "A"');
            for ($i = 0; $i < count($sessao); $i++) {
                $browser             = new Browser($sessao[$i]->browser);
                $sessao[$i]->browser = $browser->getName() . ' - ' . $browser->getVersion();
            }
        } catch (\Exception $ex) {
            $this->app->flash->addMessage('error', $ex->getMessage());
        }
        return $this->app->view->render($response, '@twMod/sessao.twig', ['sessao' => $sessao]);
    }
    //--------------------------------------------------------------------------------
    public function sesonline(Request $request, Response $response, $next)
    {
        $timestamp = time();
        $timeout   = time() - 500;
        $post      = $request->getParsedBody();
        $sess      = $post['sess'];
        $pag       = $post['pag'];

        $limpa = Useronline::where('timestamp', '<', $timeout)->delete();
        if ($sess) {
            $uonline            = new Useronline();
            $uonline->timestamp = $timestamp;
            $uonline->sessao    = $sess;
            $uonline->arquivo   = $pag;
            $uonline->save();
            return $response->withJson(1);
        } else {
            return $response->withJson(0);
        }
    }
    //--------------------------------------------------------------------------------
    public function det(Request $request, Response $response)
    {
        $this->app->flash->addMessage('info', 'Detalhes do Usuario');
        $this->app->flash->addMessage('info', 'Detalhes do Usuario2');
        $this->edit($request, $response);
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
        }

        if (@$dados) {
            $flash->addMessage('success', 'Sessão Finalizada com Sucesso!');
        } else {
            $flash->addMessage('error', 'Falha ao finalizar sessão!');
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
}
