<?php
/**
 * Class Config | modulos/system/Models/Config.php
 *
 */
namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model{
    
    protected $table = 'sys_config';
    
    protected $fillable = ['id', 'title', 'title_idx', 'title_mini', 'descricao', 'login_msg', 'image_idx', 'icon', 'image_logo', 'theme', 'footer'];
    
    protected $primaryKey = 'id';

    public $timestamps = false;

}