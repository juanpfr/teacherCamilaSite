<?php 
    require_once('class/ClassContato.php');

    $contato = new ClassContato();
    $lista = $contato->Listar();

    //print_r($lista);
?>

<!-- o table é uma tali (n sei escrever) do html q engloba cabeçalho, corpo e rodape -->

<table class="table table-dark table-hover table-scroll">

<!-- o caption é titulo -->
    <caption>Dados do formulário de contato</caption>
    <thead>
        <td scope="col">Nome</td>
        <td scope="col">E-mail</td>
        <td scope="col">Telefone</td>
        <td scope="col">Mensagem</td>
        <td scope="col">Data</td>
        <td scope="col">Hora</td>
        <td scope="col">Leitura</td>
    </thead>
    <tbody>
        <?php foreach($lista as $linha):  ?>
        <tr>
            <td><?php echo $linha['nomeContato']; ?></td>
            <td><?php echo $linha['emailContato']; ?></td>
            <td><?php echo $linha['telefoneContato']; ?></td>
            <td><?php echo $linha['mensagemContato']; ?></td>
            <td><?php echo $linha['dataContato']; ?></td>
            <td><?php echo $linha['horaContato']; ?></td>
            <td><a href="#">Leitura</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>