<?php
namespace app\models;

use Illuminate\Database\Eloquent\Model;

class Puntodeventa extends Model{
	
	protected $table = 'puntosdeventa';
	protected $fillable = ['evento','establecimiento','contenido','thumb'];
}