<?php

require_once('class/ClassGame.php');
    $id = $_GET['id'];
    $game = new ClassGame();

    $game->Desativar($id);