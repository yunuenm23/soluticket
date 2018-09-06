<?php

namespace app\controllers;

use app\models\BlogPublicacion;
use app\models\Puntodeventa;
use app\models\Faq;
use app\models\Aviso;
use app\models\Termino;
use app\models\Banner;
use app\models\Google;
use app\models\Evento;
use app\models\Head;
use app\models\Detallevento;
use app\models\Pie;
use app\models\Nosotros;
use app\models\Cliente;
use app\models\Categoria;
use app\models\Contacto;
use app\models\Newsletter;
use Sirius\Validation\Validator;
use PHPMailer\PHPMailer\PHPMailer;

class IndexController extends BaseController
{
    public function getIndex()
    {
        $veravisoerror = false;
        $veraviso = false;

        $cabecera = Head::all();
        $footer = Pie::all();
        $carousel = Evento::query()->where('publicado', 1 && 'activo', 'active')->orderBy('fecha', 'asc')->limit(1)->get();
        $eventos = Evento::query()->where('publicado', 1)->orderBy('fecha', 'asc')->skip(1)->limit(4)->get();
        $googleIndex = Google::query()->where('seccion', 0)->get();
        $banners = Banner::query()->where('seccion', 1)->get();
        $banner = Banner::query()->where('seccion', 2)->get();
        $blog = BlogPublicacion::query()->orderBy('fecha', 'desc')->limit(3)->get();
        $clientes = Cliente::query()->orderBy('id', 'desc')->get();
        $contacto = Contacto::all();
        $pfacebook = Evento::query()->where('publicado', 1)->get();

        return $this->render('index.twig', [
            'carousel' => $carousel,
            'cabecera' => $cabecera,
            'footer' => $footer,
            'eventos' => $eventos,
            'googleIndex' => $googleIndex,
            'banners' => $banners,
            'pfacebook' => $pfacebook,
            'banner' => $banner,
            'blog' => $blog,
            'clientes' => $clientes,
            'contacto' => $contacto,
        ]);
    }

    public function postIndex()
    {
        $veravisoerror = false;
        $veraviso = false;
        $validator = new Validator();

        $validator->add('nombre', 'required');
        $validator->add('apellido', 'required');
        $validator->add('ciudad', 'required');
        $validator->add('correo', 'required');

        if ($validator->validate($_POST)) {
            $mail = new phpmailer();

            $mail->Host = 'mail.soluticket.com.mx';
            $mail->SMTPAuth = true;
            $mail->Username = 'admin@soluticket.com.mx';
            $mail->Password = 'cDEFyandZ1Pj';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 465;

            $mail->setFrom('noreply@soluticket.com.mx');
            $mail->addAddress($_POST['correo']);
            $mail->addAttachment('images/logo.png');

            $mail->IsHTML(true);
            $mail->Subject = $_POST['nombre'].' '.$_POST['apellido'];
            $mail->Body = '<br><strong><h3>Gracias por suscribirte al newsletter de Soluticket.com.mx:</h3>';
            $mail->send();

            $newsletter = new Newsletter();

            $newsletter->nombre = $_POST['nombre'];
            $newsletter->apellido = $_POST['apellido'];
            $newsletter->ciudad = $_POST['ciudad'];
            $newsletter->correo = $_POST['correo'];

            $mail = new phpmailer();

            $mail->Host = 'mail.soluticket.com.mx';
            $mail->SMTPAuth = true;
            $mail->Username = 'admin@soluticket.com.mx';
            $mail->Password = '}PZ^#~&5%,=%';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 465;

            $mail->setFrom('admin@soluticket.com.mx');
            $mail->addAddress($_POST['correo']);
            $mail->addAttachment('images/logo.png');

            $mail->IsHTML(true);
            $mail->Subject = $_POST['nombre'].' - '.$_POST['apellido'].' - '.$_POST['ciudad'];
            $mail->Body = $_POST['nombre'].' '.'<br><strong><h1>Te has suscrito al newsletter de Soluticket..</h3></strong><br><br>';

            $newsletter->save();
            $veraviso = true;
            $carousel = Evento::query()->where('publicado', 1 && 'activo', 'active')->orderBy('fecha', 'asc')->limit(1)->get();
            $eventos = Evento::query()->where('publicado', 1)->orderBy('fecha', 'asc')->skip(1)->limit(4)->get();
            $googleIndex = Google::query()->where('seccion', 0)->get();
            $banners = Banner::query()->where('seccion', 1)->get();
            $banner = Banner::query()->where('seccion', 2)->get();
            $blog = BlogPublicacion::query()->orderBy('fecha', 'desc')->limit(3)->get();
            $clientes = Cliente::query()->orderBy('id', 'desc')->get();
            $contacto = Contacto::all();

            return $this->render('index.twig', [
                'veraviso' => $veraviso,
                'carousel' => $carousel,
                'eventos' => $eventos,
                'googleIndex' => $googleIndex,
                'banners' => $banners,
                'banner' => $banner,
                'pfacebook' => $pfacebook,
                'blog' => $blog,
                'clientes' => $clientes,
                'contacto' => $contacto,
            ]);
        } else {
            header('Location:'.BASE_URL.'');
        }
    }

