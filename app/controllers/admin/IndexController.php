<?php
namespace app\controllers\admin;


use app\controllers\BaseController;
use app\models\UsuariosRegistro;
use app\models\Head;
use app\models\Evento;
use app\models\Categoria;
use app\models\Detallevento;
use app\models\BlogPublicacion;
use app\models\Google;
use app\models\Aviso;
use app\models\Faq;
use app\models\Cliente;
use app\models\Puntodeventa;
use app\models\Termino;
use app\models\Pie;
use app\models\Banner;
use app\models\Nosotros;
use app\models\Contacto;
use app\models\Newsletter;

use Sirius\Validation\Validator;
use Sirius\Upload\Handler as UploadHandler;
use Sirius\Upload\HandlerAggregate as UploadHandlerAggregate;

class IndexController extends BaseController{
	// INDEX
	public function getIndex(){
		
		if(isset($_SESSION['usuarioId'])){
			$usuarioId = $_SESSION['usuarioId'];
			$usuario = UsuariosRegistro::find($usuarioId);
			$carousel = Evento::query()->where('publicado', 1)->orderBy('fecha', 'asc')->limit(1)->get();
			$eventos = Evento::query()->where('publicado', 1)->orderBy('fecha', 'asc')->skip(1)->limit(50)->get();
			$evento = Evento::query()->orderBy('fecha', 'asc')->limit(1)->get();
			$adwords = Google::query()->orderBy('id', 'asc')->get();
			$pventa = Puntodeventa::query()->orderBy('id', 'desc')->limit(1)->get();
			$puntosdeventas = Puntodeventa::query()->orderBy('id', 'desc')->get();
			$blog = BlogPublicacion::query()->orderBy('id', 'desc')->limit(5)->get();
			$banner = Banner::query()->where('seccion', 1)->orderBy('id', 'asc')->limit(1)->get();
			$banners = Banner::query()->where('seccion', 1)->orderBy('id', 'desc')->limit(4)->get();
			$bannerlarge = Banner::query()->where('seccion', 2)->get();
			$faq = Faq::all();
			$nosotros = Nosotros::all();
			$newsletter = Newsletter::all();

			if($usuario){
				return $this->render('admin/index.twig', [
					'usuario' => $usuario,
					'evento' => $evento,
					'carousel' => $carousel,
					'adwords' => $adwords,
					'eventos' => $eventos,
					'pventa' => $pventa,
					'banner' => $banner,
					'nosotros' => $nosotros,
					'banners' => $banners,
					'bannerlarge' => $bannerlarge,
					'puntosdeventas' => $puntosdeventas,
					'blog' => $blog,
					'faq' => $faq,
					'newsletter' => $newsletter
				]);
			}
		}		
		header('Location:' . BASE_URL . 'auth/login');
	}
	public function getDatos($postid){

		$datos = Head::where('id', $postid)->get();
		$footer = Pie::where('id', $postid)->get();

		return $this->render('admin/datos.twig',[
			'datos' => $datos,
			'footer' => $footer
 		]);

	}
	public function postDatos($postid) {

		$errors = [];
		$resultado = false;

		$head = Head::find($postid);

		$validator = new Validator();

		$validator->add('sitio', 'required');
		$validator->add('pclave', 'required');
		$validator->add('descripcion', 'required');
		$validator->add('autor', 'required');

		if ($validator->validate($_POST)) {


			$head->sitio = $_POST['sitio'];
			$head->pclave = $_POST['pclave'];
			$head->descripcion = $_POST['descripcion'];
			$head->autor = $_POST['autor'];


			$head->save();
			$resultado = true;

		}

		header('Location:' . BASE_URL . 'admin/datos/1');

	}
	// PIE
	public function postDatospie($postid)
	{

		$errors = false;
		$resultado = false;

		$footer = Pie::find($postid);

		$validator = new Validator();

		$validator->add('facebook', 'required');
		$validator->add('twitter', 'required');
		$validator->add('youtube', 'required');

		if ($validator->validate($_POST)) {


			$footer->facebook = $_POST['facebook'];
			$footer->twitter = $_POST['twitter'];
			$footer->youtube = $_POST['youtube'];


			$footer->save();
			$resultado = true;

		}

		header('Location:' . BASE_URL . 'admin/datos/1');

	}
	// CONTACTO
	public function getContacto($postid)
	{

		$contactos = Contacto::where('id', $postid)->get();

		return $this->render('admin/contacto.twig', [
			'contactos' => $contactos
		]);

	}
	public function postContacto($postid)
	{

		$errors = false;
		$resultado = false;

		$contactos = Contacto::find($postid);

		$validator = new Validator();

		$validator->add('contacto', 'required');
		$validator->add('faq', 'required');
		$validator->add('llamada', 'required');
		$validator->add('soporte', 'required');

		if ($validator->validate($_POST)) {


			$contactos->contacto = $_POST['contacto'];
			$contactos->faq = $_POST['faq'];
			$contactos->llamada = $_POST['llamada'];
			$contactos->soporte = $_POST['soporte'];

			$contactos->save();
			$resultado = true;

			$contactos = Contacto::query()->where('id', $postid)->get();

			return $this->render('admin/contacto.twig', [
				'resultado' => $resultado,
				'contactos' => $contactos
			]);

		}else{
			$contactos->save();
			$errors = true;

			$contactos = Contacto::query()->where('id', $postid)->get();

			return $this->render('admin/contacto.twig', [
				'errors' => $errors,
				'contactos' => $contactos
			]);
		}

	}
	// NOSOTROS
	public function getNosotros($postid)
	{

		$nosotros = Nosotros::query()->where('id', $postid)->get();

		return $this->render('admin/nosotros.twig', [
			'nosotros' => $nosotros
		]);

	}
	// NOSOTROS
	public function postNosotros($postid)
	{

		$errors = false;
		$resultado = false;

		$nosotros = Nosotros::find($postid);

		$validator = new Validator();

		$validator->add('articulo', 'required');

		if ($validator->validate($_POST)) {

			$nosotros->articulo = $_POST['articulo'];

			$nosotros->save();
			$resultado = true;
			$nosotros = Nosotros::query()->where('id', $postid)->get();

			return $this->render('admin/nosotros.twig', [
				'resultado' => $resultado,
				'nosotros' => $nosotros
			]);

		}else{

			$errors = true;
			$nosotros = Nosotros::query()->where('id', $postid)->get();

			return $this->render('admin/nosotros.twig', [
				'errors' => $errors,
				'nosotros' => $nosotros
			]);
		}
	}
	// VER
	public function getVer($postid)
	{

		$carousels = Carousel::all();

		$evento = Evento::query()->where('id', $postid)->get();

		$adwords = Google::query()->orderBy('id', 'asc')->get();

		$pventa = Puntodeventa::query()->where('id', $postid)->get();

		$blog = BlogPublicacion::query()->where('id', $postid)->get();

		$faq = Faq::all();

		return $this->render('admin/ver.twig', [
			'evento' => $evento,
			'carousels' => $carousels,
			'adwords' => $adwords,
			'pventa' => $pventa,
			'blog' => $blog,
			'faq' => $faq
		]);

	}

