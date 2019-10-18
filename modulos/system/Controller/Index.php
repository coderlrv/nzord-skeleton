<?php
namespace Modulos\System;

use Slim\Http\Request;
use Slim\Http\Response;
use \NZord\Controller\Controller;
use \Modulos\System\Models\Modulo;

/**
 * 
 *  SystemController base nzord.
 * 
 */
class IndexController extends Controller
{
    // --------------------------------------------------------------------------------
    public function index(Request $request, Response $response, $args = null)
    {
        $dados = Modulo::selectGrid(null);
        $dados->where('nome', 'system');
        $buttons = $dados->toArray();
        
        $buttons = json_decode($buttons[0]->menu, true);

        return $this->app->view->render($response, '@twMod/index.twig', ['buttons' => $buttons]);
    }
    // --------------------------------------------------------------------------------
}
