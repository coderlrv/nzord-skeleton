<?php
namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;

class PerfilMenu extends Model{
    
    protected $table = 'n_perfil_menu';
    protected $primaryKey='id_perfil_menu';
    public $timestamps = false;
}