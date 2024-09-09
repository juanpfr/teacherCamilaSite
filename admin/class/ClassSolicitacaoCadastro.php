<?php

require_once('Conexao.php');
require_once('ClassAluno.php');

class ClassSolicitacaoCadastro{

    // Atributos
    public $idSolicitacaoCadastro;
    public $nomeSolicitacaoCadastro;
    public $telefoneSolicitacaoCadastro;
    public $emailSolicitacaoCadastro;
    public $senhaSolicitacaoCadastro;
    public $statusSolicitacaoCadastro;

    public function __construct($id = false)
    {
        if($id) //se colocar $id = true ele não vai
        {
            $this->idSolicitacaoCadastro = $id;
        }
    }

    // Métodos da classe

    // Listar
    public function Listar() {
        $sql = "SELECT * FROM tbl_solicitacao_cadastro WHERE statusSolicitacaoCadastro = 'PENDENTE' ORDER BY nomeSolicitacaoCadastro DESC;";
        $conn = Conexao::LigarConexao();
        $resultado = $conn->query($sql);
        $lista = $resultado->fetchAll();
        return $lista;
    }

    // Aceitar
    public function Aceitar($id) {
        // Atualizar status da solicitação
        $sql = "UPDATE tbl_solicitacao_cadastro SET statusSolicitacaoCadastro = 'ACEITO' WHERE idSolicitacaoCadastro = $id";
        $conn = Conexao::LigarConexao();
        $conn->query($sql);

        // Inserir aluno na tabela tbl_aluno
        $this->InserirAluno($id);

        echo "<script> document.location='index.php?p=solicitacaoCadastro' </script>";
    }

    // Recusar
    public function Recusar($id) {
        $sql = "UPDATE tbl_solicitacao_cadastro SET statusSolicitacaoCadastro = 'RECUSADO' WHERE idSolicitacaoCadastro = $id";
        $conn = Conexao::LigarConexao();
        $conn->query($sql);

        echo "<script> document.location='index.php?p=solicitacaoCadastro' </script>";
    }

    // Inserir aluno na tabela tbl_aluno
    private function InserirAluno($id) {
        $sql = "SELECT nomeSolicitacaoCadastro, telefoneSolicitacaoCadastro, emailSolicitacaoCadastro, senhaSolicitacaoCadastro FROM tbl_solicitacao_cadastro WHERE idSolicitacaoCadastro = $id";
        $conn = Conexao::LigarConexao();
        $resultado = $conn->query($sql);
        $dados = $resultado->fetch();

        if ($dados) {
            $aluno = new ClassAluno();
            $aluno->nomeAluno = $dados['nomeSolicitacaoCadastro'];
            $aluno->telefoneAluno = $dados['telefoneSolicitacaoCadastro'];
            $aluno->emailAluno = $dados['emailSolicitacaoCadastro'];
            $aluno->senhaAluno = $dados['senhaSolicitacaoCadastro'];
            $aluno->statusAluno = 'ATIVO'; // Define o status do aluno como ativo
            $aluno->fotoAluno = ''; // Define a foto do aluno como vazia
            $aluno->altAluno = $dados['nomeSolicitacaoCadastro']; // Define o alt da foto como o nome do aluno
            $aluno->Inserir();
        }
    }
}
