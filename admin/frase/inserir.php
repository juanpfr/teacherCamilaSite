
<?php

if(isset($_POST['nomeFrase'])){
    $nomeFrase = $_POST['nomeFrase'];
    $textoFrase = $_POST['textoFrase'];
    //$fotoCliente = $_POST['fotoCliente'];             <---- a foto é diferente

    $statusFrase = 'ATIVO';

    //Recuperar o id
    require_once('class/Conexao.php');
    $conexao = Conexao::LigarConexao();
    $sql = $conexao->query('SELECT idFrase FROM tbl_frase ORDER BY idFrase DESC LIMIT 1');
    $resultado = $sql->fetch(PDO::FETCH_ASSOC); // Usar fetch em vez de fetchAll

    if($resultado !== false && isset($resultado['idFrase'])){
        $novoId = $resultado['idFrase'] + 1;
    }


    require_once('class/ClassFrase.php');

    $frase = new ClassFrase();
    $frase->nomeFrase = $nomeFrase;
    $frase->textoFrase = $textoFrase;
    $frase->statusFrase = $statusFrase;

    $frase->Inserir();
}
?>


<div class="container mt-5">
        <form action="index.php?p=frase&f=inserir" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nomeFrase" class="form-label">Nome da Frase</label>
                <input type="text" class="form-control" id="nomeFrase" name="nomeFrase">
            </div>
            <div class="mb-3">
                <label for="textoFrase" class="form-label">Texto da Frase</label>
                <textarea class="form-control" id="textoFrase" name="textoFrase" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>
    <!-- JS do Bootstrap -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
