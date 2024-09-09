<?php
// Inclua o arquivo que contém a definição da classe
require_once('class/ClassFrase.php');

// Crie uma instância da classe
$fraseObj = new ClassFrase();

// Obtenha o horário atual
$horaAtual = time();

// Verifique se já passaram 24 horas desde a última vez que uma frase foi selecionada
if (!isset($_SESSION['ultimaAtualizacao']) || $horaAtual - $_SESSION['ultimaAtualizacao'] >= 24*60*60) {
    // Obtenha uma frase aleatória do banco de dados
    $frase = $fraseObj->getFraseAleatoria();

    // Armazene a frase na sessão
    $_SESSION['fraseDoDia'] = $frase;

    // Atualize a hora da última atualização
    $_SESSION['ultimaAtualizacao'] = $horaAtual;
}

