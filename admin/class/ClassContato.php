<?php

require_once('Conexao.php');

class ClassContato{   // Criar uma classe para atribuir os atributos que tem no banco de dados

    // Atributos ou Caracteristicas
    public $idContato; // tem que iniciar minúsculo para identificar ser um atributo
    public $nomeContato;
    public $emailContato;
    public $telefoneContato;
    public $mensagemContato;
    public $statusContato;
    public $dataContato;
    public $horaContato;

    // Criar método

        // Listar

        public function Listar(){ // função, tem que iniciar maiúsculo para identificar que é um método
            $sql = "select * from tbl_contato where statusContato = 'ativo' order by dataContato desc";

            $conn = Conexao::LigarConexao();

            $resultado = $conn->query($sql); // Prepara e executa uma instrução SQL sem espaços reservados

            $lista = $resultado->fetchAll(); // Retornar uma matriz de dados (tabelinha), busca as linhas restantes de um conjunto de resultados, matriz de dados com informações que tem no banco de dados

            return $lista; // Retornar a minha matriz de dados
        }

        //Inserir

        public function Inserir(){
            $sql = "INSERT INTO tbl_contato (nomeContato, emailContato, telefoneContato, mensagemContato, statusContato)
            VALUES ('". $this->nomeContato ."', '". $this->emailContato ."', '". $this->telefoneContato ."', '". $this->mensagemContato ."', '". $this->statusContato ."')";

            $conn = Conexao::LigarConexao(); // Ligando a conexão do arquivo conexao.php

            $conn->exec($sql); // Exec é do PDO, executa uma instrução SQL e retorna o número de linhas afetadas
        }
}