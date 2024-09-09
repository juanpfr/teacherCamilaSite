<?php 
    require_once('class/ClassBanner.php');

    $banner = new ClassBanner();
    $lista = $banner->Listar();

    //print_r($lista);
?>

<!-- o table é uma tali (n sei escrever) do html q engloba cabeçalho, corpo e rodape -->

<table class="table table-dark table-hover">

<!-- o caption é titulo -->
    <caption>Dados do formulário de banner</caption>
    <thead>
        <td scope="col">idBanner</td>
        <td scope="col">Nome</td>
        <td scope="col">Foto</td>
        <td scope="col">Alt</td>
        <td scope="col">Status</td>
        <td scope="col">Atualizar</td>
        <td scope="col">Desativar</td>
    </thead>
    <tbody>
        <?php foreach($lista as $linha):  ?>
        <tr>
            <td><?php echo $linha['idBanner']; ?></td>
            <td><?php echo $linha['nomeBanner']; ?></td>
            <td><?php echo $linha['fotoBanner']; ?></td>
            <td><?php echo $linha['altBanner']; ?></td>
            <td><?php echo $linha['statusBanner']; ?></td>
            <td><a href="index.php?p=banner&b=atualizar&id=<?php echo $linha['idBanner']; ?>">Atualizar</a></td>
            <td><a href="index.php?p=banner&b=desativar&id=<?php echo $linha['idBanner']; ?>">Desativar</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="btns">
<a href="index.php?p=banner&b=inserir">Cadastrar+</a>
</div>