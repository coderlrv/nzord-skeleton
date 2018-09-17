<?php
namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;

class Recado extends Model{
    
    protected $table = 'mov_recados';
    
    protected $primaryKey='id';

    public $timestamps = false;
        

    /**
     * Enviar recado para usuário ou setor.
     *
     * @param [int] $userId - Id usuario remetente
     * @param [int] $sessaoId - Sessao usuario remetente
     * @param [int] $setorDestino - Id Setor destino
     * @param [int] $usuarioDestino -Id Usuario destino
     * @param [string] $texto - Texto do recado
     * @return void
     */
    public static function sendRecado($userId,$sessaoId,$setorDestino,$usuarioDestino,$texto){
        $recado = new Recado();
        $recado->user_id = $userId;
        $recado->sessao = $sessaoId;
        $recado->data = date("Y-m-d H:i:s");
        $recado->des_setor = $setorDestino;
        $recado->des_user = $usuarioDestino;
        $recado->texto = $texto;
        $recado->status = 'P';

        return $recado->save();
    }
}