<h2>Desativar Aluno</h2>

<?php

require_once('class/ClassAluno.php');
    $id = $_GET['id'];
    $aluno = new ClassAluno();

    $aluno->Desativar($id);