	// GOOGLE
	public function getGoogleditar($postid){

		$google = Google::query()->where('id', $postid)->get();

		return $this->render('admin/googleditar.twig', [
			'google' => $google
		]);

	}

	public function postGoogleditar($postid){
		
		$errors = false;
		$resultado = false;
		
		$google = Google::find($postid);

		$validator = new Validator();

		$validator->add('script', 'required');


		if($validator->validate($_POST)){

			$google->script = $_POST['script'];
			
			$google->save();
			$resultado = true;

			return $this->render('admin/googleditar.twig',[
				'resultado' => $resultado,
				'google' => $google
			]);

		}
	}
	// FAQ
	public function getFaq(){

		$faq = Faq::all();

		return $this->render('admin/faq.twig', [
			'faq' => $faq
		]);

	}

	public function postFaq(){

		$errors = false;
		$resultado = false;

		$validator = new Validator();
		
		$validator->add('titulo', 'required');
		$validator->add('descripcion', 'required');
		$validator->add('articulo', 'required');

		if ($validator->validate($_POST)) {

			$faq = new Faq([

				'titulo' => $_POST['titulo'],
				'descripcion' => $_POST['descripcion'],
				'articulo' => $_POST['articulo']

			]);

			$faq->save();
			$resultado = true;
			$faq = Faq::all();

			return $this->render('admin/faq.twig', [
				'resultado' => $resultado,
				'faq' => $faq
			]);

		}else {

			$false = true;
			$faq = $faq = Faq::all();

			return $this->render('admin/faq.twig', [
				'resultado' => $resultado,
				'faq' => $faq
			]);

		}

	}

	public function getEditarfaq($postid)
	{
		$faq = Faq::query()->where('id', $postid)->get();

		return $this->render('admin/editarfaq.twig', [
			'faq' => $faq
		]);
	}

