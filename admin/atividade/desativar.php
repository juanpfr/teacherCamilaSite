
<?php

require_once('class/ClassAtividade.php');
    $id = $_GET['id'];
    $atividade = new ClassAtividade();

    $atividade->Desativar($id);