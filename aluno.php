<?php

    $pagina = @$_GET['al'];

    if($pagina == NULL){

        require_once('alunoPagina.php');

    }else{
        if($pagina == 'jogos'){
            require_once('jogos.php');
        }
        if($pagina == 'atividade'){
            require_once('atividade.php');
        }
    }