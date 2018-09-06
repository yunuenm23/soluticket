<?php
namespace app\models;

use Illuminate\Database\Eloquent\Model;

class Head extends Model{
	
	protected $table = 'cabecera';
	protected $fillable = ['sitio','pclave','descripcion','autor'];
}