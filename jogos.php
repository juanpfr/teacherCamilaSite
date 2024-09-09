<?php
require_once('./admin/class/ClassGame.php');

$game = new ClassGame();
$lista = $game->Listar();

//print_r($lista);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>

  <!--Links-->
  <link rel="stylesheet" href="assets/css/reset.css">

  <link rel="stylesheet" href="assets/css/slick.css">

  <link rel="stylesheet" href="css/slick-theme.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

  <link rel="stylesheet" href="assets/css/style.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css" integrity="sha512-yHknP1/AwR+yx26cB1y0cjvQUMvEa2PFzt1c9LlS4pRQ5NOTZFWbhBig+X9G9eYW/8m0/4OXNx8pxJ6z57x0dw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css" integrity="sha512-17EgCFERpgZKcm0j0fEq1YCJuyAWdz9KUtv1EjVuaOz8pDnh/0nZxmU6BBXwaaxqoi9PQXnRWqlcDB027hgv9A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!--Responsivo-->

  <link rel="stylesheet" href="assets/css/responsive.css">

</head>

<body>

  <!--JOGOS-->
  <article class="jogos">
    <h2>Aprenda seu inglês jogando!</h2>
    <section class="catalogo">

      <?php foreach ($lista as $linha) : ?>
        <div class="boxgame">
          <h3><?php echo $linha['nomeGame'] ?></h3>
          <a href="<?php echo $linha['linkGame'] ?>"><img src="admin/img/<?php echo $linha['fotoGame'] ?>" alt="<?php echo $linha['nomeGame'] ?>"></a>
          <p>
            <?php echo $linha['descricaoGame'] ?>
          </p>
        </div>
      <?php endforeach; ?>
    </section>
  </article>

  <!--jQuery sempre antes de acabar o body-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-migrate/3.4.1/jquery-migrate.min.js" integrity="sha512-KgffulL3mxrOsDicgQWA11O6q6oKeWcV00VxgfJw4TcM8XRQT8Df9EsrYxDf7tpVpfl3qcYD96BpyPvA4d1FDQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <!--meu JS no final-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js" integrity="sha512-HGOnQO9+SP1V92SrtZfjqxxtLmVzqZpjFFekvzZVWoiASSQgSr4cw9Kqd2+l8Llp4Gm0G8GIFJ4ddwZilcdb8A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <script>
    $('.catalogo').slick({
      centerMode: true,
      centerPadding: '60px',
      slidesToShow: 3,
      responsive: [{
          breakpoint: 768,
          settings: {
            arrows: false,
            centerMode: true,
            centerPadding: '40px',
            slidesToShow: 3
          }
        },
        {
          breakpoint: 480,
          settings: {
            arrows: false,
            centerMode: true,
            centerPadding: '40px',
            slidesToShow: 1
          }
        }
      ]
    });
  </script>
  <!--Rodapé-->
  <?php require_once('partials/rodape.php') ?>

</body>

</html>