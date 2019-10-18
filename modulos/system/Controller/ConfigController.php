<?php
/**
 * Controller Config
 */
namespace Modulos\System;

use Modulos\System\Models\Config;
use Modulos\System\Models\Modulo;
use Modulos\System\Models\Theme;
use NZord\Controller\Controller;
use NZord\Responses\ModalResponse;
use Slim\Http\Request;
use Slim\Http\Response;

class ConfigController extends Controller
{
    //--------------------------------------------------------------------------------
    public function index(Request $request, Response $response)
    {
        $config = [];
        try {
            $config = Config::first();
            $theme  = Theme::selectListNome();
        } catch (\Exception $ex) {
            $this->app->flash->addMessage('error', $ex->getMessage());
        }
        return $this->app->view->render($response, '@twMod/config.twig', ['config' => $config, 'themes' => $theme]);
    }
    //--------------------------------------------------------------------------------
    public function save($request, $response, $args)
    {
        $data = $request->getParsedBody();
        unset($data['files']);
        $save = Config::find($data['id']);
        Logger($data);
        try {
            $save->fill($data);
            $save->save();
            $this->app->flash->addMessage('success', "Cadastra realizado com sucesso.");
         
            return $response->withRedirect($this->app['app']['url'] . 'app/system/config');
        } catch (\Exception $ex) {
            $this->app->flash->addMessage('error', $ex);
            //return $response->withRedirect( $this->app['app']['url'].'app/system/config' );
        }
    }
    //--------------------------------------------------------------------------------
    public function form(Request $request, Response $response, $args = null)
    {
        $modulo = null;
        $id     = @$args['id'];
        $url    = $request->getAttribute('url');
        if ($id) {
            $modulo         = Modulo::find($id);
            $url            = str_replace('form/' . $id, 'form', $url);
            $modulo['menu'] = json_decode($modulo['menu'], true);
        }
        $gride = Modulo::selectGrid();
        return ['modulo' => $modulo, 'gride' => $gride->toArray(), 'url' => $url];
    }
    //--------------------------------------------------------------------------------
    public function saveModulo($request, $response, $args)
    {
        $post = $request->getParsedBody();
        if (isset($post['id'])) {
            $save = Modulo::find($post['id']);
        } else {
            $save = new Modulo();
        }
        $post['menu'] = json_encode($post['menu']);
        
        Logger($post);
        $save->fill($post);

        try {
            $save->save();
            return $this->responseJson('Cadastra realizado com sucesso!');
        } catch (\Exception $ex) {
            return $this->responseJson('Erro ao Gravar', 500, $ex);
        }
    }
    //--------------------------------------------------------------------------------
}
