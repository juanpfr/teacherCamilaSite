<?php

require_once('Conexao.php');

class ClassAula{
    //Atributos da class

    public $idAula;
    public $idAluno;
    public $dataAula;
    public $horaAula;
    public $statusAula;


    public function __construct($id = false)
    {
        if($id) //se colocar $id = true ele não vai
        {
            $this->idAula = $id;
            $this->Carregar();
        }
    }

    //Metodos da class

        //Listar
        
        public function Listar(){ //criação do método Listar sempre com letra maiúscula para melhor entendimento do código (não é obrigatório)
            $sql = "select * from tbl_aula where statusAula = 'ATIVO' order by idAula asc;"; //comando do banco de dados // no caso esse é para listar tudo da tabela aula

            $conn = Conexao::LigarConexao(); //variavel que recebe os parametros de ligar conexão feito na ClassConexao

            $resultado = $conn->query($sql); //executa o comando (ctrl + enter no banco de dados)

            $lista = $resultado->fetchAll(); //deixa em linhas e colunas igual aparece no banco de dados

            return $lista;
        }

        //Inserir

        public function Inserir(){
            $sql = "INSERT INTO tbl_aula (idAluno, dataAula, horaAula, statusAula)
            VALUES ('$this->idAluno', '$this->dataAula', '$this->horaAula', '$this->statusAula')";

            $conn = Conexao::LigarConexao(); // Ligando a conexão do arquivo Conexao.php

            $conn->exec($sql); // Exec é do PDO, executa uma instrução SQL e retorna o número de linhas afetadas

            echo"<script> document.location='index.php?p=aula' </script>";
            
        }

         //Carregar
         public function Carregar(){
            $sql = "SELECT * from tbl_aula WHERE idAula = $this->idAula";
            $conn = Conexao::LigarConexao();
            $resultado = $conn->query($sql);
            $aula = $resultado->fetch();
            
        //atributos da class / colunas do banco
            $this->idAula = $aula['idAula'];
            $this->idAluno = $aula['idAluno'];
            $this->dataAula = $aula['dataAula'];
            $this->horaAula = $aula['horaAula'];
            $this->statusAula = $aula['statusAula'];
        }

        public function Atualizar(){
            $sql = "UPDATE tbl_aula set idAluno = '".$this->idAluno."', dataAula = '".$this->dataAula."', horaAula = '".$this->horaAula."', statusAula = '".$this->statusAula."' WHERE idAula = $this->idAula;";
            $conn = Conexao::LigarConexao();
            $conn->exec($sql);

            echo"<script> document.location='index.php?p=aula' </script>";
        }

         //DESATIVAR 

    public function Desativar($id){
        $sql = "UPDATE tbl_aula set statusAula = 'INATIVO' where idAula = $id";
        $conn = Conexao::LigarConexao();
        $resultado = $conn->query($sql);

        echo"<script> document.location='index.php?p=aula' </script>";
        }
}