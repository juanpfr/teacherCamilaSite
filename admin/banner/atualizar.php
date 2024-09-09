<?php

require_once('class/ClassBanner.php');
$id = $_GET['id'];
$banner = new ClassBanner($id);

if(isset($_POST['nomeBanner'])){
    $nomeBanner = $_POST['nomeBanner'];
    //$fotoBanner = $_POST['fotoBanner'];             <---- a foto é diferente

    $statusBanner = 'ATIVO';
    $altBanner = 'foto' . $nomeBanner;

    //verificar se a foto foi modificada
    if(!empty($_FILES['fotoBanner']['name'])){
        //Recuperar o id
   
        
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
        $novoNome = $banner-> idBanner. '_' . $nomeCliFoto . '.' . $extensao;
    
        //print_r($novoNome);
    
        //Mover a imagem
        if(move_uploaded_file($arquivo['tmp_name'], 'img/banner/' . $novoNome)){
        $fotoBanner = 'banner/' . $novoNome;
        }else{
        throw new Exception('NO ÉS POSSIBLE REALIZATED THE UPLOAD BROO!');
        }
    }else{
        $fotoBanner = $banner->fotoBanner;
    }

    $banner->nomeBanner = $nomeBanner;
    $banner->fotoBanner = $fotoBanner;
    $banner->altBanner = $altBanner;
    $banner->statusBanner = $statusBanner;
 

    $banner->Atualizar();
}
?>


<div class="container mt-5">
  <h2>Atualizar Banner</h2>
  <form action="index.php?p=banner&b=atualizar&id=<?php echo $banner->idBanner; ?>" method="POST" enctype="multipart/form-data">
    <div class="row">
      <div class="col-4">
        <img src="img/<?php echo $banner->fotoBanner; ?>" class="img-fluid" alt="<?php echo $banner->nomeBanner; ?>" class="img-fluid" alt="foto do Banner" id="imgFoto">
        <input type="file" class="form-control" id="fotoBanner" name="fotoBanner" required style="display: none;">
        <div class="mb-3">
          <label for="fotoBanner" class="form-label">Foto do banner</label>
        </div>
      </div>
      <div class="col-8">

        <div class="row">
          <div class="col">
            <div class="mb-9">
              <label for="nomeBanner" class="form-label">Nome do banner</label>
              <input type="text" class="form-control" id="nomeBanner" name="nomeBanner" required value="<?php echo $banner->nomeBanner; ?>">
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