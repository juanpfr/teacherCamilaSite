<h2>Aceitar Solicitação</h2>

<?php

require_once('class/ClassSolicitacaoAula.php');
    $id = $_GET['id'];
    $aluno = new ClassSolicitacaoAula();

    $aluno->Aceitar($id);