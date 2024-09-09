<?php

require_once('Conexao.php');

class ClassAluno{

    //Atributos
    public $idAluno;
    public $nomeAluno;
    public $telefoneAluno;
    public $emailAluno;
    public $senhaAluno;
    public $fotoAluno;
    public $altAluno;
    public $statusAluno;

    public function __construct($id = false)
    {
        if($id) //se colocar $id = true ele não vai
        {
            $this->idAluno = $id;
            $this->Carregar();
        }
    }

    //Metodos da class



        //Verificar Login

        public function VerificarLogin(){
            $sql = "SELECT * from tbl_aluno where emailAluno = '". $this->emailAluno ."' and senhaAluno = '". $this->senhaAluno ."' and statusAluno = 'ATIVO'";

            $conn = Conexao::LigarConexao();
            $resultado = $conn->query($sql);
            $aluno = $resultado->fetch();

            if($aluno){
                return $aluno['idAluno'];
            }else{
                return false;
            }
        }
        //Listar
        
        public function Listar(){ //criação do método Listar sempre com letra maiúscula para melhor entendimento do código (não é obrigatório)
            $sql = "select * from tbl_aluno where statusAluno = 'ATIVO' order by nomeAluno desc;"; //comando do banco de dados // no caso esse é para listar tudo da tabela servico

            $conn = Conexao::LigarConexao(); //variavel que recebe os parametros de ligar conexão feito na ClassConexao

            $resultado = $conn->query($sql); //executa o comando (ctrl + enter no banco de dados)

            $lista = $resultado->fetchAll(); //deixa em linhas e colunas igual aparece no banco de dados

            return $lista;
        }

        public function Inserir(){
            $sql = "INSERT INTO tbl_aluno (nomeAluno, telefoneAluno, emailAluno, senhaAluno, fotoAluno, altAluno, statusAluno)
            VALUES ('$this->nomeAluno', '$this->telefoneAluno', '$this->emailAluno', '$this->senhaAluno', '$this->fotoAluno', '$this->nomeAluno', '$this->statusAluno')";

            $conn = Conexao::LigarConexao(); // Ligando a conexão do arquivo Conexao.php

            $conn->exec($sql); // Exec é do PDO, executa uma instrução SQL e retorna o número de linhas afetadas

            echo"<script> document.location='index.php?p=aluno' </script>";
            
        }

        public function Carregar(){
            $sql = "SELECT * from tbl_aluno WHERE idAluno = $this->idAluno";
            $conn = Conexao::LigarConexao();
            $resultado = $conn->query($sql);
            $aluno = $resultado->fetch();
            
        //atributos da class / colunas do banco
            $this->idAluno = $aluno['idAluno'];
            $this->nomeAluno = $aluno['nomeAluno'];
            $this->telefoneAluno = $aluno['telefoneAluno'];
            $this->emailAluno = $aluno['emailAluno'];
            $this->senhaAluno = $aluno['senhaAluno'];
            $this->fotoAluno = $aluno['fotoAluno'];
            $this->statusAluno = $aluno['statusAluno'];
        }

        public function Atualizar(){
            $sql = "UPDATE tbl_aluno set nomeAluno = '".$this->nomeAluno."', telefoneAluno = '".$this->telefoneAluno."', emailAluno = '".$this->emailAluno."', senhaAluno = '".$this->senhaAluno."', fotoAluno = '".$this->fotoAluno."', altAluno = '".$this->nomeAluno."', statusAluno = '".$this->statusAluno."' WHERE idAluno = $this->idAluno;";
            $conn = Conexao::LigarConexao();
            $conn->exec($sql);

            echo"<script> document.location='index.php?p=aluno' </script>";
        }

        //DESATIVAR 

    public function Desativar($id){
        $sql = "UPDATE tbl_aluno set statusAluno = 'INATIVO' where idAluno = $id";
        $conn = Conexao::LigarConexao();
        $resultado = $conn->query($sql);

        echo"<script> document.location='index.php?p=aluno' </script>";
        }
}

//
if(isset($_POST['email'])){

    $aluno = new ClassAluno();

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $aluno->emailAluno = $email;
    $aluno->senhaAluno = $senha;

    if($idAluno = $aluno->VerificarLogin()){

        session_start();                            //inicia a sessão
        $_SESSION['idAluno'] = $idAluno;

        echo json_encode(['success' => true, 'message' => 'Login OK']);
    }else{
        echo json_encode(['success'=> false, 'message' => 'Login Inválido']);
    }
}

function contarAlunos() {
    $query = "SELECT COUNT(*) AS total FROM tbl_aluno";
    $conn = Conexao::LigarConexao();
    $stmt = $conn->query($query);

    if ($stmt !== false) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    } else {
        return 0; // Retorna 0 se houver erro na consulta
    }
}


