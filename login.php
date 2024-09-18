<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!--ícone-->
    <link rel="shortcut icon" type="text/html" href="assets/img/logo.ico">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!--Bootstrap Icons-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .vh-100{
            height: 0%;
        }

        .h-100{
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
            /* fallback for old browsers */
            background: #D0006F;

            /* Chrome 10-25, Safari 5.1-6 */
            background: -webkit-linear-gradient(to right, rgba(97, 0, 52), rgba(97, 0, 52));

            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
            background: linear-gradient(to right, rgba(208, 0, 111), rgba(97, 0, 52))
        }

        .itemMenu{
            width: 100%;
            display: flex;
            align-items: center;
        }

        .itemMenu a{
            padding: 30px 0 0 100px;
            width: 10%;
        }

        .txtLink:hover{
            color: white;
        }

        .text-center{
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

        a:hover{
            color: white!important;
            transform: scale(1.07)!important; /* Zoom na imagem */
        }
    </style>
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Responsivo -->
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>

<body>
    <section class="vh-100 gradient-custom">
        <div class="itemMenu">
            <a href="index">
                <span class="icon"><i class="bi bi-arrow-left-circle"></i></span>
                <span class="txtLink">Voltar</span>
            </a>
        </div>
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-dark text-white" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">

                            <form class="mb-md-5 mt-md-4 pb-5 site" id="formLogin">
                                <img src="assets/img/logo.svg" alt="Logo teacher Camila">
                                <br><br><br><br>
                                <h2 class="fw-bold mb-2 text-uppercase">Login</h2>
                                <br>
                                <p class="text-white-50 mb-5">Por favor informe seu e-mail para efetuar o login</p>
                                <br>
                                <div data-mdb-input-init class="form-outline form-white mb-4">
                                    <label class="form-label" for="typeEmailX">Email</label>
                                    <br>
                                    <input name="email" type="email" id="typeEmailX" class="form-control form-control-lg" placeholder="Digite seu email"/>
                                </div>
                                <div data-mdb-input-init class="form-outline form-white mb-4">
                                    <label class="form-label" for="typePasswordX">Senha</label>
                                    <br>
                                    <input name="senha" type="password" id="typePasswordX" class="form-control form-control-lg" placeholder="Digite sua senha"/>
                                </div>
                                <p class="small mb-5 pb-lg-2"><a class="text-white-50" href="#!">Esqueceu sua senha?</a></p>
                                <button data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-light btn-lg px-5" type="button" onclick="Login()">Login</button>
                            </form>
                            <div>
                                <p class="mb-0">Não tem conta? <a href="cadastrar.php" class="text-white-50 fw-bold">Solicite seu Cadastro!</a></p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

    <script src="assets/js/script.js"></script>

</body>

</html>