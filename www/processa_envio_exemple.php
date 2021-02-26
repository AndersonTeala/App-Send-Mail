<?php
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\OAuth;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\POP3;
use PHPMailer\PHPMailer\SMTP;

require "vendor/autoload.php";

    //print_r($_POST);

    class Mensagem {
      private $para = null;
      private $assunto = null;
      private $mensagem = null;

      public function __get($atributo) {
        return $this->$atributo;
      }

      public function __set($atributo, $valor) {
        $this->$atributo = $valor;
      }

      public function mensagemValida() {
        if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)){
          return false;
        } else {
          return true;
        }
      }
 
    }

    $mensagem = new Mensagem();

    $mensagem->__set('para', $_POST['para']);
    $mensagem->__set('assunto', $_POST['assunto']);
    $mensagem->__set('mensagem', $_POST['mensagem']);

    //print_r($mensagem);

    if(!$mensagem->mensagemValida()) {
        echo 'Mensagem não é válida';
        die();
    }

    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.mailtrap.io';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'test@mailhog.local';                     //SMTP username
        $mail->Password   = 'secret';                               //SMTP password
        $mail->SMTPSecure = 'tls';         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 2525;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('test@mailhog.local', 'Spaceatm Remetente');
        $mail->addAddress('test@mailhog.local', 'Spaceatm Destinatário');     //Add a recipient
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addAddress('ellen@example.com');               //Name is optional
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Oi. Eu sou o assunto';
        $mail->Body    = 'Oi. Eu sou o conteúdo do <strong>e-mail</strong>.';
        $mail->AltBody = 'Oi. Eu sou o conteúdo do e-mail';

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "🙁 Não foi possível enviar este e-mail, por favor tente novamente Detalhes do erro: {$mail->ErrorInfo}";
    }