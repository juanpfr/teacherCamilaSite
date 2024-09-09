<h2>Recusar Solicitação</h2>

<?php

require_once('class/ClassSolicitacaoAula.php');
    $id = $_GET['id'];
    $aluno = new ClassSolicitacaoAula();

    $aluno->Recusar($id);