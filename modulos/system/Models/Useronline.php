<?php
namespace Modulos\System\Models;

use Illuminate\Database\Eloquent\Model;

class Useronline extends Model{
    
    protected $table = 'useronline';
    
    protected $primaryKey='timestamp';

    public $timestamps = false;

}