	public function postEditarfaq($postid)
	{
		$errors = false;
		$resultado = false;

		$faqs = Faq::find($postid);

		$validator = new Validator();
		$validator->add('titulo', 'required');
		$validator->add('descripcion', 'required');
		$validator->add('articulo', 'required');

		if ($validator->validate($_POST)) {
			$faqs->titulo = $_POST['titulo'];
			$faqs->descripcion = $_POST['descripcion'];
			$faqs->articulo = $_POST['articulo'];

			$faqs->save();
			$resultado = true;
			$faq = Faq::query()->where('id', $postid)->get();

			return $this->render('admin/editarfaq.twig', [
				'resultado' => $resultado,
				'faq' => $faq
			]);
			
		} else {
			$errors = true;
			$faq = Faq::query()->where('id', $postid)->get();

			return $this->render('admin/editarfaq.twig', [
				'faq' => $faq,
				'errors' => $errors
			]);
		}

	}

	public function getEliminarfaq($postid){

		$faq = Faq::find($postid);
		$faq->delete();

		header('Location:' . BASE_URL . 'admin/faq');	
	}
	// AVISO
	public function getAviso($postid)
	{

		$avisos = Aviso::query()->where('id', $postid)->get();


		return $this->render('admin/aviso.twig', ['avisos' => $avisos]);

	}

	public function postAviso($postid)
	{
		$errors = false;
		$resultado = false;

		$avisos = Aviso::find($postid);

		$validator = new Validator();

		$validator->add('titulo', 'required');
		$validator->add('articulo', 'required');

		if ($validator->validate($_POST)) {

			$avisos->titulo = $_POST['titulo'];
			$avisos->articulo = $_POST['articulo'];

			$avisos->save();
			$resultado = true;
            $avisos = Aviso::query()->where('id',$postid)->get();

            return $this->render('admin/aviso.twig', [
                'resultado' => $resultado,
                'avisos' => $avisos
            ]);

		}else{
			$errors = true;
			$avisos = Aviso::query()->where('id', $postid)->get();


			return $this->render('admin/aviso.twig', [
				'errors' => $errors,
				'clientes' => $clientes
			]);
		}

	}
	// TERMINOS
	public function getTerminos($postid)
	{

		$terminos = Termino::query()->where('id', $postid)->get();


		return $this->render('admin/terminos.twig', ['terminos' => $terminos]);

	}

	public function postTerminos($postid)
	{
		$errors = [];
		$resultado = false;

		$terminos = Termino::find($postid);

		$validator = new Validator();

		$validator->add('titulo', 'required');
		$validator->add('articulo', 'required');

		if ($validator->validate($_POST)) {

			$terminos->titulo = $_POST['titulo'];
			$terminos->articulo = $_POST['articulo'];

			$terminos->save();
			$resultado = true;

		} else {
			$errors = $validator->getMessages();
		}

		header('Location:' . BASE_URL . 'admin/terminos/1');
	}

	// CLIENTES

	public function getClientes()
	{

		$clientes = Cliente::all();


		return $this->render('admin/clientes.twig', ['clientes' => $clientes]);

	}

	public function postClientes()
	{
		$errors = false;
		$resultado = false;

		$upload = new UploadHandler('images/clientes/');
		$rootfolder = 'images/clientes/';
		$upload->addRule('extension', ['allowed' => ['jpg', 'jpeg', 'png']]);
		$upload->addRule('imagewidth', 'min=400&max=400');
		$upload->addRule('imageheight', 'min=400&max=400');
		$upload->addRule('size', ['max' => '1M']);

		$thumbnail = $upload->process($_FILES['thumbnail']);

		if ($thumbnail->isValid()) {

			$cliente = new Cliente;

			$cliente->thumbnail = $rootfolder.$thumbnail->name;
			$cliente->save();
			$thumbnail->confirm();

			$cliente->save();
			$resultado = true;
			$clientes = Cliente::all();

			return $this->render('admin/clientes.twig', [
				'resultado' => $resultado,
				'clientes' => $clientes
			]);

		}else{

			$errors = true;
			$clientes = Cliente::all();

			return $this->render('admin/clientes.twig', [
				'errors' => $errors,
				'clientes' => $clientes
			]);

		}
	}

	public function getEditarcliente($postid)
	{

		$clientes = Cliente::query()->where('id', $postid)->get();

		return $this->render('admin/editarcliente.twig', [
			'clientes' => $clientes
		]);

	}

	public function postEditarCliente($postid)
	{
		$errors = false;
		$resultado = false;

		$cliente = Cliente::find($postid);

		$upload = new UploadHandler('images/clientes/');
		$rootfolder = 'images/clientes/';
		$upload->setOverwrite(true);
		$upload->addRule('extension', ['allowed' => ['jpg', 'jpeg', 'png']]);
		$upload->addRule('imagewidth', 'min=400&max=400');
		$upload->addRule('imageheight', 'min=400&max=400');
		$upload->addRule('size', ['max' => '1M']);

		$thumbnail = $upload->process($_FILES['thumbnail']);

		if ($thumbnail->isValid()) {

			$cliente->thumbnail = $rootfolder.$thumbnail->name;
			$cliente->save();
			$thumbnail->confirm();

			$cliente->save();
			$resultado = true;
			$clientes = Cliente::all();

			return $this->render('admin/clientes.twig', [
				'resultado' => $resultado,
				'clientes' => $clientes
			]);

		}else{

			$errors = true;
			$clientes = Cliente::all();

			return $this->render('admin/clientes.twig', [
				'errors' => $errors,
				'clientes' => $clientes
			]);

		}
	}
	public function getEliminarcliente($postid)
	{

		$clientes = Cliente::find($postid);
		$clientes->delete();

		header('Location:' . BASE_URL . 'admin/clientes');
	}


