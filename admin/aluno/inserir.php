<?php

if (isset($_POST['nomeAluno'])) {
  $nomeAluno = $_POST['nomeAluno'];
  $telefoneAluno = $_POST['telefoneAluno'];
  $emailAluno = $_POST['emailAluno'];
  $senhaAluno = $_POST['senhaAluno'];
  //$fotoAluno = $_POST['fotoAluno'];             <---- a foto é diferente

  $statusAluno = 'ATIVO';
  $altAluno = 'foto' . $nomeAluno;

  //Recuperar o id
  require_once('class/Conexao.php');
  $conexao = Conexao::LigarConexao();
  $sql = $conexao->query('SELECT idAluno FROM tbl_aluno ORDER BY idAluno DESC LIMIT 1');
  $resultado = $sql->fetch(PDO::FETCH_ASSOC); // Usar fetch em vez de fetchAll

  if ($resultado !== false && isset($resultado['idAluno'])) {
    $novoId = $resultado['idAluno'] + 1;
  }

  //Tratar o campo FILES
  $arquivo = $_FILES['fotoAluno'];
  if ($arquivo['error']) {
    throw new Exception('O erro foi: ' . $arquivo['error']);
  }

  //Obter a extensão do arquivo
  $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
  $nomeAlFoto = str_replace(' ', '', $nomeAluno); //substitui um 'espaço' para 'sem espaço'
  $nomeAlFoto = iconv('UTF-8', 'ASCII//TRANSLIT', $nomeAlFoto); //remover sinais diacriticos (remove os caracteres especiais)
  $nomeAlFoto = strtolower($nomeAlFoto);

  //novo nome da imagem
  $novoNome = $novoId . '_' . $nomeAlFoto . '.' . $extensao;

  //print_r($novoNome);

  //Mover a imagem
  if (move_uploaded_file($arquivo['tmp_name'], 'img/aluno/' . $novoNome)) {
    $fotoAluno = 'aluno/' . $novoNome;
  } else {
    throw new Exception('NO ÉS POSSIBLE REALIZATED THE UPLOAD BROO!');
  }

  require_once('class/ClassAluno.php');

  $aluno = new ClassAluno();
  $aluno->nomeAluno = $nomeAluno;
  $aluno->telefoneAluno = $telefoneAluno;
  $aluno->emailAluno = $emailAluno;
  $aluno->senhaAluno = $senhaAluno;
  $aluno->fotoAluno = $fotoAluno;
  $aluno->altAluno = $altAluno;
  $aluno->statusAluno = $statusAluno;

  $aluno->Inserir();
}
?>

<div class="container mt-5">
  <form action="index.php?p=aluno&al=inserir" method="POST" enctype="multipart/form-data">
    <div class="row">
      <div class="col-4 formAluno">
        <h2>Formulário Aluno</h2>
        <img src="img/sem-foto.jpg" class="img-fluid" alt="foto do Aluno" id="imgFoto">
        <input type="file" class="form-control" id="fotoAluno" name="fotoAluno" required style="display: none;">
        <div class="mb-3">
          <label for="fotoAluno" class="form-label">Foto do Aluno</label>
        </div>
      </div>
      <div class="col-8">

        <div class="row">
          <div class="col">
            <div class="mb-9">
              <label for="nomeAluno" class="form-label">Nome do Aluno</label>
              <input type="text" class="form-control" id="nomeAluno" name="nomeAluno" required>
              <!-- id tem q ser o mesmo nome do "for" -->
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-4 tel">
            <div class="mb-3">
              <label for="telefoneAluno" class="form-label">Telefone do Aluno</label>
              <input type="tel" class="form-control" id="telefoneAluno" name="telefoneAluno" required>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <div class="mb-3">
              <label for="emailAluno" class="form-label">E-mail do Aluno</label>
              <input type="email" class="form-control" id="emailAluno" name="emailAluno" required>
            </div>
          </div>
          <div class="col">
            <div class="mb-3">
              <label for="senhaAluno" class="form-label">Senha do Aluno</label>
              <input type="password" class="form-control" id="senhaAluno" name="senhaAluno" required>
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