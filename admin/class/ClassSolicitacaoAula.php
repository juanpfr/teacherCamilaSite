<?php

require_once('Conexao.php');

class ClassSolicitacaoAula{

    //Atributos
    public $idSolicitacaoAula;
    public $idAluno;
    public $dataSolicitacao;
    public $horaSolicitacao;
    public $statusSolicitacao;

    public function __construct($id = false)
    {
        if($id) //se colocar $id = true ele não vai
        {
            $this->idSolicitacaoAula = $id;
        }
    }


    //Metodos da class

    public function Listar(){ //criação do método Listar sempre com letra maiúscula para melhor entendimento do código (não é obrigatório)
            $sql = "SELECT * FROM tbl_solicitacao_aula WHERE statusSolicitacao = 'PENDENTE' ORDER BY idSolicitacaoAula ASC;"; //comando do banco de dados // no caso esse é para listar tudo da tabela servico

            $conn = Conexao::LigarConexao(); //variavel que recebe os parametros de ligar conexão feito na ClassConexao

            $resultado = $conn->query($sql); //executa o comando (ctrl + enter no banco de dados)

            $lista = $resultado->fetchAll(); //deixa em linhas e colunas igual aparece no banco de dados

            return $lista;
        }

    // Aceitar
    public function Aceitar($id) {
        // Atualizar status da solicitação
        $sql = "UPDATE tbl_solicitacao_aula SET statusSolicitacaoAula = 'ACEITO' WHERE idSolicitacaoAula = $id";
        $conn = Conexao::LigarConexao();
        $conn->query($sql);

        // Inserir aluno na tabela tbl_aula
        $this->InserirAula($id);

        echo "<script> document.location='index.php?p=solicitacaoAula' </script>";
    }

    // Recusar
    public function Recusar($id) {
        $sql = "UPDATE tbl_solicitacao_aula SET statusSolicitacaoAula = 'RECUSADO' WHERE idSolicitacaoAula = $id";
        $conn = Conexao::LigarConexao();
        $conn->query($sql);

        echo "<script> document.location='index.php?p=solicitacaoAula' </script>";
    }

    // Inserir aluno na tabela tbl_aluno
    private function InserirAula($id) {
        $sql = "SELECT idAluno, dataSolicitacao, horaSolicitacao, statusSolicitacao FROM tbl_solicitacao_aula WHERE idSolicitacaoAula = $id";
        $conn = Conexao::LigarConexao();
        $resultado = $conn->query($sql);
        $dados = $resultado->fetch();

        if ($dados) {
            $aula = new ClassAula();
            $aula->idAluno = $dados['idAluno'];
            $aula->dataAula = $dados['dataSolicitacao'];
            $aula->horaAula = $dados['horaSolicitacao'];
            $aula->statusAula = 'ATIVO';
            $aula->Inserir();
        }
    }
}

    