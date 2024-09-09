<?php

require_once('Conexao.php');

class ClassUsuario{

    //Atributos
    public $idUsuario;
    public $nomeUsuario;
    public $emailUsuario;
    public $senhaUsuario;
    public $statusUsuario;

    public function __construct($id = false)
    {
        if($id) //se colocar $id = true ele não vai
        {
            $this->idUsuario = $id;
            $this->Carregar();
        }
    }

    //Metodos da class

        //Listar
        
        public function Listar(){ //criação do método Listar sempre com letra maiúscula para melhor entendimento do código (não é obrigatório)
            $sql = "select * from tbl_usuario where statusUsuario = 'ATIVO' order by nomeUsuario desc;"; //comando do banco de dados // no caso esse é para listar tudo da tabela servico

            $conn = Conexao::LigarConexao(); //variavel que recebe os parametros de ligar conexão feito na ClassConexao

            $resultado = $conn->query($sql); //executa o comando (ctrl + enter no banco de dados)

            $lista = $resultado->fetchAll(); //deixa em linhas e colunas igual aparece no banco de dados

            return $lista;
        }

        public function Inserir(){
            $sql = "INSERT INTO tbl_usuario (usuario, email, senha, status)
            VALUES ('$this->nomeUsuario', '$this->emailUsuario', '$this->senhaUsuario', '$this->statusUsuario')";

            $conn = Conexao::LigarConexao(); // Ligando a conexão do arquivo Conexao.php

            $conn->exec($sql); // Exec é do PDO, executa uma instrução SQL e retorna o número de linhas afetadas

            echo"<script> document.location='index.php?p=usuario' </script>";
            
        }

        public function Carregar(){
            $sql = "SELECT * from tbl_usuario WHERE id = $this->idUsuario";
            $conn = Conexao::LigarConexao();
            $resultado = $conn->query($sql);
            $usuario = $resultado->fetch();
            
        //atributos da class / colunas do banco
            $this->idUsuario = $usuario['id'];
            $this->nomeUsuario = $usuario['usuario'];
            $this->emailUsuario = $usuario['email'];
            $this->senhaUsuario = $usuario['senha'];
            $this->statusUsuario = $usuario['status'];
        }

    public function Atualizar(){
        $sql = "UPDATE tbl_usuario set usuario = '".$this->nomeUsuario."', email = '".$this->emailUsuario."', senha = '".$this->senhaUsuario."', status = '".$this->statusUsuario."' WHERE id = $this->idUsuario;";
        $conn = Conexao::LigarConexao();
        $conn->exec($sql);

        echo"<script> document.location='index.php?p=usuario' </script>";
    }

    //DESATIVAR 

    public function Desativar($id){
        $sql = "UPDATE tbl_usuario set status = 'DESATIVADO' where id = $id";
        $conn = Conexao::LigarConexao();
        $resultado = $conn->query($sql);

        echo"<script> document.location='index.php?p=usuario' </script>";
        }

        //Verificar Login
    public function VerificarLogin(){
        $sql = "SELECT * from tbl_usuario where email = '". $this->emailUsuario ."' and senha = '". $this->senhaUsuario ."' and status = 'ATIVO'";

        $conn = Conexao::LigarConexao();
        $resultado = $conn->query($sql);
        $usuario = $resultado->fetch();

        if($usuario){
            return $usuario['id'];
        }else{
            return false;
        }
    }
}

if(isset($_POST['email'])){

    $usuario = new ClassUsuario();

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $usuario->emailUsuario = $email;
    $usuario->senhaUsuario = $senha;

    if($idUsuario = $usuario->VerificarLogin()){

        session_start();                            //inicia a sessão
        $_SESSION['id'] = $idUsuario;

        echo json_encode(['success' => true, 'message' => 'Login OK']);
    }else{
        echo json_encode(['success'=> false, 'message' => 'Login Inválido']);
    }
}