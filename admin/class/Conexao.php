<?php

require __DIR__ . '../../../vendor/autoload.php'; // Caminho para o autoload do Composer

use Dotenv\Dotenv;

class Conexao {

    // Método
    public static function LigarConexao() {

        // Carregar as variáveis do .env
        try {
            $dotenv = Dotenv::createImmutable(__DIR__ . '../../../'); // Caminho para a raiz do projeto
            $dotenv->load();
        } catch (Exception $e) {
            echo "Erro ao carregar o .env: " . $e->getMessage();
            return null;
        }

        // Informações de conexão carregadas do .env
        $host = $_ENV['DB_HOST'];
        $dbname = $_ENV['DB_NAME'];
        $username = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASSWORD'];

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            echo "Erro na conexão: " . $e->getMessage();
            return null;
        }
    }
}
