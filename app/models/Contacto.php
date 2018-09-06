<?php
namespace app\models;

use Illuminate\Database\Eloquent\Model;

class Contacto extends Model{
	
	protected $table = 'contacto';
	protected $fillable = ['contacto','faq','llamada','soporte'];
}