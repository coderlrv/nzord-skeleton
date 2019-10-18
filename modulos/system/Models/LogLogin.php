<?php
namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;
use NZord\Helpers\NQuery;

class LogLogin extends Model
{
    protected $table = 'sys_log_login';

    protected $primaryKey = 'id';

    public $timestamps = false;

    /**
     * Grava log de login
     *
     * @param string $login     - login do usuário tentando acessar.
     * @param string $ip        - ip remoto.
     * @param string $detalhe   - detalhes do acesso.
     * @param int $status    - Situação de acesso.  1 - Acesso com sucesso  2 - Acesso negado.
     * 
     * @return boolean
     */
    public static function add($login, $ip, $detalhe, $status)
    {
        $log            = new LogLogin();
        $log->data      = date('Y-m-d H:i:s');
        $log->user_id   = $login;
        $log->remote_ip = $ip;
        $log->detalhe   = $detalhe;
        $log->status    = $status;
        return $log->save();
    }
    /**
     *  Select logs de login para grid
     *  Alias =
     *       none
     * @return NQuery
     */
    public static function selectGrid(){
        return new NQuery('select
                        id
                        ,data
                        ,user_id
                        ,remote_ip
                        ,detalhe
                        ,status
                        from sys_log_login');
    }
}
