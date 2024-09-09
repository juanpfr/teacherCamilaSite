<?php 
    require_once('class/ClassAluno.php');

    $aluno = new ClassAluno();
    $lista = $aluno->Listar();

    //print_r($lista);
?>

<!-- o table é uma tali (n sei escrever) do html q engloba cabeçalho, corpo e rodape -->

<table class="table table-dark table-hover table-scroll">

<!-- o caption é titulo -->
    <caption>Dados do formulário de aluno</caption>
    <thead>
        <td scope="col">idAluno</td>
        <td scope="col">Nome</td>
        <td scope="col">Telefone</td>
        <td scope="col">Email</td>
        <td scope="col">Senha</td>
        <td scope="col">Foto</td>
        <td scope="col">Alt</td>
        <td scope="col">Status</td>
        <td scope="col">Atualizar</td>
        <td scope="col">Desativar</td>
    </thead>
    <tbody>
        <?php foreach($lista as $linha):  ?>
        <tr>
            <td><?php echo $linha['idAluno']; ?></td>
            <td><?php echo $linha['nomeAluno']; ?></td>
            <td><?php echo $linha['telefoneAluno']; ?></td>
            <td><?php echo $linha['emailAluno']; ?></td>
            <td><?php echo $linha['senhaAluno']; ?></td>
            <td><?php echo $linha['fotoAluno']; ?></td>
            <td><?php echo $linha['altAluno']; ?></td>
            <td><?php echo $linha['statusAluno']; ?></td>
            <td><a href="index.php?p=aluno&al=atualizar&id=<?php echo $linha['idAluno']; ?>">Atualizar</a></td>
            <td><a href="index.php?p=aluno&al=desativar&id=<?php echo $linha['idAluno']; ?>">Desativar</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="btns">
<a href="index.php?p=aluno&al=inserir">Cadastrar+</a>
</div>