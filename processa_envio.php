<?php

	require "./lib/PHPMailer/Exception.php";
	require "./lib/PHPMailer/OAuth.php";
	require "./lib/PHPMailer/PHPMailer.php";
	require "./lib/PHPMailer/POP3.php";
	require "./lib/PHPMailer/SMTP.php";

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	#print_r($_POST);


	class Mensagem {

		private $para = null;
		private $assunto = null;
		private $mensagem = null;

		public $status = array('codigo_status' => null, 'descricao_status' => '');

		public function __get($atributo){
			return $this->$atributo;
		}


		public function __set($atributo, $valor) {
			$this->$atributo = $valor;
		}


		public function mensagemValida() {

			if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)) {
				return false;
			}

			return true;
		}

	}



	$mensagem = new Mensagem();


	#SETANDO OS VALORES RECEBIDOS POR POST NO OBJETO
	$mensagem->__set('para', $_POST['para']);
	$mensagem->__set('assunto', $_POST['assunto']);
	$mensagem->__set('mensagem', $_POST['mensagem']);

	#print_r($mensagem);

	if(!$mensagem->mensagemValida()) {
		echo "Mensagem Não é valida";
		header("Location: index.php");
		exit;
	}

	$mail = new PHPMailer(true);

	try {
	    //Server settings
	    $mail->SMTPDebug = false;//SMTP::DEBUG_SERVER;                      //Enable verbose debug output
	    $mail->isSMTP();                                            //Send using SMTP
	    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
	    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
	    $mail->Username   = 'desenvolvimentophpteste@gmail.com';                     //SMTP username
	    $mail->Password   = 'gvujofhjkmharvpl';                               //SMTP password
	    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
	    $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

	    //Recipients
	    $mail->setFrom('desenvolvimentophpteste@gmail.com', 'Desenv');
	    $mail->addAddress($mensagem->__get('para'));     //Add a recipient
	    // $mail->addReplyTo('info@example.com', 'Information'); 
	    // $mail->addCC('cc@example.com');
	    // $mail->addBCC('bcc@example.com');

	    //Attachments
	    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
	    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

	    //Content
	    $mail->isHTML(true);                                  //Set email format to HTML
	    $mail->Subject = $mensagem->__get('assunto');
	    $mail->Body    = $mensagem->__get('mensagem');
	    $mail->AltBody = 'É necessário usar um client que suporte HTML';

	    $mensagem->status['codigo_status'] = 1;
	    $mensagem->status['descricao_status'] = 'Email enviado com sucesso';
	    
	    $mail->send();
	} catch (Exception $e) {

		$mensagem->status['codigo_status'] = 2;
	    $mensagem->status['descricao_status'] = "Não foi possível enviar o email; detalhes do erro : {$mail->ErrorInfo}";
	}

?>

<html>
	<head>
		<meta charset="utf-8" />
    	<title>App Mail Send</title>

    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

	</head>
<body>

	<div class="container">
		<div class="py-3 text-center">
			<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
			<h2>Send Mail</h2>
			<p class="lead">Seu app de envio de e-mails particular!</p>
		</div>

		<div class="row">
			<div class="col-md-12">
				<?php if($mensagem->status['codigo_status'] === 1): ?>
					<div class="container">
						<h1 class="display-4 text-success text-center">Sucesso</h1>
						<p class="text-center"><?= $mensagem->status['descricao_status']?></p>
						<a href="index.php" class="btn btn-success btn-lg mt-5 text-whtie d-block m-auto">Voltar</a>
					</div>
				<?php endif; ?>

				<?php if($mensagem->status['codigo_status'] === 2): ?>
					<div class="container">
						<h1 class="display-4 text-danger text-center">Ops!! Falha o enviar seu email</h1>
						<p class="text-center"><?= $mensagem->status['descricao_status']?></p>
						<a href="index.php" class="btn btn-danger btn-lg mt-5 text-whtie d-block m-auto">Voltar</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</body>
</html>