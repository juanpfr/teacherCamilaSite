<?php

session_start();
$tipo = '';

if (isset($_SESSION['id'])) {

    $tipo = 'professora';
} else {

    header('Location:http://localhost/teacherCamilaSite/admin/login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Teacher Camila</title>

    <!--Reset CSS-->
    <link rel="stylesheet" href="../css/reset.css">

    <!--Bootstrap Icons-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!--Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!--Estilo CSS (Dashboard)-->

    <link rel="stylesheet" href="css/style.css">

</head>

<body>
    <div>
        <div class="topo">
            <div>
                <h1></h1>
            </div>
            <div>
                <h2>Dashboard Teacher Camila</h2>
            </div>
            <div>
                <a href="http://localhost/teacherCamilaSite/admin/destroy_session.php">
                    <i id="btnSair" class="bi bi-box-arrow-left"></i>
                </a>
            </div>
        </div>
        <div class="conteudo">
            <nav class="menuLateral">
                <ul class="ul">
                    <li class="itemMenu">
                        <a href="index.php?p=dashboard">
                            <span class="icon"><i class="bi bi-columns-gap"></i></span>
                            <span class="txtLink">Dashboard</span>
                        </a>
                    </li>
                    <li class="itemMenu">
                        <a href="index.php?p=aluno">
                            <span class="icon"><i class="bi bi-person-fill-add"></i></span>
                            <span class="txtLink">Aluno</span>
                        </a>
                    </li>
                    <li class="itemMenu">
                        <a href="index.php?p=atividade">
                            <span class="icon"><i class="bbi bi-filetype-docx"></i></i></span>
                            <span class="txtLink">Atividade</span>
                        </a>
                    </li>
                    <li class="itemMenu">
                        <a href="index.php?p=aula">
                            <span class="icon"><i class="bi bi-images"></i></span>
                            <span class="txtLink">Aula</span>
                        </a>
                    </li>
                    <li class="itemMenu">
                        <a href="index.php?p=banner">
                            <span class="icon"><i class="bi bi-card-image"></i></span>
                            <span class="txtLink">Banner</span>
                        </a>
                    </li>
                    <li class="itemMenu">
                        <a href="index.php?p=contato">
                            <span class="icon"><i class="bi bi-chat-left-text-fill"></i></span>
                            <span class="txtLink">Contato</span>
                        </a>
                    </li>
                    <li class="itemMenu">
                        <a href="index.php?p=frase">
                            <span class="icon"><i class="bi bi-body-text"></i></span>
                            <span class="txtLink">Frase</span>
                        </a>
                    </li>
                    <li class="itemMenu">
                        <a href="index.php?p=game">
                            <span class="icon"><i class="bi bi-controller"></i></span>
                            <span class="txtLink">Game</span>
                        </a>
                    </li>
                    <li class="itemMenu">
                        <a href="index.php?p=solicitacaoCadastro">
                            <span class="icon"><i class="bi bi-question-circle"></i></span>
                            <span class="txtLink">So. Cadastro</span>
                        </a>
                    </li>
                    <li class="itemMenu">
                        <a href="index.php?p=solicitacaoAula">
                            <span class="icon"><i class="bi bi-question-circle"></i></span>
                            <span class="txtLink">So. Aula</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="site">
                <?php

                $pagina = $_GET['p'];

                switch ($pagina) {
                    case 'dashboard':
                        $titulo = "Dashboard";
                        require_once('dashboard/dashboard.php');
                        break;
                    case 'aluno':
                        $titulo = "Aluno";
                        require_once('aluno/aluno.php');
                        break;
                    case 'atividade':
                        $titulo = "Atividade";
                        require_once('atividade/atividade.php');
                        break;
                    case 'aula':
                        $titulo = "Aula";
                        require_once('aula/aula.php');
                        break;
                    case 'banner':
                        $titulo = "Banner";
                        require_once('banner/banner.php');
                        break;
                    case 'contato':
                        $titulo = "Contato";
                        require_once('contato/contato.php');
                        break;
                    case 'frase':
                        $titulo = "Frase";
                        require_once('frase/frase.php');
                        break;
                    case 'game':
                        $titulo = "Game";
                        require_once('game/game.php');
                        break;
                    case 'solicitacaoCadastro':
                        $titulo = "Solicitação";
                        require_once('solicitacaoCadastro/solicitacaoCadastro.php');
                        break;
                    case 'solicitacaoAula':
                        $titulo = "Solicitação";
                        require_once('solicitacaoAula/solicitacaoAula.php');
                        break;
                    default:
                        # code...
                        break;
                }

                echo '<h2>' . $pagina . '</h2>';
                ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>