	// EVENTOS
	public function getEventos()
	{

		$eventos = Evento::query()->where('publicado' , 1)->orderBy('fecha', 'desc')->get();
		$noeventos = Evento::query()->where('publicado' , 0)->orderBy('fecha', 'desc')->get();
		$categorias = Categoria::query()->orderBy('id', 'desc')->get();

		return $this->render('admin/eventos.twig', [
			'eventos' => $eventos,
			'noeventos' => $noeventos,
			'categorias' => $categorias
			]);

	}
	public function postEventos()
	{
		$errors = false;
		$resultado = false;

		$validator = new Validator();

		$validator->add('nombre', 'required');
		$validator->add('descripcion', 'required');
		$validator->add('estado', 'required');
		$validator->add('ciudad', 'required');
		$validator->add('categoria', 'required');
		$validator->add('fecha', 'required');
		$validator->add('hora', 'required');
		$validator->add('pixel', 'required');
		$validator->add('pub', 'required');
		$validator->add('FE', 'required');
		

		$upload = new UploadHandler('images/eventos/');
		$rootfolder = 'images/eventos/';
		$upload->addRule('extension', ['allowed' => ['jpg', 'jpeg', 'png']]);
		$upload->addRule('imagewidth', 'min=1980&max=1980');
		$upload->addRule('imageheight', 'min=800&max=800');
		$upload->addRule('size', ['max' => '1M']);

		$slide = $upload->process($_FILES['slide']);

		if ($validator->validate($_POST)) {

			$eventos = new Evento([

				'nombre' => $_POST['nombre'],
				'descripcion' => $_POST['descripcion'],
				'estado' => $_POST['estado'],
				'ciudad' => $_POST['ciudad'],
				'categoria' => $_POST['categoria'],
				'fecha' => $_POST['fecha'],
				'hora' => $_POST['hora'],
				'facebook_pixel' => $_POST['pixel'],
				'publicado' => $_POST['pub'],
				'activo' => $_POST['FE']

			]);

			if ($slide->isValid()) {

				$eventos->slide = $rootfolder.$slide->name;
				$eventos->save();
				$slide->confirm();

			}else{
				
				$errors = true;
				$eventos = Evento::query()->orderBy('fecha', 'desc')->get();
				$categorias = Categoria::query()->orderBy('id', 'desc')->get();
				return $this->render('admin/eventos.twig', [
					'errors' => $errors,
					'eventos' => $eventos,
					'categorias' => $categorias
				]);
			}

		$eventos->save();
		$resultado = true;
		$eventos = Evento::query()->orderBy('fecha', 'desc')->get();
		$categorias = Categoria::query()->orderBy('id', 'desc')->get();

		return $this->render('admin/eventos.twig', [
			'resultado' => $resultado,
			'eventos' => $eventos,
			'categorias' => $categorias
		]);	

		}
	}
	public function getEditareventos($postid)
	{

		$eventos = Evento::query()->where('id', $postid)->get();
		$categorias = Categoria::query()->orderBy('id', 'desc')->get();

		return $this->render('admin/editareventos.twig', [
			'eventos' => $eventos,
			'categorias' => $categorias
		]);

	}
	public function postEditareventos($postid)
	{
		$errors = false;
		$resultado = false;

		$validator = new Validator();

		$validator->add('nombre', 'required');
		$validator->add('descripcion', 'required');
		$validator->add('estado', 'required');
		$validator->add('ciudad', 'required');
		$validator->add('categoria', 'required');
		$validator->add('fecha', 'required');
		$validator->add('hora', 'required');
		$validator->add('pixel', 'required');
		$validator->add('pub', 'required');
		$validator->add('FE', 'required');

		$upload = new UploadHandler('images/eventos/');
		$rootfolder = 'images/eventos/';
		$upload->setOverwrite(true);
		$upload->addRule('extension', ['allowed' => ['jpg', 'jpeg', 'png']]);
		$upload->addRule('imagewidth', 'min=1980&max=1980');
		$upload->addRule('imageheight', 'min=800&max=800');
		$upload->addRule('size', ['max' => '1M']);

		$slide = $upload->process($_FILES['slide']);

		$eventos = Evento::find($postid);

		if ($validator->validate($_POST)) {

			$eventos->nombre = $_POST['nombre'];
			$eventos->descripcion = $_POST['descripcion'];
			$eventos->estado = $_POST['estado'];
			$eventos->ciudad = $_POST['ciudad'];
			$eventos->categoria = $_POST['categoria'];
			$eventos->fecha = $_POST['fecha'];
			$eventos->hora = $_POST['hora'];
			$eventos->facebook_pixel = $_POST['pixel'];
			$eventos->publicado = $_POST['pub'];
			$eventos->activo = $_POST['FE'];

			if ($slide->isValid()) {

				$eventos->slide = $rootfolder.$slide->name;
				$eventos->save();
				$slide->confirm();

			}

		$eventos->save();
		$resultado = true;
		$eventos = Evento::query()->where('id', $postid)->get();
		$categorias = Categoria::query()->orderBy('id', 'desc')->get();
		return $this->render('admin/editareventos.twig', [
			'resultado' => $resultado,
			'eventos' => $eventos,
			'categorias' => $categorias
		]);	

		}else {
			$errors = true;
			$eventos = Evento::query()->where('id', $postid)->get();
			$categorias = Categoria::query()->orderBy('id', 'desc')->get();
			
			return $this->render('admin/editareventos.twig', [
				'errors' => $errors,
				'eventos' => $eventos,
				'categorias' => $categorias
			]);
		}
	}
	public function getEliminarevento($postid)
	{

		$eventos = Evento::find($postid);
		$eventos->delete();

		header('Location:' . BASE_URL . 'admin/eventos');
	}

