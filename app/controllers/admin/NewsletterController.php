<?php
namespace app\controllers\Admin;

use app\controllers\BaseController;
use app\models\Newsletter;
use Sirius\Validation\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class NewsletterController extends BaseController
{
    public function getIndex()
    {

        $newsletter = Newsletter::query()->orderBy('created_at', 'desc')->get();

        return $this->render('admin/newsletter.twig', ['newsletter' => $newsletter]);

    }

    public function postIndex()
    {
        $nombre = Newsletter::query()->where('nombre')->orderBy('created_at','desc')->get();
        $apellido = Newsletter::query()->where('apellido')->orderBy('created_at', 'desc')->get();
        $ciudad = Newsletter::query()->where('ciudad')->orderBy('created_at','desc')->get();
        $correos = Newsletter::query()->where('correo')->orderBy('created_at', 'desc')->get();
        $alta = Newsletter::query()->where('created_at')->orderBy('created_at', 'desc')->get();


		// Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
		// Propiedades del documento
        $spreadsheet->getProperties()->setCreator('Maarten Balliauw')
            ->setLastModifiedBy('Maarten Balliauw')
            ->setTitle('Office 2007 XLSX Test Document')
            ->setSubject('Office 2007 XLSX Test Document')
            ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
            ->setKeywords('office 2007 openxml php')
            ->setCategory('Test result file');

        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', $nombre)
            ->setCellValue('B1', $apellido)
            ->setCellValue('C1', $correos)
            ->setCellValue('D1', $alta);

        $spreadsheet->getActiveSheet()->setTitle('Newsletter');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="newsletter_soluticket.xlsx"');

        $excel_writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $excel_writer->save('php://output');

        header('Location:' . BASE_URL . 'admin/newsletter');

    }

    public function getNuevonewsletter()
    {

        $emails = Newsletter::all();

        $newsletternew = Newsletter::all();

        return $this->render('admin/nuevonewsletter.twig', [
            'newsletternew' => $newsletternew,
            'emails' => $emails
        ]);

    }

    public function postNuevonewsletter()
    {


        $validator = new Validator();
        $validator->add('destino', 'required');
        $validator->add('asunto', 'required');

        if ($validator->validate($_POST)) {

            $mail = new phpmailer();

            $mail->Host = 'email.losfumancheros.com.mx';
            $mail->SMTPAuth = true;
            $mail->Username = 'contacto@losfumancheros.com.mx';
            $mail->Password = "*#NBf83.O+]G";
            $mail->SMTPSecure = 'tls';
            $mail->Port = 465;

            $mail->setFrom('contacto@losfumancheros.com.mx');
            $mail->addAddress($_POST['destino']);
            $mail->addAttachment('images/logo.png');

            $mail->IsHTML(true);
            $mail->Subject = 'contacto@losfumancheros.com.mx' . 'No responder';
            $mail->Body = '<br><strong><h3>Has recibido un nuevo mensaje de Los fumancheros Oficial Website.</h3></strong><br><br>' . $_POST['asunto'];
            $mail->AltBody = $_POST['asunto'];
            $mail->msgHTML(file_get_contents('newsletter/index.html'), dirname(__FILE__));

            if (!$mail->send()) {
                echo 'Ha habido un error';
            } else {
                header('Location:' . BASE_URL . 'admin/');
            }
        }

    }

    public function getEliminar($postid)
    {

        $newsletter = Newsletter::find($postid);
        $newsletter->delete();

        header('Location:' . BASE_URL . 'admin/newsletter');
    }

}