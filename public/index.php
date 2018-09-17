<?php

/**
 * 
 * NZord - Framework baseado em Slim3
 * @package     nZord Framework
 * @author      Dpto de Tecnologia da Prefeitura de Lucas do Rio Verde-MT <ti@lucasdorioverde.mt.gov.br>
 * @version     v.1.0 (01/08/2018)
 * @copyright   Copyright (c) 2018, DTIC
 * 
 */

ini_set('display_errors',1);
error_reporting(E_ALL);

session_start();
date_default_timezone_set('America/Cuiaba');
setlocale(LC_ALL,'pt_BR.UTF8', "ptb");


if (PHP_SAPI == 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
    /* 
     * Para ajudar o servidor embutido do PHP, verifique se a requisição foi 
     * realmente para algo que provavelmente deveria ser servido como um arquivo estático
     */
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

$starttime = microtime(true);

// Carrega todas dependencias composer
require '../vendor/autoload.php';

$setting = require '../base/settings.php';
$app = new \NZord\App($setting);
$app->run();