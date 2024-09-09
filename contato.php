<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Para carregar o Dotenv

$ok = 0;

if (isset($_POST['email'])) {

    $nome   = $_POST['nome'];
    $email  = $_POST['email'];
    $fone   = $_POST['fone'];
    $mens   = $_POST['mens'];

    require_once('admin/class/ClassContato.php');

    $contato = new ClassContato();
    $contato->nomeContato = $nome;
    $contato->emailContato = $email;
    $contato->telefoneContato = $fone;
    $contato->mensagemContato = $mens;
    $contato->statusContato = "ATIVO";

    $contato->Inserir();

    // Carregar as variáveis do .env
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    // Criar uma instância do PHPMailer
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['SMTP_USER'];
        $mail->Password   = $_ENV['SMTP_PASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Destinatários
        $mail->setFrom($_ENV['SMTP_USER'], 'Teacher Camila');
        $mail->addAddress('njuapedrofrediani04@gmail.com');

        // Conteúdo
        $mail->isHTML(true);
        $mail->Subject = 'Teacher Camila';
        $mail->Body    = "
            <strong> Mensagem da Teacher Camila </strong><br><br>
            <strong> Nome: </strong> $nome <br>
            <strong> Email: </strong> $email <br>
            <strong> Telefone: </strong> $fone <br>
            <strong> Mensagem: </strong> $mens
        ";
        $mail->AltBody = "
            <strong> Mensagem da Teacher Camila </strong><br><br>
            <strong> Nome: </strong> $nome <br>
            <strong> Email: </strong> $email <br>
            <strong> Telefone: </strong> $fone <br>
            <strong> Mensagem: </strong> $mens    
        ";

        $mail->send();
        $ok = 1;
    } catch (Exception $e) {
        $ok = 2;
        echo "Erro do Mailer: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Teacher Camila</title>

    <!--Link para o arquivo que da um reset maneiro na pagina (Tem que ficar antes pq se n reseta tudo mo perigo)-->
    <link rel="stylesheet" href="css/reset.css">

    <!--Ícones do Google-->

    <!--Location-ON-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <!--Mail-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <!--Slick-->

    <link rel="stylesheet" href="assets/css/slick.css">

    <link rel="stylesheet" href="assets/css/slick-theme.css">

    <!--Animate CSS-->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <!--Link pro css do site pra ficar bonitinho-->

    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Responsivo -->
    <link rel="stylesheet" href="assets/css/responsive.css">

    <!--ícone-->
    <link rel="shortcut icon" type="text/html" href="assets/img/logo.ico">

</head>

<body>

    <main>
        <section class="form">
            <div class="site">
                <h2>Contato Teacher Camila</h2>

                <h3 class="confirm">
                    <?php
                    if ($ok == 1) {
                        echo $nome . ", sua mensagem foi enviada com sucesso.";
                    } else if ($ok == 2) {
                        echo $nome . ", não foi possível enviar sua mensagem.";
                    }
                    ?>
                </h3>

                <form action="#" method="POST">
                    <div>
                        <div>
                            <input type="text" name="nome" id="nome" placeholder="Informe seu nome:" required> <!--o nome e o id é como se fosse a variavel, o placeholder é pro texto ficar dentro do input q é uma caixa q tem o tipo texto e o required é para ser um campo obrigatorio, tendeu?-->
                        </div>
                        <div>
                            <input type="email" name="email" id="email" placeholder="Informe seu email:" required>
                        </div>
                        <div>
                            <input type="tel" name="tel" id="tel" placeholder="Informe seu telefone" required>
                        </div>
                    </div>
                    <div>
                        <div>
                            <textarea name="mens" id="mens" cols="30" rows="10" placeholder="Informe sua mensagem:"></textarea>
                        </div>
                        <div>
                            <input type="submit" value="Enviar por e-mail">
                            <button onclick="EnviarWhats()"> Enviar por WhatsApp </button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <!--Rodapé-->
    <?php require_once('partials/rodape.php') ?>

</body>