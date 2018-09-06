<?php
namespace app\models;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model{
	
	protected $table = 'newsletter';
	protected $fillable = ['nombre','apellido','ciudad','correo'];
}