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
      public $status = array( 'codigo_status' => null, 'descricao_status' => '' );

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
        header('Location: index.php');
    }

    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = false;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.mailtrap.io';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'test@mailhog.local';                     //SMTP username
        $mail->Password   = 'secret';                               //SMTP password
        $mail->SMTPSecure = 'tls';         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 2525;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('test@mailhog.local', 'Spaceatm Remetente');
        $mail->addAddress($mensagem->__get('para'));     //Add a recipient
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addAddress('ellen@example.com');               //Name is optional
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $mensagem->__get('assunto');
        $mail->Body    = $mensagem->__get('mensagem');
        $mail->AltBody = 'Necessário utilizar um cliente que suporte HTML para essa mensagem';

        $mail->send();
        $mensagem->status['codigo_status'] = 1;
        $mensagem->status['descricao_status'] = 'E-mail enviado com sucesso';
    } catch (Exception $e) {

      $mensagem->status['codigo_status'] = 2;
      $mensagem->status['descricao_status'] = '🙁 Não foi possível enviar este e-mail, por favor tente novamente Detalhes do erro: ' . $mail->ErrorInfo;

    }

    ?>

  <html>
      <head>
      <meta charset="utf-8" />
        <title>App Mail Send</title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="style.css">
      </head>
      <body>

          <div class="container">
              <div class="py-3 text-center">
                  <img class="d-block mx-auto mb-2" src="images/logo.png" alt=""  height="80">
                  <h2>Send Mail</h2>
                  <p class="lead">Seu app de envio de e-mails particular!</p>
              </div>
          </div>

          <div class="row">
              <div class="col-md-12">
                  <? if($mensagem->status['codigo_status'] == 1) { ?>
                      <div class="container">
                          <h1 class="display-4 text-sucess">Sucesso</h1>
                          <p><?= $mensagem->status['descricao_status'] ?></p>
                          <a href="index.php" class="btn btn-sucess btn-lg mt-5 text-white">Voltar</a>
                      </div>
                <? } ?>

                  <? if($mensagem->status['codigo_status'] == 2) { ?>
                    <div class="container">
                          <h1 class="display-4 text-danger">Ops!</h1>
                          <p><?= $mensagem->status['descricao_status'] ?></p>
                          <a href="index.php" class="btn btn-sucess btn-lg mt-5 text-white">Voltar</a>
                      </div>
                <? } ?>
              </div>
          </div>
        
      </body>
  </html>