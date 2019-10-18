<?php
namespace Modulos\System;

use Modulos\System\Models\Log;
use NZord\Controller\Controller;
use NZord\Controller\DataTables;
use Slim\Http\Request;
use Slim\Http\Response;

class LogController extends Controller
{
    protected $logs;

    public function __construct($app)
    {
        parent::__construct($app);
        $this->logs = Log::selectGrid();
    }

    //--------------------------------------------------------------------------------
    public function index(Request $request, Response $response)
    {
        $logs = $this->logs->orderBy('id', 'desc')->limit(1);
        return $this->app->view->render($response, '@twMod/Log/log.twig', ['logs' => $logs->toArray()]);
    }

    /**
     * Retornar lista logs
     *
     * @param Request $request
     * @param Response $response
     * @return JSON
     */
    public function gridJson(Request $request, Response $response)
    {
        $this->logs = $this->logs->orderBy('id', 'desc')->limit(1000);
        return $response->withJson(DataTables::simple($_REQUEST, $this->logs->toSql()));
    }
}