    public function getLayout()
    {
        $cabecera = Head::all();
        $footer = Pie::all();
        $head = Head::all();
        $blog = BlogPublicacion::query()->orderBy('fecha', 'asc')->limit(3)->get();
        $pfacebook = Evento::where('publicado', 1)->orderBy('fecha', 'asc');
        $eventos = Evento::where('publicado', 1)->orderBy('fecha', 'asc');

        return $this->render('layout.twig', [
            'cabecera' => $cabecera,
            'head' => $head,
            'pfacebook' => $pfacebook,
            'eventos' => $eventos,
            'blog' => $blog,
            'datospie' => $datospie,
        ]);
    }

    public function postEnviarcorreo()
    {
        $validator = new Validator();
        $validator->add('correo', 'required');
        $validator->add('nombre', 'required');
        $validator->add('asunto', 'required');
        $validator->add('mensaje', 'required');

        if ($validator->validate($_POST)) {
            $mail = new phpmailer();

            $mail->Host = 'mail.soluticket.com.mx';
            $mail->SMTPAuth = true;
            $mail->Username = 'contacto@soluticket.com.mx';
            $mail->Password = 'cto_2018';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 465;

            $mail->setFrom($_POST['correo']);
            $mail->addAddress('contacto@soluticket.com.mx');
            $mail->addAttachment('images/logo.png');

            $mail->IsHTML(true);
            $mail->Subject = $_POST['nombre'].' - '.$_POST['asunto'];
            $mail->Body = '<br><strong><h3>Has recibido un nuevo mensaje desde la pagina de Soluticket.</h3></strong><br><br>'.$_POST['mensaje'];
            $mail->AltBody = $_POST['nombre'].$_POST['asunto'].'/'.$_POST['mensaje'];

            if (!$mail->send()) {
                header('Location:'.BASE_URL.'error500');
            } else {
                header('Location:'.BASE_URL.'');
            }
        }
    }

    public function getError500()
    {
        return $this->render('error500.twig');
    }

    public function getNosotros()
    {
        $nosotros = Nosotros::all();
        $cabecera = Head::all();
        $footer = Pie::all();
        $pfacebook = Evento::query()->where('publicado', 1)->get();

        return $this->render('nosotros.twig', [
            'cabecera' => $cabecera,
            'footer' => $footer,
            'pfacebook' => $pfacebook,
            'nosotros' => $nosotros,
        ]);
    }

    public function getEventos()
    {
        $categorias = Categoria::all();
        $eventos = Evento::query()->where('publicado', 1)->orderBy('fecha', 'asc')->get();
        $eventostodos = Evento::query()->where('publicado', 1)->orderBy('fecha', 'asc')->get();
        $google = Google::query()->where('seccion', 2)->get();
        $cabecera = Head::all();
        $pfacebook = Evento::query()->where('publicado', 1)->get();
        $footer = Pie::all();

        return $this->render('eventos.twig', [
            'cabecera' => $cabecera,
            'footer' => $footer,
            'eventos' => $eventos,
            'categorias' => $categorias,
            'eventostodos' => $eventostodos,
            'pfacebook' => $pfacebook,
            'google' => $google,
        ]);
    }

