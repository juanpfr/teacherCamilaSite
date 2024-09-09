<?php

require_once('class/ClassAula.php');
    $id = $_GET['id'];
    $aula = new ClassAula();

    $aula->Desativar($id);