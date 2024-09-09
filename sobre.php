<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Camila</title>

    <!--Links-->
    <link rel="stylesheet" href="assets/css/reset.css">

    <link rel="stylesheet" href="css/slick.css">

    <link rel="stylesheet" href="css/slick-theme.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <link rel="stylesheet" href="css/style.css">

    <!-- Responsivo -->
    <link rel="stylesheet" href="css/responsive.css">
</head>



</head>

<body>
    <!--Corpo-->
    <main>

        <!--Subtitulo-->
        <?php require_once('partials/subtitulo.php'); ?>

        <article class="info">
            <div class="wow animate__animated animate__fadeInUp">
                <h2>Informações</h2>
            </div>
        </article>

        <!--Informações (1.0)-->
        <?php require_once('partials/info2.php') ?>

        <!--Sobre-->
        <?php require_once('partials/sobre.php') ?>
    </main>

    <!--Rodapé-->
    <?php require_once('partials/rodape.php') ?>

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