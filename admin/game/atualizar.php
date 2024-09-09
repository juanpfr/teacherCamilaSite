
<?php

require_once('class/ClassGame.php');
$id = $_GET['id'];
$game = new ClassGame($id);

if(isset($_POST['nomeGame'])){
    $nomeGame = $_POST['nomeGame'];
    $linkGame = $_POST['linkGame'];
    $descricaoGame = $_POST['descricaoGame'];
    //$fotoGame = $_POST['fotoGame'];             <---- a foto é diferente

    $statusGame = 'ATIVO';
    $altGame = 'foto' . $nomeGame;

    //verificar se a foto foi modificada
    if(!empty($_FILES['fotoGame']['name'])){
        //Recuperar o id
   
        
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
        $novoNome = $game-> idGame. '_' . $nomeCliFoto . '.' . $extensao;
    
        //print_r($novoNome);
    
        //Mover a imagem
        if(move_uploaded_file($arquivo['tmp_name'], 'img/game/' . $novoNome)){
        $fotoGame = 'game/' . $novoNome;
        }else{
        throw new Exception('NO ÉS POSSIBLE REALIZATED THE UPLOAD BROO!');
        }
    }else{
        $fotoGame = $game->fotoGame;
    }

    $game->nomeGame = $nomeGame;
    $game->linkGame = $linkGame;
    $game->fotoGame = $fotoGame;
    $game->altGame = $altGame;
    $game->statusGame = $statusGame;
    $game->descricaoGame = $descricaoGame;

    $game->Atualizar();
}
?>


<div class="container mt-5">
  <h2>Atualizar Game</h2>
  <form action="index.php?p=game&g=atualizar&id=<?php echo $game->idGame; ?>" method="POST" enctype="multipart/form-data">
    <div class="row">
      <div class="col-4">
        <img src="img/<?php echo $game->fotoGame; ?>" class="img-fluid" alt="<?php echo $game->nomeGame; ?>" id="imgFoto">
        <input type="file" class="form-control" id="fotoGame" name="fotoGame"  style="display: none;">
        <div class="mb-3">
          <label for="fotoGame" class="form-label">Foto do game</label>
        </div>
      </div>
      <div class="col-8">

        <div class="row">
          <div class="col">
            <div class="mb-9">
              <label for="nomeGame" class="form-label">Nome do game</label>
              <input type="text" class="form-control" id="nomeGame" name="nomeGame" required value="<?php echo $game->nomeGame; ?>">
              <!-- id tem q ser o mesmo nome do "for" -->
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="mb-3">
              <label for="linkGame" class="form-label">Link do game</label>
              <input type="text" class="form-control" id="linkGame" name="linkGame" required value="<?php echo $game->linkGame; ?>">
            </div>
          </div>
        </div>

         <!-- Novo campo de descrição do jogo -->
         <div class="row">
          <div class="col-12">
            <div class="mb-3">
              <label for="descricaoGame" class="form-label">Descrição do game</label>
              <input type="text" class="form-control large-input" id="descricaoGame" name="descricaoGame" required value="<?php echo $game->descricaoGame; ?>">
            </div>
          </div>
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