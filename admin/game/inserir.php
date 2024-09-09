

<?php

if(isset($_POST['nomeGame'])){
    $nomeGame = $_POST['nomeGame'];
    $linkGame = $_POST['linkGame'];
    $descricaoGame = $_POST['descricaoGame'];
       

    $statusGame = 'ATIVO';
    $altGame = 'foto' . $nomeGame;

    //Recuperar o id
    require_once('class/Conexao.php');
    $conexao = Conexao::LigarConexao();
    $sql = $conexao->query('SELECT idGame FROM tbl_game ORDER BY idGame DESC LIMIT 1');
    $resultado = $sql->fetch(PDO::FETCH_ASSOC); // Usar fetch em vez de fetchAll

    if($resultado !== false && isset($resultado['idGame'])){
        $novoId = $resultado['idGame'] + 1;
    }

    //Tratar o campo FILES
    $arquivo = $_FILES['fotoGame'];
    if($arquivo['error']){
      throw new Exception('O erro foi: ' . $arquivo['error']);
    }

    //Obter a extensão do arquivo
    $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
    $nomeCliFoto = str_replace(' ', '', $nomeGame); //substitui um 'espaço' para 'sem espaço'
    $nomeCliFoto = iconv('UTF-8', 'ASCII//TRANSLIT', $nomeCliFoto); //remover sinais diacriticos (remove os caracteres especiais)
    $nomeCliFoto = strtolower($nomeCliFoto);

    //novo nome da imagem
    $novoNome = $novoId . '_' . $nomeCliFoto . '.' . $extensao;

    //print_r($novoNome);

    //Mover a imagem
    if(move_uploaded_file($arquivo['tmp_name'], 'img/game/' . $novoNome)){
      $fotoGame = 'game/' . $novoNome;
    }else{
      throw new Exception('NO ÉS POSSIBLE REALIZATED THE UPLOAD BROO!');
    }

    require_once('class/ClassGame.php');

    $game = new ClassGame();
    $game->nomeGame = $nomeGame;
    $game->linkGame = $linkGame;
    $game->fotoGame = $fotoGame;
    $game->altGame = $altGame;
    $game->statusGame = $statusGame;
    $game->descricaoGame = $descricaoGame;

    $game->Inserir();
}
?>

<div class="container mt-5">
  <h2>Inserir Game</h2>
  <form action="index.php?p=game&g=inserir" method="POST" enctype="multipart/form-data">
    <div class="row">
      <div class="col-4">
        <img src="img/sem-foto.jpg" class="img-fluid" alt="foto do Cliente" id="imgFoto">
        <input type="file" class="form-control" id="fotoGame" name="fotoGame" required style="display: none;">
        <div class="mb-3">
          <label for="fotoGame" class="form-label">Foto do game</label>
        </div>
      </div>
      <div class="col-8">

        <div class="row">
          <div class="col">
            <div class="mb-9">
              <label for="nomeGame" class="form-label">Nome do game</label>
              <input type="text" class="form-control" id="nomeGame" name="nomeGame" required>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="mb-3">
              <label for="linkGame" class="form-label">Link do game</label>
              <input type="text" class="form-control" id="linkGame" name="linkGame" required>
            </div>
          </div>
        </div>

        <!-- Novo campo de descrição do jogo -->
        <div class="mb-3">
                <label for="descricaoGame" class="form-label">Descrição do game</label>
                <input type="text" class="form-control large-input" id="descricaoGame" name="descricaoGame" required>
            </div>

        <button type="submit" class="btn btn-primary">Enviar</button>
      </div>
    </div>
  </form>
</div>


<script>
  // Transformar 
  document.getElementById('imgFoto').addEventListener('click', function() {
    //alert('Click na IMG');
    document.getElementById('fotoGame').click();
  })
  document.getElementById('fotoGame').addEventListener('change', function(event) {
    let imgFoto = document.getElementById('imgFoto');
    let arquivo = event.target.files[0];
    if (arquivo) {
      let carregar = new FileReader();
      carregar.onload = function(e) {
        imgFoto.src = e.target.result;
      }
      carregar.readAsDataURL(arquivo);
    }
  })
</script>