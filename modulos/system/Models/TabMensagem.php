<?php
/**
 * Class TabMensagem | modulos/system/Models/TabMensagem.php
 *
 */
namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;

class TabMensagem extends Model{
    
    protected $table = 'tab_mensagem';
    
    protected $primaryKey='id';

    public $timestamps = false;

}