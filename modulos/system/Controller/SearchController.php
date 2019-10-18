<?php
namespace Modulos\System;

use Illuminate\Database\Capsule\Manager as DB;
use NZord\Controller\Controller;
use Slim\Http\Request;
use Slim\Http\Response;

class SearchController extends Controller
{
    // --------------------------------------------------------------------------------
    public function index(Request $request, Response $response)
    {
        $data  = $request->getParsedBody();
        $dados = DB::select("select
                            m.menuId id
                            ,concat(case when p.menuNome is not null then concat(case when p.menuIdPai > 0 then '... ' else '' end,p.menuNome,' -> ') else '' end ,'<b>',m.menuNome,'</b>') label
                            ,case when m.link is not null then m.link else m.menuLink end as url
                            ,m.hint title
                            ,case when m.link is not null then m.link else m.menuLink end detalhe
                            from n_menu m
                            left join n_menu p on p.menuId = m.menuIdPai
                            where m.menuNome like '%" . $data['q'] . "%' and (m.menuLink <> '' or m.link <> '')");

        return $this->app->view->render($response, '@twMod/search.twig', [
            'dados' => $dados,
            'termo' => $data['q'],
        ]);
    }
}