	// PUNTOS DE VENTA
	public function getPuntosdeventa()
	{

		$puntosdeventas = Puntodeventa::query()->orderBy('evento', 'desc')->get();

		$eventos = Evento::query()->orderBy('fecha', 'desc')->get();
		$eventoid = Evento::all();

		return $this->render('admin/puntosdeventa.twig', [
			'puntosdeventas' => $puntosdeventas,
			'eventos' => $eventos,
			'eventoid' => $eventoid
		]);

	}
	public function postPuntosdeventa()
	{
		$errors = false;
		$resultado = false;

		$validator = new Validator();

		$validator->add('establecimiento', 'required');
		$validator->add('contenido', 'required');
		$validator->add('evento', 'required');

		$folder = new UploadHandler('images/puntosdeventa/');
		$rootfolder = 'images/puntosdeventa/';
		$folder->addRule('extension', ['allowed' => ['jpg', 'jpeg', 'png']]);
		$folder->addRule('imagewidth', 'min400&max=400');
		$folder->addRule('imageheight', 'min300&max=300');
		$folder->addRule('size', ['max' => '1M']);

		$thumb = $folder->process($_FILES['thumb']);

		if ($validator->validate($_POST)) {

			$pventa = new Puntodeventa([

				'establecimiento' => $_POST['establecimiento'],
				'contenido' => $_POST['contenido'],
				'evento' => $_POST['evento']

			]);

			if ($thumb->isValid()) {

				$pventa->thumbnail = $rootfolder. $thumb->name;
				$pventa->save();
				$thumb->confirm();

			}

		$pventa->save();
		$resultado = true;
		$puntosdeventas = Puntodeventa::query()->orderBy('evento', 'desc')->get();
		$eventos = Evento::query()->orderBy('fecha', 'desc')->get();
		$eventoid = Evento::all();

		return $this->render('admin/puntosdeventa.twig', [
			'resultado' => $resultado,
			'puntosdeventas' => $puntosdeventas,
			'eventos' => $eventos,
			'eventoid' => $eventoid
		]);	

		}else{

			$errors = true;
			$puntosdeventas = Puntodeventa::all();
			$eventos = Evento::query()->orderBy('fecha', 'desc')->get();
			$eventoid = Evento::all();

			return $this->render('admin/puntosdeventa.twig', [
				'errors' => $errors,
				'puntosdeventas' => $puntosdeventas,
				'eventos' => $eventos,
				'eventoid' => $eventoid
			]);

		}
	}
	public function getEditarpventa($postid)
	{

		$puntosdeventa = Puntodeventa::query()->where('id', $postid)->get();
		$eventoid = Evento::all();

		return $this->render('admin/editarpventa.twig', [
			'puntosdeventa' => $puntosdeventa,
			'eventoid' => $eventoid
		]);
	}
	public function postEditarpventa($postid)
	{

		$errors = false;
		$resultado = false;

		$puntosdeventa = Puntodeventa::find($postid);

		$validator = new Validator();

		$validator->add('establecimiento', 'required');
		$validator->add('contenido', 'required');
		$validator->add('evento', 'required');

		$folder = new UploadHandler('images/puntosdeventa/');
		$rootfolder = 'images/puntosdeventa/';
		$folder->setOverwrite(true);
		$folder->addRule('extension', ['allowed' => ['jpg', 'jpeg', 'png']]);
		$folder->addRule('imagewidth', 'min=400&max=400');
		$folder->addRule('imageheight', 'mmin=300&max=300');
		$folder->addRule('size', ['max' => '1M']);

		$thumb = $folder->process($_FILES['thumb']);

		if ($validator->validate($_POST)) {
			$puntosdeventa->establecimiento = $_POST['establecimiento'];
			$puntosdeventa->contenido = $_POST['contenido'];
			$puntosdeventa->evento = $_POST['evento'];

			if ($thumb->isValid()) {

				$puntosdeventa->thumbnail = $rootfolder . $thumb->name;
				$puntosdeventa->save();
				$thumb->confirm();

			}

		$puntosdeventa->save();
		$resultado = true;
		$puntosdeventa = Puntodeventa::query()->where('id', $postid)->get();
		$eventoid = Evento::all();

		return $this->render('admin/editarpventa.twig', [
			'resultado' => $resultado,
			'eventoid' => $eventoid,
			'puntosdeventa' => $puntosdeventa
		]);	

		}else{
				
			$errors = true;
			$puntosdeventa = Puntodeventa::query()->where('id', $postid)->get();
			$eventoid = Evento::query()->where('id', $postid)->get();

			return $this->render('admin/editarpventa.twig', [
				'errors' => $errors,
				'puntosdeventa' => $puntosdeventa,
				'eventoid' => $eventoid
			]);

		}
	}
	public function getEliminarpventa($postid)
	{

		$puntosdeventa = Puntodeventa::find($postid);
		$puntosdeventa->delete();

		header('Location:' . BASE_URL . 'admin/puntosdeventa');

	}

