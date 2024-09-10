<?php

ini_set('session.gc_maxlifetime', 43200); // 12 horas em segundos
session_set_cookie_params(43200); // 12 horas em segundos

session_start();
$tipo = '';
$nomeAluno = '';

if (isset($_SESSION['idAluno'])) {

    require_once('admin/class/ClassAluno.php');
    $tipo = 'aluno';

    // Criar uma instância do ClassMecanico e obter o nome
    $aluno = new ClassAluno($_SESSION['idAluno']);
    $nomeAluno = $aluno->nomeAluno;
    $fotoAluno = $aluno->fotoAluno;
    $altAluno = $aluno->altAluno;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Camila</title>

    <!--Links-->
    <link rel="stylesheet" href="assets/css/reset.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Responsivo -->
    <link rel="stylesheet" href="assets/css/responsive.css">

    <!--Bootstrap Icons-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!--ícone-->
    <link rel="icon" type="image/ico" href="assets/img/logo.ico"/>
</head>

<body>

    <!--Cabeçalho-->
    <header>

        <!--Faixa do Topo-->
        <?php require_once('partials/topo.php'); ?>

    </header>

    <?php
    if (isset($_GET['pa'])) {
        $pagina = $_GET['pa'];
    } else {
        $pagina = 'default'; // ou alguma outra página padrão
    }

    // Verificar se a página é agendamento e se o usuário não está logado
    if ($pagina === 'agendamento' && $tipo !== 'aluno') {
        header("Location: login");
        exit();
    }

    if ($pagina === 'aluno' && $tipo !== 'aluno') {
        header("Location: login");
        exit();
    }

    switch ($pagina) {
        case 'sobre':
            $titulo = "Sobre";
            require_once('sobre.php');
            break;
        case 'aluno':
            $titulo = "Aluno";
            require_once('aluno.php');
            break;
        case 'agendamento':
            $titulo = "Agendamento";
            require_once('agendamento.php');
            break;
        case 'contato':
            $titulo = "Contato";
            require_once('contato.php');
            break;
        case 'meuPerfil':
            $titulo = "Meu Perfil";
            require_once('meuPerfil.php');
            break;
        default:
            # code...
            break;
    }
    ?>

    <?php if ($pagina === 'default') { ?>

        <!--Corpo-->

        <!--Subtitulo-->
        <style>
            .subtitulo {
                padding: 55px 0;
            }
        </style>
        <?php require_once('partials/subtitulo.php') ?>

        <main>
            <!--Agendamento Banner-->
            <?php require_once('partials/agendamento.php') ?>

            <!--Informações-->
            <?php require_once('partials/info.php') ?>

            <!--Sobre-->
            <?php require_once('partials/sobre.php') ?>
        </main>

        <!--Rodapé-->
        <?php require_once('partials/rodape.php') ?>
    <?php } ?>

    <!--jQuery-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> <!--sempre o primeiro pq o bicho é enjoado slc kk-->

    <!--WOW-->
    <script src="assets/js/wow.min.js"></script>

    <script src="https://unpkg.com/scrollreveal@4.0.0/dist/scrollreveal.min.js"></script>

    <script src="https://unpkg.com/scrollreveal"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    <!--Sclick JS-->
    <script src="assets/js/slick.min.js"></script>

    <script src="assets/js/script.js"></script> <!--sempre o ultimo blz? (esse aq é humilde)-->
</body>

</html>