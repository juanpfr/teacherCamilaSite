
<?php

require_once('class/ClassFrase.php');
$id = $_GET['id'];
$frase = new ClassFrase($id);

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



    $frase->nomeFrase = $nomeFrase;
    $frase->textoFrase = $textoFrase;
    $frase->statusFrase = $statusFrase;

    $frase->Atualizar();
}
?>


<div class="container mt-5">
        <form action="index.php?p=frase&f=atualizar&id=<?php echo $frase->idFrase; ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nomeFrase" class="form-label">Nome da Frase</label>
                <input type="text" class="form-control" id="nomeFrase" name="nomeFrase" required value="<?php echo $frase->nomeFrase; ?>">
            </div>
            <div class="mb-3">
                <label for="textoFrase" class="form-label">Texto da Frase</label>
                <input type="text" class="form-control large-input" id="textoFrase" name="textoFrase" required value="<?php echo $frase->textoFrase; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>
    <!-- JS do Bootstrap -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