	// BLOG
	public function getBlog()
	{

		$blog = BlogPublicacion::query()->orderBy('id', 'desc')->get();

		return $this->render('admin/blog.twig', [
			'blog' => $blog
		
		]);

	}
	public function postBlog()
	{

		$errors = false;
		$resultado = false;

		$validator = new Validator();

		$validator->add('titulo', 'required');
		$validator->add('descripcion', 'required');
		$validator->add('fecha', 'required');
		$validator->add('articulo', 'required');

		$upload = new UploadHandler('images/publicaciones/');
		$rootfolder = 'images/publicaciones/';
		$upload->addRule('extension', ['allowed' => ['jpg', 'jpeg', 'png']]);
		$upload->addRule('imagewidth', 'min=1000&max=1000');
		$upload->addRule('imageheight', 'min=600&max=600');
		$upload->addRule('size', ['max' => '1M']);


		$thumbnail = $upload->process($_FILES['post']);

		if ($validator->validate($_POST)) {

			$post = new BlogPublicacion([

				'titulo' => $_POST['titulo'],
				'descripcion' => $_POST['descripcion'],
				'fecha' => $_POST['fecha'],
				'articulo' => $_POST['articulo']

			]);

			if ($thumbnail->isValid()) {
				$post->img_blog = $rootfolder . $thumbnail->name;
				$post->save();
				$thumbnail->confirm();
			}else{

				$errors = true;
				$blog = BlogPublicacion::query()->orderBy('id', 'desc')->get();

				return $this->render('admin/blog.twig', [
					'errors' => $errors,
					'blog' => $blog
				]);
			}

		$post->save();
		$resultado = true;
		$blog = BlogPublicacion::query()->orderBy('id', 'desc')->get();

		return $this->render('admin/blog.twig', [
			'resultado' => $resultado,
			'blog' => $blog
		]);	

		}
	}
	public function getBlogeditar($postid)
	{

		$blog = BlogPublicacion::query()->where('id', $postid)->get();

		return $this->render('admin/blogeditar.twig', [
			'blog' => $blog
		]);

	}
	public function postBlogeditar($postid)
	{
		$errors = false;
		$resultado = false;

		$blog = BlogPublicacion::find($postid);

		$validator = new Validator();

		$validator->add('titulo', 'required');
		$validator->add('descripcion', 'required');
		$validator->add('fecha', 'required');
		$validator->add('articulo', 'required');

		$folder = new UploadHandler('images/publicaciones/');
		$rootfolder = 'images/publicaciones/';
		$folder->setOverwrite(true);
		$folder->addRule('extension', ['allowed' => ['jpg', 'jpeg', 'png']]);
		$folder->addRule('imagewidth', 'min=1000&max=1000');
		$folder->addRule('imageheight', 'min=600&max=600');
		$folder->addRule('size', ['max' => '1M']);

		$thumbnail = $folder->process($_FILES['thumbnail']);

		if ($validator->validate($_POST)) {

			$blog->titulo = $_POST['titulo'];
			$blog->descripcion = $_POST['descripcion'];
			$blog->fecha = $_POST['fecha'];
			$blog->articulo = $_POST['articulo'];

			if ($thumbnail->isValid()) {

				$blog->img_blog = $rootfolder.$thumbnail->name;
				$blog->save();
				$thumbnail->confirm();
				
			}

		$blog->save();
		$resultado = true;
		$blog = BlogPublicacion::query()->where('id', $postid)->get();

		return $this->render('admin/blogeditar.twig', [
			'resultado' => $resultado,
			'blog' => $blog
		]);	

		}else{

			$errors = true;
			$blog = BlogPublicacion::query()->where('id', $postid)->get();

			return $this->render('admin/blogeditar.twig', [
				'errors' => $errors,
				'blog' => $blog
			]);
		}


	}
	
