<?php 
    require_once('class/ClassSolicitacaoAula.php');

    $solicitacaoAula = new ClassSolicitacaoAula();
    $lista = $solicitacaoAula->Listar();

    //print_r($lista);
?>

<!-- o table é uma tali (n sei escrever) do html q engloba cabeçalho, corpo e rodape -->

<table class="table table-dark table-hover">

<!-- o caption é titulo -->
    <caption>Dados do formulário de aluno</caption>
    <thead>
        <td scope="col">ID Solicitação</td>
        <td scope="col">ID Aluno</td>
        <td scope="col">Data da aula</td>
        <td scope="col">Horário da aula</td>
        <td scope="col">Status</td>
        <td scope="col">Data de solicitação</td>
        <td scope="col">Aceitar</td>
        <td scope="col">Recusar</td>
    </thead>
    <tbody>
        <?php foreach($lista as $linha):  ?>
        <tr>
            <td><?php echo $linha['idSolicitacaoAula']; ?></td>
            <td><?php echo $linha['idAluno']; ?></td>
            <td><?php echo $linha['dataSolicitacao']; ?></td>
            <td><?php echo $linha['horaSolicitacao']; ?></td>
            <td><?php echo $linha['statusSolicitacao']; ?></td>
            <td><?php echo $linha['dataCriacao']; ?></td>
            <td><a href="index.php?p=solicitacaoAula&sa=aceitar&id=<?php echo $linha['idSolicitacaoAula']; ?>">Aceitar</a></td>
            <td><a href="index.php?p=solicitacaoAula&sa=recusar&id=<?php echo $linha['idSolicitacaoAula']; ?>">Recusar</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>