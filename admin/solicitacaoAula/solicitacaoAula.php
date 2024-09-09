<?php

    $pagina = @$_GET['sa'];

    if($pagina == NULL){

        require_once('listar.php');

    }else{
        if($pagina == 'aceitar'){
            require_once('aceitar.php');
        }
        if($pagina == 'recusar'){
            require_once('recusar.php');
        }
    }