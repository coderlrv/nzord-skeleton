<?php
/**
 * Class Institucional | modulos/system/Models/Institucional.php
 *
 */

namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;

class Institucional extends Model{
    
    protected $table = 'intra_institucional';
    
    protected $primaryKey='id';

    public $timestamps = false;

}