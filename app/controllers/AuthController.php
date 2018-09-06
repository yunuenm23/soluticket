<?php
namespace app\controllers;

use app\models\UsuariosRegistro;
use Sirius\Validation\Validator;

class AuthController extends BaseController{
	public function getLogin(){
		
		return $this->render('login.twig');
	}
	public function postLogin(){
		$validator = new Validator();
		$validator->add('correo','required');
		$validator->add('correo','email');
		$validator->add('clave','required');

		if($validator->validate($_POST)){
			$usuario = UsuariosRegistro::where('correo', $_POST['correo'])->first();
			$disenador = UsuariosRegistro::where('correo', $_POST['correo'])->first();
			$dweb = UsuariosRegistro::where('correo', $_POST['correo'])->first();
			$soporte = UsuariosRegistro::where('correo', $_POST['correo'])->first();

			if($usuario){
				if(password_verify($_POST['clave'], $usuario->clave)){
					$_SESSION['usuarioId'] = $usuario->tipousuario;
					header('Location:' . BASE_URL . 'admin/');
					return null;
				}
			}

			$validator->addMessage('Error', 'Usuario o Correo Electrónico incorrectos');
		}
		
		$errors = $validator->getMessages();
		return $this->render('login.twig',[
			'errors' => $errors
		]);
	}
	public function getLogout(){
		unset($_SESSION['usuarioId']);
		header('Location:' . BASE_URL . 'auth/login');
	}
	

}