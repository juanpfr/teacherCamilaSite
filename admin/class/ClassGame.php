<?php

require_once('Conexao.php');

class ClassGame{
    //Atributos da class

    public $idGame;
    public $nomeGame;
    public $linkGame;
    public $fotoGame;
    public $altGame;
    public $statusGame;
    public $descricaoGame;

    public function __construct($id = false)
    {
        if($id) //se colocar $id = true ele não vai
        {
            $this->idGame = $id;
            $this->Carregar();
        }
    }

    //Metodos da class

        //Listar
        
        public function Listar(){ //criação do método Listar sempre com letra maiúscula para melhor entendimento do código (não é obrigatório)
            $sql = "select * from tbl_game where statusGame = 'ATIVO' order by nomeGame asc;"; //comando do banco de dados // no caso esse é para listar tudo da tabela game

            $conn = Conexao::LigarConexao(); //variavel que recebe os parametros de ligar conexão feito na ClassConexao

            $resultado = $conn->query($sql); //executa o comando (ctrl + enter no banco de dados)

            $lista = $resultado->fetchAll(); //deixa em linhas e colunas igual aparece no banco de dados

            return $lista;
        }

        //Inserir

        public function Inserir(){
            $sql = "INSERT INTO tbl_game (nomeGame, linkGame, fotoGame, descricaoGame, altGame, statusGame)
            VALUES ('$this->nomeGame', '$this->linkGame', '$this->fotoGame' , '$this->descricaoGame', '$this->nomeGame', '$this->statusGame')";

            $conn = Conexao::LigarConexao(); // Ligando a conexão do arquivo Conexao.php

            $conn->exec($sql); // Exec é do PDO, executa uma instrução SQL e retorna o número de linhas afetadas

            echo"<script> document.location='index.php?p=game' </script>";
            
        }

         //Carregar
         public function Carregar(){
            $sql = "SELECT * from tbl_game WHERE idGame = $this->idGame";
            $conn = Conexao::LigarConexao();
            $resultado = $conn->query($sql);
            $game = $resultado->fetch();
            
        //atributos da class / colunas do banco
            $this->idGame = $game['idGame'];
            $this->nomeGame = $game['nomeGame'];
            $this->linkGame = $game['linkGame'];
            $this->fotoGame = $game['fotoGame'];
            $this->statusGame = $game['statusGame'];
            $this->descricaoGame = $game['descricaoGame'];
        }

        public function Atualizar(){
            $sql = "UPDATE tbl_game set nomeGame = '".$this->nomeGame."', linkGame = '".$this->linkGame."', fotoGame = '".$this->fotoGame."', descricaoGame = '".$this->descricaoGame."', altGame = '".$this->nomeGame."', statusGame = '".$this->statusGame."' WHERE idGame = $this->idGame;";
            $conn = Conexao::LigarConexao();
            $conn->exec($sql);

            echo"<script> document.location='index.php?p=game' </script>";
        }

         //DESATIVAR 

    public function Desativar($id){
        $sql = "UPDATE tbl_game set statusGame = 'INATIVO' where idGame = $id";
        $conn = Conexao::LigarConexao();
        $resultado = $conn->query($sql);

        echo"<script> document.location='index.php?p=game' </script>";
        }
}