	public function getEliminarpost($postid)
	{

		$post = BlogPublicacion::find($postid);
		$post->delete();

		header('Location:' . BASE_URL . 'admin/blog');
	}
	// BANNERS
	public function getBanners()
	{
		$errors = false;
		$resultado = false;

		$banners = Banner::query()->orderBy('seccion', 'asc')->get();

		return $this->render('admin/banners.twig', [
			'banners' => $banners
		]);

	}
	
	public function postBanners()
	{
		$errors = false;
		$resultado = false;

		$validator = new Validator();

		$validator->add('link', 'required');
		$validator->add('FE', 'required');

		$folder = new UploadHandler('images/banners/');
		$rootfolder = 'images/banners/';
		$folder->setOverwrite(true);
		$folder->addRule('extension', ['allowed' => ['jpg', 'jpeg', 'png']]);
		$folder->addRule('imagewidth', 'min=1140&max=12800');
		$folder->addRule('imageheight', 'min=160&max=500');
		$folder->addRule('size', ['max' => '1M']);

		$thumbnail = $folder->process($_FILES['thumbnail']);

		if ($validator->validate($_POST)) {

			$banners = new Banner([

				'url' => $_POST['link'],
				'seccion' => $_POST['FE'],

			]);

			if ($thumbnail->isValid()) {

				$banners->img_banner = $rootfolder . $thumbnail->name;
				$banners->save();
				$thumbnail->confirm();

			}

			$banners->save();
			$resultado = true;

			$banners = Banner::query()->orderBy('seccion', 'asc')->get();

			return $this->render('admin/banners.twig', [

				'resultado' => $resultado,
				'banners' => $banners
			]);

		} else {

			$errors = true;
			$banners = Banner::query()->orderBy('seccion', 'asc')->get();

			return $this->render('admin/banners.twig', [
				'errors' => $errors,
				'banners' => $banners
			]);
		}
	}

	public function getEditarbanner($postid)
	{
		$banners = Banner::query()->where('id', $postid)->get();

		return $this->render('admin/editarbanner.twig', [
			'banners' => $banners
		]);

	}

	public function postEditarbanner($postid)
	{
		$errors = false;
		$resultado = false;

		$banners = Banner::find($postid);

		$validator = new Validator();

		$validator->add('link', 'required');
		$validator->add('FE', 'required');

		$folder = new UploadHandler('images/banners/');
		$rootfolder = 'images/banners/';
		$folder->setOverwrite(true);
		$folder->addRule('extension', ['allowed' => ['jpg', 'jpeg', 'png']]);
		$folder->addRule('imagewidth', 'min=1140&max=12800');
		$folder->addRule('imageheight', 'min=160&max=500');
		$folder->addRule('size', ['max' => '1M']);

		$thumbnail = $folder->process($_FILES['thumbnail']);

		if ($validator->validate($_POST)) {

			$banners->url = $_POST['link'];
			$banners->seccion = $_POST['FE'];

			if ($thumbnail->isValid()) {

				$banners->img_banner = $rootfolder.$thumbnail->name;
				$banners->save();
				$thumbnail->confirm();

			}

			$banners->save();
			$resultado = true;

			$banners = Banner::where('id', $postid)->get();

			return $this->render('admin/editarbanner.twig', [

				'resultado' => $resultado,
				'banners' => $banners
			]);

		}else{

			$errors = true;
			$banners = Banner::query()->where('id', $postid)->get();

			return $this->render('admin/editarbanner.twig', [
				'errors' => $errors,
				'banners' => $banners
			]);
		}
	}

	public function getEliminarbanner($postid)
	{

		$banner = Banner::find($postid);
		$banner->delete();

		header('Location:' . BASE_URL . 'admin/banners');
	}

	// MAPAS

