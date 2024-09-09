

<?php

if(isset($_POST['idAluno'])){
    $idAluno = $_POST['idAluno'];
    $dataAula = $_POST['dataAula'];
    $horaAula = $_POST['horaAula'];
    //$fotoCliente = $_POST['fotoCliente'];             <---- a foto é diferente

    $statusAula = 'ATIVO';

    //Recuperar o id
    require_once('class/Conexao.php');
    $conexao = Conexao::LigarConexao();
    $sql = $conexao->query('SELECT idAula FROM tbl_aula ORDER BY idAula DESC LIMIT 1');
    $resultado = $sql->fetch(PDO::FETCH_ASSOC); // Usar fetch em vez de fetchAll

    if($resultado !== false && isset($resultado['idAula'])){
        $novoId = $resultado['idAula'] + 1;
    }

    

    require_once('class/ClassAula.php');

    $aula = new ClassAula();
    $aula->idAluno = $idAluno;
    $aula->dataAula = $dataAula;
    $aula->horaAula = $horaAula;
    $aula->statusAula = $statusAula;

    $aula->Inserir();
}
?>

<div class="container mt-5">
        <form action="index.php?p=aula&aul=inserir" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="idAluno" class="form-label">ID do Aluno</label>
                <input type="number" class="form-control" id="idAluno" name="idAluno" required>
            </div>
            <div class="mb-3">
                <label for="dataAula" class="form-label">Data da Aula</label>
                <input type="date" class="form-control" id="dataAula" name="dataAula" required>
            </div>
            <div class="mb-3">
                <label for="horaAula" class="form-label">Hora da Aula</label>
                <input type="time" class="form-control" id="horaAula" name="horaAula" required>
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
          <!-- JS do Bootstrap -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>



