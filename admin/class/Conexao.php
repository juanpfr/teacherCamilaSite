<?php

require 'vendor/autoload.php'; // Para carregar o Dotenv

class Conexao{

    // Método
    public static function LigarConexao(){

        // Carregar as variáveis do .env
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        // Informações de conexão carregadas do .env
        $host = $_ENV['DB_HOST'];
        $dbname = $_ENV['DB_NAME'];
        $username = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASS'];

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            echo "Erro na conexão: " . $e->getMessage();
        }
    }
}