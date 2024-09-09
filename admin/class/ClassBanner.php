<?php

require_once('Conexao.php');

class ClassBanner{
    //Atributos da class

    public $idBanner;
    public $nomeBanner;
    public $fotoBanner;
    public $altBanner;
    public $statusBanner;


    public function __construct($id = false)
    {
        if($id) //se colocar $id = true ele não vai
        {
            $this->idBanner = $id;
            $this->Carregar();
        }
    }

    //Metodos da class

        //Listar
        
        public function Listar(){ //criação do método Listar sempre com letra maiúscula para melhor entendimento do código (não é obrigatório)
            $sql = "select * from tbl_banner where statusBanner = 'ATIVO' order by nomeBanner asc;"; //comando do banco de dados // no caso esse é para listar tudo da tabela banner

            $conn = Conexao::LigarConexao(); //variavel que recebe os parametros de ligar conexão feito na ClassConexao

            $resultado = $conn->query($sql); //executa o comando (ctrl + enter no banco de dados)

            $lista = $resultado->fetchAll(); //deixa em linhas e colunas igual aparece no banco de dados

            return $lista;
        }

        //Inserir

        public function Inserir(){
            $sql = "INSERT INTO tbl_banner (nomeBanner, fotoBanner, altBanner, statusBanner)
            VALUES ('$this->nomeBanner', '$this->fotoBanner' , '$this->nomeBanner', '$this->statusBanner')";

            $conn = Conexao::LigarConexao(); // Ligando a conexão do arquivo Conexao.php

            $conn->exec($sql); // Exec é do PDO, executa uma instrução SQL e retorna o número de linhas afetadas

            echo"<script> document.location='index.php?p=banner' </script>";
            
        }

         //Carregar
         public function Carregar(){
            $sql = "SELECT * from tbl_banner WHERE idBanner = $this->idBanner";
            $conn = Conexao::LigarConexao();
            $resultado = $conn->query($sql);
            $banner = $resultado->fetch();
            
        //atributos da class / colunas do banco
            $this->idBanner = $banner['idBanner'];
            $this->nomeBanner = $banner['nomeBanner'];
            $this->fotoBanner = $banner['fotoBanner'];
            $this->statusBanner = $banner['statusBanner'];
        }

        public function Atualizar(){
            $sql = "UPDATE tbl_banner set nomeBanner = '".$this->nomeBanner."', fotoBanner = '".$this->fotoBanner."', altBanner = '".$this->nomeBanner."', statusBanner = '".$this->statusBanner."' WHERE idBanner = $this->idBanner;";
            $conn = Conexao::LigarConexao();
            $conn->exec($sql);

            echo"<script> document.location='index.php?p=banner' </script>";
        }


         //DESATIVAR 

    public function Desativar($id){
        $sql = "UPDATE tbl_banner set statusBanner = 'INATIVO' where idBanner = $id";
        $conn = Conexao::LigarConexao();
        $resultado = $conn->query($sql);

        echo"<script> document.location='index.php?p=banner' </script>";
        }
}