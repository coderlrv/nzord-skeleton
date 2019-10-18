<?php
namespace Modulos\System;

use Modulos\System\Models\Mensagem;
use Modulos\System\Models\MensagemUser;
use Modulos\System\Models\TabMensagem;
use NZord\Controller\Controller;
use Slim\Http\Request;
use Slim\Http\Response;

class MensagemController extends Controller
{
    //--------------------------------------------------------------------------------
    public function index(Request $request, Response $response)
    {
        $mensagem = [];
        try {
            $mensagem = Mensagem::selectGrid('m.data DESC', ' x.destino = ' . $this->app->session->get('user'));
            $qtde     = Mensagem::selectQtde(null, ' x.destino = ' . $this->app->session->get('user'));
            $qtde     = (array) $qtde[0];
        } catch (\Exception $ex) {
            $this->app->flash->addMessage('error', $ex->getMessage());
        }
        return $this->app->view->render($response, '@twMod/mailbox.twig', ['mensagem' => $mensagem, 'qtde' => $qtde]);
    }
    //--------------------------------------------------------------------------------
    public function enviados(Request $request, Response $response)
    {
        $mensagem = [];
        try {
            $mensagem = Mensagem::selectGridEnv('m.data DESC', ' m.remetente = ' . $this->app->session->get('user'));
            $qtde     = Mensagem::selectQtde(null, ' x.destino = ' . $this->app->session->get('user'));
            $qtde     = (array) $qtde[0];
        } catch (\Exception $ex) {
            $this->app->flash->addMessage('error', $ex->getMessage());
        }
        return $this->app->view->render($response, '@twMod/mailSendbox.twig', ['mensagem' => $mensagem, 'qtde' => $qtde]);
    }
    //--------------------------------------------------------------------------------
    public function contato(Request $request, Response $response)
    {
        $contato = [];
        try {
            $contato = Mensagem::selectContatos('u.login ASC', ' u.status = "A"');
            $qtde    = Mensagem::selectQtde(null, ' x.destino = ' . $this->app->session->get('user'));
            $qtde    = (array) $qtde[0];
        } catch (\Exception $ex) {
            $this->app->flash->addMessage('error', $ex->getMessage());
        }
        return $this->app->view->render($response, '@twMod/mailContato.twig', ['contatos' => $contato, 'qtde' => $qtde]);
    }
    //--------------------------------------------------------------------------------
    public function nova(Request $request, Response $response, $args)
    {
        $mensagem = [];
        $contato  = [];
        try {
            $contato  = Mensagem::selectContatos('u.login ASC', ' u.status = "A"');
            $mensagem = Mensagem::selectGridEnv('m.data DESC', ' m.remetente = ' . $this->app->session->get('user'));
            $qtde     = Mensagem::selectQtde(null, ' x.destino = ' . $this->app->session->get('user'));
            $qtde     = (array) $qtde[0];
        } catch (\Exception $ex) {
            $this->app->flash->addMessage('error', $ex->getMessage());
        }
        return $this->app->view->render($response, '@twMod/mailCompose.twig', ['mensagem' => $mensagem, 'qtde' => $qtde, 'contatos' => $contato, 'args' => $args]);
    }
    //--------------------------------------------------------------------------------
    public function det(Request $request, Response $response, $args)
    {
        $userId = $request->getAttribute('session')->get('user');
        $dados  = [];
        try {
            $dados = Mensagem::selectRead('m.data DESC', ' m.id = ' . $args['id']);
            $dados = (array) $dados[0];
            $qtde  = Mensagem::selectQtde(null, ' x.destino = ' . $userId);
            $qtde  = (array) $qtde[0];
        } catch (\Exception $ex) {
            $this->app->flash->addMessage('error', $ex->getMessage());
        }
        if ($dados['anexo']) {
            $r = explode(',', $dados['anexo']);
            foreach ($r as $k => $v) {
                $v     = explode(';', $v);
                $arr[] = array('id' => $v[0], 'nome' => $v[1]);
            }
            $dados['anexo'] = $arr;
        }
        $this->setLeitura($args['id'], $userId);
        return $this->app->view->render($response, '@twMod/readmail.twig', ['mensagem' => $dados, 'qtde' => $qtde]);
    }
    //--------------------------------------------------------------------------------
    public function getMsgs(Request $request, Response $response, $next)
    {
        if ($this->app->session->get('user') != null) {
            try {
                $dados = Mensagem::selectGrid('m.data DESC', ' x.destino  = ' . $this->app->session->get('user') . ' and x.status = 0');
            } catch (\Exception $ex) {
                $this->app->flash->addMessage('error', $ex->getMessage());
            }
            if ($dados) {
                echo '
            <a href="#" class="dropdown-toggle animated infinite bounce" data-toggle="dropdown" style="padding-top: 15px !important; padding-bottom: 15px !important;">
                <i class="fa fa-envelope"></i>
                <span class="label label-success">' . count($dados) . '</span>
            </a>
        	<ul class="dropdown-menu">
        		<li class="header">Você tem ' . count($dados) . ' mensagem(ns)</li>
        		<li>
        		<ul class="menu">';
                foreach ($dados as $row) {
                    $data = str_replace(' ', '<br> <i class="fa fa-clock-o"></i> ', date('d/m/Y H:i', strtotime($row->data)));
                    echo '<li>
        				<a href="' . $this->app->router->pathFor('index') . '/app/system/mensagem/det/' . $row->id . '">
        					<div class="pull-left"><img src="' . $this->app->router->pathFor('index') . '/' . $row->avatar . '" class="img-circle" ></div>
        					<h4>' . $row->de . ' <small><span class="pull-right">' . $data . '</span></small></h4>
        					<p>' . $row->assunto . '</p>
        				</a>
        				</li>';
                }
                echo '</ul>
        		</li>
        		<li class="footer"><a href="' . $this->app->router->pathFor('index') . '/app/system/mensagem">Ver Todas as Mensagens</a></li>
        	</ul>';
            } else {
                echo '
            <a href="' . $this->app->router->pathFor('index') . '/app/system/mensagem" style="padding: 15px 10px 15px !important;">
                <i class="fa fa-envelope-o"></i>
            </a>';
            }
        } else {
            //return $response->withRedirect( $this->router->pathFor('login') );
            return $next($request, $response);
        }
    }
    //--------------------------------------------------------------------------------
    public function sendMsgDefault($remetente, $destino, $modelo, $var = array(), $perfil = 3)
    {
        $mod     = TabMensagem::find($modelo);
        $texto   = $mod->detalhe;
        $assunto = $mod->titulo;

        $key = array_keys($var);
        foreach ($key as $k => $v) {
            $busca[$k] = '{' . strtoupper($v) . '}';
        }
        $texto = str_replace($busca, $var, $texto);

        $msg            = new Mensagem();
        $msg->remetente = $remetente;
        $msg->destino   = $destino;
        $msg->assunto   = $assunto;
        $msg->texto     = $texto;
        $msg->data      = date('Y-m-d H:i:s');
        $msg->perfil    = $perfil;
        if ($msg->save()) {
            $newId = $msg->id;
            if ($newId != null) {
                $destino = explode(',', $destino);
                foreach ($destino as $destUser) {
                    $muser            = new MensagemUser();
                    $muser->mensagem  = $newId;
                    $muser->remetente = $remetente;
                    $muser->destino   = $destUser;
                    $muser->status    = 0;
                    $muser->data      = date('Y-m-d H:i:s');
                    $muser->save();
                }
            }
        }
    }
    //--------------------------------------------------------------------------------
    public static function setLeitura($msg, $user)
    {
        $dados = MensagemUser::where('mensagem', $msg)->where('destino', $user)->where('status', 0)->get();
        if ($dados[0]) {
            $msg               = MensagemUser::find($dados[0]['id']);
            $msg->status       = 1;
            $msg->data_leitura = date('Y-m-d H:i:s');
            $msg->save();
        }
    }
}
