<?php
require_once('admin/class/Conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtendo os dados do formulário
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Inserindo os dados na tabela tbl_solicitacao_cadastro
    $sql = "INSERT INTO tbl_solicitacao_cadastro (nomeSolicitacaoCadastro, telefoneSolicitacaoCadastro, emailSolicitacaoCadastro, senhaSolicitacaoCadastro, statusSolicitacaoCadastro)
            VALUES (:nome, :telefone, :email, :senha, 'PENDENTE')";

    $conn = Conexao::LigarConexao();
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);

    if ($stmt->execute()) {
        echo "<script>alert('Solicitação enviada com sucesso!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Erro ao enviar a solicitação.'); window.location.href='index.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Cadastro</title>

    <!-- Responsivo -->
    <link rel="stylesheet" href="assets/css/responsive.css">

    <!-- ícone -->
    <link rel="shortcut icon" type="text/html" href="assets/img/logo.ico">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .vh-100{
            height: auto!important;
        }

        .h-100 {
            height: auto!important;
        }

        .align-items-center {
            align-items: auto;
        }

        img {
            width: 200px;
            height: 100px;
        }

        .site {
            margin: 0 !important;
        }

        .gradient-custom {
            background: #D0006F;
            background: -webkit-linear-gradient(to right, rgba(97, 0, 52), rgba(97, 0, 52));
            background: linear-gradient(to right, rgba(208, 0, 111), rgba(97, 0, 52))
        }

        .itemMenu {
            width: 100%;
            display: flex;
            align-items: center;
        }

        .itemMenu a {
            padding: 30px 0 0 100px;
            width: 10%;
        }

        .txtLink:hover {
            color: white;
        }

        .text-center {
            margin-top: -3.4%;
        }

        .bi-arrow-left-circle::before {
            color: white;
        }

        a {
            transition: transform 0.3s, filter 0.3s!important;
            color: white!important;
            text-decoration: underline;
        }

        a:hover {
            color: white!important;
            transform: scale(1.07)!important;
        }
    </style>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <section class="vh-100 gradient-custom">
        <div class="itemMenu">
            <a href="index.php">
                <span class="icon"><i class="bi bi-arrow-left-circle"></i></span>
                <span class="txtLink">Voltar</span>
            </a>
        </div>
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-dark text-white" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">

                            <form class="mb-md-5 mt-md-4 pb-5 site" id="formCadastro" method="POST">
                                <img src="img/logo.svg" alt="Logo teacher Camila">
                                <br><br><br><br>
                                <h2 class="fw-bold mb-2 text-uppercase">Solicitar Cadastro</h2>
                                <br>
                                <p class="text-white-50 mb-5">Solicite um cadastro para ter acesso à área do aluno e poder agendar sua aula! Por favor, use um email válido para receber a confirmação.</p>
                                <br>
                                <div data-mdb-input-init class="form-outline form-white mb-4">
                                    <label class="form-label" for="typeNameX">Nome</label>
                                    <br>
                                    <input name="nome" type="text" id="typeNameX" class="form-control form-control-lg" placeholder="Digite seu nome" required/>
                                </div>
                                <div data-mdb-input-init class="form-outline form-white mb-4">
                                    <label class="form-label" for="typePhoneX">Telefone</label>
                                    <br>
                                    <input name="telefone" type="tel" id="typePhoneX" class="form-control form-control-lg" placeholder="(xx) xxxxx-xxxx" required/>
                                </div>
                                <div data-mdb-input-init class="form-outline form-white mb-4">
                                    <label class="form-label" for="typeEmailX">Email</label>
                                    <br>
                                    <input name="email" type="email" id="typeEmailX" class="form-control form-control-lg" placeholder="Digite seu email" required/>
                                </div>
                                <div data-mdb-input-init class="form-outline form-white mb-4">
                                    <label class="form-label" for="typePasswordX">Senha</label>
                                    <br>
                                    <input name="senha" type="password" id="typePasswordX" class="form-control form-control-lg" placeholder="Digite sua senha" required/>
                                </div>
                                <button data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-light btn-lg px-5" type="submit">Solicitar Cadastro</button>
                            </form>
                            <div>
                                <p class="mb-0">Já tem uma conta? <a href="login.php" class="text-white-50 fw-bold">Faça Login!</a></p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#typePhoneX').mask('(00) 00000-0000', {placeholder: "(xx) xxxxx-xxxx"});
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

    <script src="js/script.js"></script>

</body>

</html>
