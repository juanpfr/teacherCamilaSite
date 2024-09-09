<?php 
    require_once('class/ClassAtividade.php');

    $atividade = new ClassAtividade();
    $lista = $atividade->Listar();

    //print_r($lista);
?>

<!-- o table é uma tali (n sei escrever) do html q engloba cabeçalho, corpo e rodape -->

<table class="table table-dark table-hover">

<!-- o caption é titulo -->
    <caption>Dados do formulário de atividade</caption>
    <thead>
        <td scope="col">idAtividade</td>
        <td scope="col">idAluno</td>
        <td scope="col">Nome da Atividade</td>
        <td scope="col">Status</td>
        <td scope="col">Atualizar</td>
        <td scope="col">Desativar</td>
    </thead>
    <tbody>
        <?php foreach($lista as $linha):  ?>
        <tr>
            <td><?php echo $linha['idAtividade']; ?></td>
            <td><?php echo $linha['idAluno']; ?></td>>
            <td><?php echo $linha['nomeAtividade']; ?></td>
            <td><?php echo $linha['statusAtividade']; ?></td>
            <td><a href="index.php?p=atividade&at=atualizar&id=<?php echo $linha['idAtividade']; ?>">Atualizar</a></td>
            <td><a href="index.php?p=atividade&at=desativar&id=<?php echo $linha['idAtividade']; ?>">Desativar</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="btns">
<a href="index.php?p=atividade&at=inserir">Cadastrar+</a>
</div>