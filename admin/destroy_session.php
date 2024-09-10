<?php

    session_start();        //inicia a sessão
    session_unset();        //limpar todos os dados da sessão (limpa o cache talvez)
    session_destroy();      //apaga a sessão

    header('Location:http://localhost/teacherCamilaSite/');

    exit();
