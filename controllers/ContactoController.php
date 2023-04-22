<?php
    class ContactoController extends Controller {
        public function index() {
            $this->loadView('contacto');
        }


    public function send() {
        if(empty($_POST['enviar'])) {
            throw new Exception('No se recibió el formulario de contacto.');            
        }

        $from = $_POST['email'];
        $name = $_POST['nombre'];
        $subject = $_POST['asunto'];
        $message = $_POST['mensaje'];

        $email = new Email(ADMIN_EMAIL, $from, $name, $subject, $message);

        if(!$email->send()) {
            throw new Exception('No se pudo enviar el email de contacto.');
        }

        Session::flash('success', "Mensaje enviado, recibirás una respuesta en la mayor brevedad posible.");
        redirect('/');
    }
}