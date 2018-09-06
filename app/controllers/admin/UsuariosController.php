<?php
namespace app\controllers\Admin;

use app\controllers\BaseController;
use app\models\UsuariosRegistro;
use Sirius\Validation\Validator;
use Sirius\Upload\Handler as UploadHandler;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class UsuariosController extends BaseController{
	public function getIndex(){
		
		$usuarios = UsuariosRegistro::all();

		return $this->render('admin/usuarios.twig', ['usuarios' => $usuarios]);

	}
	public function postIndex(){ 

		$errors = false;
		$resultado = false;


		$validator = new Validator();
		$validator->add('nombre', 'required');
		$validator->add('apellido', 'required');
		$validator->add('usuario', 'required');
		$validator->add('correo', 'email');
		$validator->add('clave', 'required');
		$validator->add('tipousuario', 'required');

		$upload = new UploadHandler('images/admin/');
		$folder = 'images/admin/';
		$imgProfile = $upload->process($_FILES['imgProfile']);

		if ($validator->validate($_POST)) {
		    
		    $mail = new phpmailer();

			$mail->Host = 'mail.soluticket.com.mx';
			$mail->SMTPAuth = true;
			$mail->Username = 'admin@soluticket.com.mx';
			$mail->Password = "cDEFyandZ1Pj";
			$mail->SMTPSecure = 'tls';
			$mail->Port = 465;

			$mail->setFrom('noreply@soluticket.com.mx');
			$mail->addAddress($_POST['correo']);
			$mail->addAttachment('images/logo.png');

			$mail->IsHTML(true);
			$mail->Subject = $_POST['nombre'] . ' ' . $_POST['apellido'];
			$mail->Body = '<br><strong><h3>Soluticket te ha dado de alta en su sistema con los siguientes datos:</h3></strong><br><br>' . '<h3>Correo electrónico: </h3>' . $_POST['correo'] . '<br><h3>Usuario:  </h3>'. $_POST['usuario'] . '<h3>Contraseña: </h3>' . $_POST['clave'];
			$mail->send();

			$users = new UsuariosRegistro();

			$users->nombre = $_POST['nombre'];
			$users->apellido = $_POST['apellido'];
			$users->usuario = $_POST['usuario'];
			$users->correo = $_POST['correo'];
			$users->tipousuario = $_POST['tipousuario'];

			$users->clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);

			if ($imgProfile->isValid()) {

				$users->foto = $folder . $imgProfile->name;
				$users->save();
				$imgProfile->confirm();

			}else{
				$users->foto = $folder . 'foto.jpg';
			}

			$users->save();
			$resultado = true;
			$usuarios = UsuariosRegistro::all();

			return $this->render('admin/usuarios.twig', [
				'resultado' => $resultado,
				'usuarios' => $usuarios
			]);

		}
		header('Location:' . BASE_URL . 'admin/usuarios');
	}
	public function getEditarusuario($postid){

		$usuarios = UsuariosRegistro::query()->where('id', $postid)->get();

		return $this->render('admin/editarusuario.twig', [
			'usuarios' => $usuarios
		]);

	}
	public function postEditarusuario($postid){

		$errors = [];
		$resultado = false;

		$validator = new Validator();

		$validator->add('nombre', 'required');
		$validator->add('apellido', 'required');
		$validator->add('usuario', 'required');
		$validator->add('correo', 'email');
		$validator->add('clave', 'required');
		$validator->add('tipousuario', 'required');

		$upload = new UploadHandler('images/admin/');
		$folder = 'images/admin/';

		$upload->addRule('extension', ['allowed' => ['jpg', 'jpeg', 'png']]);
		$upload->addRule('imagewidth', 'min=150&max=150');
		$upload->addRule('imageheight', 'min=150&max=150');
		$upload->addRule('size', ['max' => '1M']);

		$imgProfile = $upload->process($_FILES['imgProfile']);

		$usuarios = UsuariosRegistro::find($postid);

		if($validator->validate($_POST)){
		    
		    $mail = new phpmailer();

			$mail->Host = 'mail.soluticket.com.mx';
			$mail->SMTPAuth = true;
			$mail->Username = 'admin@soluticket.com.mx';
			$mail->Password = "cDEFyandZ1Pj";
			$mail->SMTPSecure = 'tls';
			$mail->Port = 465;

			$mail->setFrom('noreply@soluticket.com.mx');
			$mail->addAddress($_POST['correo']);
			$mail->addAttachment('images/logo.png');

			$mail->IsHTML(true);
			$mail->Subject = $_POST['nombre'] . ' ' . $_POST['apellido'];
			$mail->Body = '<br><strong><h3>Se ha actualizado con éxito tu cuenta en el sistema de Soluticket:</h3></strong><br><br>' . '<h3>Correo electrónico: </h3>' . $_POST['correo'] . '<br><h3>Usuario:  </h3>'. $_POST['usuario'] . '<h3>Contraseña: </h3>' . $_POST['clave'];
			$mail->send();

			$usuarios->nombre = $_POST['nombre'];
			$usuarios->apellido = $_POST['apellido'];
			$usuarios->usuario = $_POST['usuario'];
			$usuarios->correo = $_POST['correo'];
			$usuarios->tipousuario = $_POST['tipousuario'];
			$usuarios->clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);

			if ($imgProfile->isValid()) {

				$usuarios->foto = $folder.$imgProfile->name;
				$usuarios->save();
				$imgProfile->confirm();

			}

			$usuarios->save();
			$resultado = true;
			$usuarios = UsuariosRegistro::query()->where('id', $postid)->get();

			return $this->render('admin/editarusuario.twig', [
				'resultado' => $resultado,
				'usuarios' => $usuarios
			]);
		}
	}
	public function getEliminar($postid){

		$usuarios = UsuariosRegistro::find($postid);
		$usuarios->delete();

		header('Location:' . BASE_URL . 'admin/usuarios');
	}
}