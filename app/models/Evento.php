<?php
namespace app\models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model{
	
	protected $table = 'eventos';
	protected $fillable = ['nombre', 'descripcion','estado','ciudad','categoria','fecha','hora','link','articulo','mapa','slide','facebook_pixel','publicado','activo'];
}