    public function postEventos()
    {
        if (isset($_POST['ciudad'])) {
            $eventos = Evento::query()->where('publicado', 1)->orderBy('fecha', 'asc')->get();
            $eventosolo = Evento::where('ciudad', $_POST['ciudad'])->get();
            $google = Google::query()->where('seccion', 2)->get();
            $cabecera = Head::all();
            $categorias = Categoria::all();
            $pfacebook = Evento::query()->where('publicado', 1)->get();
            $footer = Pie::all();

            return $this->render('eventos.twig', [
                'cabecera' => $cabecera,
                'categorias' => $categorias,
                'footer' => $footer,
                'google' => $google,
                'eventos' => $eventos,
                'eventosolo' => $eventosolo,
                'pfacebook' => $pfacebook,
            ]);
        } elseif (isset($_POST['categoria'])) {
            $eventos = Evento::query()->where('publicado', 1)->orderBy('fecha', 'asc')->get();
            $categoriasola = Evento::where('categoria', $_POST['categoria'])->get();
            $google = Google::query()->where('seccion', 2)->get();
            $cabecera = Head::all();
            $categorias = Categoria::all();
            $pfacebook = Evento::query()->where('publicado', 1)->get();
            $footer = Pie::all();

            return $this->render('eventos.twig', [
                'cabecera' => $cabecera,
                'categorias' => $categorias,
                'footer' => $footer,
                'google' => $google,
                'eventos' => $eventos,
                'categoriasola' => $categoriasola,
                'pfacebook' => $pfacebook,
            ]);
        }
    }

    public function getVerevento($id)
    {
        $eventos = Evento::query()->where('id', $id)->get();
        $google = Google::query()->where('seccion', 3)->get();
        $puntosdeventa = Puntodeventa::query()->where('evento', $id)->get();
        $detalles = Detallevento::query()->where('eventos', $id)->get();
        $cabecera = Head::all();
        $footer = Pie::all();
        $pfacebook = Evento::query()->where('publicado', 1)->get();

        return $this->render('ver-evento.twig', [
            'cabecera' => $cabecera,
            'footer' => $footer,
            'eventos' => $eventos,
            'google' => $google,
            'puntosdeventa' => $puntosdeventa,
            'detalles' => $detalles,
            'pfacebook' => $pfacebook,
        ]);
    }

    public function getFaq()
    {
        $cabecera = Head::all();
        $footer = Pie::all();
        $faqs = Faq::query()->orderBy('created_at', 'desc')->get();
        $pfacebook = Evento::query()->where('publicado', 1)->get();

        return $this->render('faq.twig', [
            'cabecera' => $cabecera,
            'footer' => $footer,
            'pfacebook' => $pfacebook,
            'faqs' => $faqs,
        ]);
    }

    public function getAviso()
    {
        $avisos = Aviso::all();
        $cabecera = Head::all();
        $pfacebook = Evento::query()->where('publicado', 1)->get();
        $footer = Pie::all();

        return $this->render('aviso.twig', [
            'cabecera' => $cabecera,
            'footer' => $footer,
            'pfacebook' => $pfacebook,
            'avisos' => $avisos,
        ]);
    }

    public function getTerminos()
    {
        $cabecera = Head::all();
        $footer = Pie::all();
        $pfacebook = Evento::query()->where('publicado', 1)->get();
        $terminos = Termino::all();

        return $this->render('terminos.twig', [
            'cabecera' => $cabecera,
            'footer' => $footer,
            'pfacebook' => $pfacebook,
            'terminos' => $terminos,
        ]);
    }

    public function getNoticias()
    {
        $cabecera = Head::all();
        $pfacebook = Evento::query()->where('publicado', 1)->get();
        $footer = Pie::all();
        $blog = BlogPublicacion::query()->orderBy('fecha', 'desc')->get();

        return $this->render('noticias.twig', [
            'cabecera' => $cabecera,
            'footer' => $footer,
            'pfacebook' => $pfacebook,
            'blog' => $blog,
        ]);
    }

    public function getVernoticia($id)
    {
        $cabecera = Head::all();
        $footer = Pie::all();
        $pfacebook = Evento::query()->where('publicado', 1)->get();
        $blog = BlogPublicacion::query()->where('id', $id)->get();
        $google = Google::query()->where('seccion', 2)->get();

        return $this->render('ver-noticia.twig', [
            'cabecera' => $cabecera,
            'footer' => $footer,
            'pfacebook' => $pfacebook,
            'blog' => $blog,
            'google' => $google,
        ]);
    }
}
