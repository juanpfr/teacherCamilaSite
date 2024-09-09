<?php

require_once('class/ClassFrase.php');
    $id = $_GET['id'];
    $frase = new ClassFrase();

    $frase->Desativar($id);