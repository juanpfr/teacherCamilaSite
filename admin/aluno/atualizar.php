<?php

require_once('class/ClassAluno.php');
$id = $_GET['id'];
$aluno = new ClassAluno($id);

if (isset($_POST['nomeAluno'])) {
  $nomeAluno = $_POST['nomeAluno'];
  $telefoneAluno = $_POST['telefoneAluno'];
  $emailAluno = $_POST['emailAluno'];
  $senhaAluno = $_POST['senhaAluno'];
  //$fotoAluno = $_POST['fotoAluno'];             <---- a foto é diferente

  $statusAluno = 'ATIVO';
  $altAluno = 'foto' . $nomeAluno;

  //verificar se a foto foi modificada
  if (!empty($_FILES['fotoAluno']['name'])) {
    //Recuperar o id


    //Tratar o campo FILES
    $arquivo = $_FILES['fotoAluno'];
    if ($arquivo['error']) {
      throw new Exception('O erro foi: ' . $arquivo['error']);
    }

    //Obter a extensão do arquivo
    $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
    $nomeCliFoto = str_replace(' ', '', $nomeAluno); //substitui um 'espaço' para 'sem espaço'
    $nomeCliFoto = iconv('UTF-8', 'ASCII//TRANSLIT', $nomeCliFoto); //remover sinais diacriticos (remove os caracteres especiais)
    $nomeCliFoto = strtolower($nomeCliFoto);

    //novo nome da imagem
    $novoNome = $aluno->idAluno . '_' . $nomeCliFoto . '.' . $extensao;

    //print_r($novoNome);


    //Mover a imagem
    if (move_uploaded_file($arquivo['tmp_name'], 'img/aluno/' . $novoNome)) {
      $fotoAluno = 'aluno/' . $novoNome;
    } else {
      throw new Exception('NO ÉS POSSIBLE REALIZATED THE UPLOAD BROO!');
    }
  } else {
    $fotoAluno = $aluno->fotoAluno;
  }

  $aluno->nomeAluno = $nomeAluno;
  $aluno->telefoneAluno = $telefoneAluno;
  $aluno->emailAluno = $emailAluno;
  $aluno->senhaAluno = $senhaAluno;
  $aluno->fotoAluno = $fotoAluno;
  $aluno->altAluno = $altAluno;
  $aluno->statusAluno = $statusAluno;

  $aluno->Atualizar();
}

?>

<div class="container mt-5">
  <form action="index.php?p=aluno&al=atualizar&id=<?php echo $aluno->idAluno; ?>" method="POST" enctype="multipart/form-data">
    <div class="row">
      <div class="col-4 formAluno">
        <img src="img/<?php echo $aluno->fotoAluno; ?>" class="img-fluid" alt="<?php echo $aluno->nomeAluno; ?>" alt="foto do Aluno" id="imgFoto">
        <input type="file" class="form-control" id="fotoAluno" name="fotoAluno" style="display: none;">
        <div class="mb-3">
          <label for="fotoAluno" class="form-label">Foto do Aluno</label>
        </div>
      </div>
      <div class="col-8">

        <div class="row">
          <div class="col">
            <div class="mb-9">
              <label for="nomeAluno" class="form-label">Nome do Aluno</label>
              <input type="text" class="form-control" id="nomeAluno" name="nomeAluno" required value="<?php echo $aluno->nomeAluno; ?>">
              <!-- id tem q ser o mesmo nome do "for" -->
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-4 tel">
            <div class="mb-3">
              <label for="telefoneAluno" class="form-label">Telefone do Aluno</label>
              <input type="tel" class="form-control" id="telefoneAluno" name="telefoneAluno" required value="<?php echo $aluno->telefoneAluno; ?>">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <div class="mb-3">
              <label for="emailAluno" class="form-label">E-mail do Aluno</label>
              <input type="email" class="form-control" id="emailAluno" name="emailAluno" required value="<?php echo $aluno->emailAluno; ?>">
            </div>
          </div>
          <div class="col">
            <div class="mb-3">
              <label for="senhaAluno" class="form-label">Senha do Aluno</label>
              <input type="password" class="form-control" id="senhaAluno" name="senhaAluno" required value="<?php echo $aluno->senhaAluno; ?>">
            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-primary">Enviar</button>
      </div>
    </div>
  </form>
</div>

<script>
  // mascara telefone celular

  var telefoneAluno = document.getElementById("telefoneAluno");

  telefoneAluno.addEventListener("input", () => {

    //Remover caracteres não numéricos usando expressão regular /\D/g e limitar a 11 digitos.
    var limparValor = telefoneAluno.value.replace(/\D/g, "").substring(0, 11);

    //dividir a string em um array de caracteres individuais
    var numerosArray = limparValor.split("");

    //criar variavel para receber o número formatado
    var numeroFormatado = "";

    //acessa o if quando a quantidade de numeros é maior do que zero
    if (numerosArray.length > 0) {
      numeroFormatado += `(${numerosArray.slice(0,2).join("")})`;
    }

    //acessa o if quando a quantidade de numeros é maior do que dois
    if (numerosArray.length > 2) {
      numeroFormatado += ` ${numerosArray.slice(2,7).join("")}`;
    }

    //acessa o if quando a quantidade de numeros é maior do que sete
    if (numerosArray.length > 7) {
      numeroFormatado += `-${numerosArray.slice(7,11).join("")}`;
    }

    //enviar para o campo formatado
    telefoneAluno.value = numeroFormatado;

  })

  // Transformar 
  document.getElementById('imgFoto').addEventListener('click', function() {
    //alert('Click na IMG');
    document.getElementById('fotoAluno').click();
  })
  document.getElementById('fotoAluno').addEventListener('change', function(event) {
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