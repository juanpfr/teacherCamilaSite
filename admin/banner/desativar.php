<?php

require_once('class/ClassBanner.php');
    $id = $_GET['id'];
    $banner = new ClassBanner();

    $banner->Desativar($id);