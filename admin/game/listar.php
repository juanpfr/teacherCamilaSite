<?php 
    require_once('class/ClassGame.php');

    $game = new ClassGame();
    $lista = $game->Listar();

    //print_r($lista);
?>

<!-- o table é uma tali (n sei escrever) do html q engloba cabeçalho, corpo e rodape -->

<table class="table table-dark table-hover">

<!-- o caption é titulo -->
    <caption>Dados do formulário de game</caption>
    <thead>
        <td scope="col">idGame</td>
        <td scope="col">Nome</td>
        <td scope="col">Foto</td>
        <td scope="col">Alt</td>
        <td scope="col">Status</td>
        <td scope="col">Descrição</td>
        <td scope="col">Atualizar</td>
        <td scope="col">Desativar</td>
    </thead>
    <tbody>
        <?php foreach($lista as $linha):  ?>
        <tr>
            <td><?php echo $linha['idGame']; ?></td>
            <td><?php echo $linha['nomeGame']; ?></td>
            <td><?php echo $linha['fotoGame']; ?></td>
            <td><?php echo $linha['altGame']; ?></td>
            <td><?php echo $linha['statusGame']; ?></td>
            <td><?php echo $linha['descricaoGame']; ?></td>
            <td><a href="index.php?p=game&g=atualizar&id=<?php echo $linha['idGame']; ?>">Atualizar</a></td>
            <td><a href="index.php?p=game&g=desativar&id=<?php echo $linha['idGame']; ?>">Desativar</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="btns">
<a href="index.php?p=game&g=inserir">Cadastrar+</a>
</div>