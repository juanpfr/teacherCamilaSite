<?php

if(isset($_POST['nomeBanner'])){
    $nomeBanner = $_POST['nomeBanner'];
    //$fotoCliente = $_POST['fotoCliente'];             <---- a foto é diferente

    $statusBanner = 'ATIVO';
    $altBanner = 'foto' . $nomeBanner;

    //Recuperar o id
    require_once('class/Conexao.php');
    $conexao = Conexao::LigarConexao();
    $sql = $conexao->query('SELECT idBanner FROM tbl_banner ORDER BY idBanner DESC LIMIT 1');
    $resultado = $sql->fetch(PDO::FETCH_ASSOC); // Usar fetch em vez de fetchAll

    if($resultado !== false && isset($resultado['idBanner'])){
        $novoId = $resultado['idBanner'] + 1;
    }

    //Tratar o campo FILES
    $arquivo = $_FILES['fotoBanner'];
    if($arquivo['error']){
      throw new Exception('O erro foi: ' . $arquivo['error']);
    }

    //Obter a extensão do arquivo
    $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
    $nomeCliFoto = str_replace(' ', '', $nomeBanner); //substitui um 'espaço' para 'sem espaço'
    $nomeCliFoto = iconv('UTF-8', 'ASCII//TRANSLIT', $nomeCliFoto); //remover sinais diacriticos (remove os caracteres especiais)
    $nomeCliFoto = strtolower($nomeCliFoto);

    //novo nome da imagem
    $novoNome = $novoId . '_' . $nomeCliFoto . '.' . $extensao;

    //print_r($novoNome);

    //Mover a imagem
    if(move_uploaded_file($arquivo['tmp_name'], 'img/banner/' . $novoNome)){
      $fotoBanner = 'banner/' . $novoNome;
    }else{
      throw new Exception('NO ÉS POSSIBLE REALIZATED THE UPLOAD BROO!');
    }

    require_once('class/ClassBanner.php');

    $banner = new ClassBanner();
    $banner->nomeBanner = $nomeBanner;
    $banner->fotoBanner = $fotoBanner;
    $banner->altBanner = $altBanner;
    $banner->statusBanner = $statusBanner;

    $banner->Inserir();
}
?>

<div class="container mt-5">
 
  <form class="formBanner" action="index.php?p=banner&b=inserir" method="POST" enctype="multipart/form-data">
  <h2>Inserir Banner</h2>
      <div class="col-8">

     
            <div class="mb-9">
              <label for="nomeBanner" class="form-label">Nome do banner</label>
              <input type="text" class="form-control" id="nomeBanner" name="nomeBanner" required>
            </div>
     

        
      </div>
      <div class="col-4">
        <img src="img/sem-foto.jpg" class="img-fluid" alt="foto do Cliente" id="imgFoto">
        <input type="file" class="form-control" id="fotoBanner" name="fotoBanner" required style="display: none;">
        <div class="mb-3">
          <label for="fotoBanner" class="form-label"></label>
        </div>
        <button id="btnBanner" type="submit" class="btn btn-primary">Enviar</button>
      </div>
    
  </form>
</div>


<script>
  // Transformar 
  document.getElementById('imgFoto').addEventListener('click', function() {
    //alert('Click na IMG');
    document.getElementById('fotoBanner').click();
  })
  document.getElementById('fotoBanner').addEventListener('change', function(event) {
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
