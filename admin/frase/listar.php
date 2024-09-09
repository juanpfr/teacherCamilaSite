<?php 
    require_once('class/ClassFrase.php');

    $frase = new ClassFrase();
    $lista = $frase->Listar();

    //print_r($lista);
?>

<!-- o table é uma tali (n sei escrever) do html q engloba cabeçalho, corpo e rodape -->

<table class="table table-dark table-hover">

<!-- o caption é titulo -->
    <caption>Dados do formulário de aluno</caption>
    <thead>
        <td scope="col">idFrase</td>
        <td scope="col">Nome</td>
        <td scope="col">Texto</td>
        <td scope="col">Status</td>
        <td scope="col">Atualizar</td>
        <td scope="col">Desativar</td>
    </thead>
    <tbody>
        <?php foreach($lista as $linha):  ?>
        <tr>
            <td><?php echo $linha['idFrase']; ?></td>
            <td><?php echo $linha['nomeFrase']; ?></td>
            <td><?php echo $linha['textoFrase']; ?></td>
            <td><?php echo $linha['statusFrase']; ?></td>
            <td><a href="index.php?p=frase&f=atualizar&id=<?php echo $linha['idFrase']; ?>">Atualizar</a></td>
            <td><a href="index.php?p=frase&f=desativar&id=<?php echo $linha['idFrase']; ?>">Desativar</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="btns">
<a href="index.php?p=frase&f=inserir">Cadastrar+</a>
</div>