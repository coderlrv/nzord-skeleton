<?php
namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;

class MensagemUser extends Model{
    
    protected $table = 'mensagem_user';
    
    protected $primaryKey='id';

    public $timestamps = false;

}