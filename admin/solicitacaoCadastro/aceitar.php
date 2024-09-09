<h2>Aceitar Solicitação</h2>

<?php

require_once('class/ClassSolicitacaoCadastro.php');
    $id = $_GET['id'];
    $aluno = new ClassSolicitacaoCadastro();

    $aluno->Aceitar($id);