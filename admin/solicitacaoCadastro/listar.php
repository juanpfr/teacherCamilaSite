<?php 
    require_once('class/ClassSolicitacaoCadastro.php');

    $solicitacaoCadastro = new ClassSolicitacaoCadastro();
    $lista = $solicitacaoCadastro->Listar();

    //print_r($lista);
?>

<!-- o table é uma tali (n sei escrever) do html q engloba cabeçalho, corpo e rodape -->

<table class="table table-dark table-hover">

<!-- o caption é titulo -->
    <caption>Dados do formulário de aluno</caption>
    <thead>
        <td scope="col">ID</td>
        <td scope="col">Nome</td>
        <td scope="col">Telefone</td>
        <td scope="col">Email</td>
        <td scope="col">Senha</td>
        <td scope="col">Status</td>
        <td scope="col">Data de solicitação</td>
        <td scope="col">Aceitar</td>
        <td scope="col">Recusar</td>
    </thead>
    <tbody>
        <?php foreach($lista as $linha):  ?>
        <tr>
            <td><?php echo $linha['idSolicitacaoCadastro']; ?></td>
            <td><?php echo $linha['nomeSolicitacaoCadastro']; ?></td>
            <td><?php echo $linha['telefoneSolicitacaoCadastro']; ?></td>
            <td><?php echo $linha['emailSolicitacaoCadastro']; ?></td>
            <td><?php echo $linha['senhaSolicitacaoCadastro']; ?></td>
            <td><?php echo $linha['statusSolicitacaoCadastro']; ?></td>
            <td><?php echo $linha['dataSolicitacaoCadastro']; ?></td>
            <td><a href="index.php?p=solicitacaoCadastro&sc=aceitar&id=<?php echo $linha['idSolicitacaoCadastro']; ?>">Aceitar</a></td>
            <td><a href="index.php?p=solicitacaoCadastro&sc=recusar&id=<?php echo $linha['idSolicitacaoCadastro']; ?>">Recusar</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>