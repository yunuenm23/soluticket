<?php
namespace app\models;

use Illuminate\Database\Eloquent\Model;

class UsuariosRegistro extends Model{
	
	protected $table = 'usuarios';
	protected $fillable = ['nombre','apellido','usuario','correo','clave','tipousuario','foto'];
}