<?php
namespace app\models;

use Illuminate\Database\Eloquent\Model;

class Termino extends Model{
	
	protected $table = 'terminos';
	protected $fillable = ['titulo','articulo'];
}