	public function getDetalleseventos()
	{
		$errors = false;
		$resultado = false;

		$detalles = Detallevento::query()->orderBy('id', 'desc')->get();
		$eventoid = Evento::query()->where('publicado', 1)->orderBy('id', 'asc')->get();

		return $this->render('admin/detalleseventos.twig', [
			'detalles' => $detalles,
			'eventoid' => $eventoid
		]);

	}

	public function postDetalleseventos()
	{
		$errors = false;
		$resultado = false;

		$validator = new Validator();

		$validator->add('evento', 'required');
		$validator->add('precios', 'required');
		$validator->add('manual', 'required');
		$validator->add('automatico', 'required');
		$validator->add('detalles', 'required');

		$upload = new UploadHandler('images/mapas/');
		$rootfolder = 'images/mapas/';
		$upload->addRule('extension', ['allowed' => ['jpg', 'jpeg', 'png']]);
		$upload->addRule('imagewidth', 'min=800&max=800');
		$upload->addRule('imageheight', 'min=800&max=800');
		$upload->addRule('size', ['max' => '1M']);

		$thumbnail = $upload->process($_FILES['mapa']);

		if ($validator->validate($_POST)) {

			$detalles = new Detallevento([

				'eventos' => $_POST['evento'],
				'precios' => $_POST['precios'],
				'btn_manual' => $_POST['manual'],
				'btn_automatico' => $_POST['automatico'],
				'detalles' => $_POST['detalles']

			]);

			if ($thumbnail->isValid()){

				$detalles->mapa = $rootfolder . $thumbnail->name;
				$detalles->save();
				$thumbnail->confirm();

			}else{

				$errors = true;
				$detalles = Detallevento::query()->orderBy('id', 'desc')->get();

				return $this->render('admin/detalleseventos.twig', [
					'errors' => $errors,
					'detalles' => $detalles
				]);
			}

			$detalles->save();
			$resultado = true;
			$detalles = Detallevento::query()->orderBy('id', 'desc')->get();
			$eventoid = query()->where('publicado', 1)->orderBy('id', 'asc')->get();

			return $this->render('admin/detalleseventos.twig', [
				'resultado' => $resultado,
				'detalles' => $detalles,
				'eventoid' => $eventoid
			]);

		}
	}

	public function getDetallesevento($postid)
	{
		$detalles = Detallevento::query()->where('id', $postid)->get();
		$eventoid = Evento::query()->where('publicado', 1)->orderBy('id', 'asc')->get();

		return $this->render('admin/detallesevento.twig', [
			'detalles' => $detalles,
			'eventoid' => $eventoid
		]);

	}

	public function postDetallesevento($postid)
	{
		$errors = false;
		$resultado = false;

		$detalles = Detallevento::find($postid);

		$validator = new Validator();

		$validator->add('evento', 'required');
		$validator->add('precios', 'required');
		$validator->add('manual', 'required');
		$validator->add('automatico', 'required');
		$validator->add('detalles', 'required');

		$folder = new UploadHandler('images/mapas/');
		$rootfolder = 'images/mapas/';
		$folder->setOverwrite(true);
		$folder->addRule('extension', ['allowed' => ['jpg', 'jpeg', 'png']]);
		$folder->addRule('imagewidth', 'min=800&max=800');
		$folder->addRule('imageheight', 'min=800&max=800');
		$folder->addRule('size', ['max' => '1M']);

		$thumbnail = $folder->process($_FILES['mapa']);

		if ($validator->validate($_POST)) {

			$detalles->eventos = $_POST['evento'];
			$detalles->precios = $_POST['precios'];
			$detalles->btn_manual = $_POST['manual'];
			$detalles->btn_automatico = $_POST['automatico'];
			$detalles->detalles = $_POST['detalles'];

			if ($thumbnail->isValid()) {

				$detalles->mapa = $rootfolder . $thumbnail->name;
				$detalles->save();
				$thumbnail->confirm();

			}

			$detalles->save();
			$resultado = true;

			$detalles = Detallevento::query()->where('id', $postid)->get();
			$eventoid = Evento::query()->where('publicado', 1)->orderBy('id', 'asc')->get();

			return $this->render('admin/detallesevento.twig', [

				'resultado' => $resultado,
				'detalles' => $detalles,
				'eventoid' => $eventoid
			]);

		}else{

			$errors = true;
			$detalles = Detallevento::query()->where('id', $postid)->get();
			$eventoid = Evento::query()->where('publicado', 1)->orderBy('id', 'asc')->get();

			return $this->render('admin/detallesevento.twig', [
				'errors' => $errors,
				'detalles' => $detalles,
				'eventoid' => $eventoid
			]);
		}
	}

	public function getEliminardetalle($postid)
	{

		$detalles = Detallevento::find($postid);
		$detalles->delete();

		header('Location:' . BASE_URL . 'admin/detalleseventos');
	}

}