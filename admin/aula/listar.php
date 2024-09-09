<?php 
    require_once('class/ClassAula.php');

    $aula = new ClassAula();
    $lista = $aula->Listar();

    //print_r($lista);
?>

<!-- o table é uma tali (n sei escrever) do html q engloba cabeçalho, corpo e rodape -->

<table class="table table-dark table-hover">

<!-- o caption é titulo -->
    <caption>Dados do formulário de aula</caption>
    <thead>
        <td scope="col">idAula</td>
        <td scope="col">idAluno</td>
        <td scope="col">Data</td>
        <td scope="col">Hora</td>
        <td scope="col">Status</td>
        <td scope="col">Atualizar</td>
        <td scope="col">Desativar</td>
    </thead>
    <tbody>
        <?php foreach($lista as $linha):  ?>
        <tr>
            <td><?php echo $linha['idAula']; ?></td>
            <td><?php echo $linha['idAluno']; ?></td>
            <td><?php echo $linha['dataAula']; ?></td>
            <td><?php echo $linha['horaAula']; ?></td>
            <td><?php echo $linha['statusAula']; ?></td>
            <td><a href="index.php?p=aula&aul=atualizar&id=<?php echo $linha['idAula']; ?>">Atualizar</a></td>
            <td><a href="index.php?p=aula&aul=desativar&id=<?php echo $linha['idAula']; ?>">Desativar</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="btns">
<a href="index.php?p=aula&aul=inserir">Cadastrar+</a>
</div>