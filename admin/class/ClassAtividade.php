<?php

require_once('Conexao.php');

class ClassAtividade
{

    //Atributos
    public $idAluno;
    public $idAtividade;
    public $nomeAtividade;
    public $textoPergunta;
    public $respostaPergunta;
    public $statusAtividade;
    public $novoStatus;

    //Metodos da class

    //Listar

    public function Listar()
    { //criação do método Listar sempre com letra maiúscula para melhor entendimento do código (não é obrigatório)
        $sql = "SELECT * FROM tbl_atividade WHERE statusAtividade IN ('CONCLUÍDA', 'PENDENTE', 'ATIVO') ORDER BY idAtividade ASC;"; //comando do banco de dados // no caso esse é para listar tudo da tabela servico

        $conn = Conexao::LigarConexao(); //variavel que recebe os parametros de ligar conexão feito na ClassConexao

        $resultado = $conn->query($sql); //executa o comando (ctrl + enter no banco de dados)

        $lista = $resultado->fetchAll(); //deixa em linhas e colunas igual aparece no banco de dados

        return $lista;
    }

    
    // Listar por Aluno
    public function ListarPorAluno($idAluno)
    {
        $pdo = Conexao::LigarConexao();
        $sql = "SELECT * FROM tbl_atividade WHERE idAluno = :idAluno AND statusAtividade = 'PENDENTE'";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':idAluno', $idAluno, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Cadastrar
    public static function cadastrarAtividade($idAluno, $nomeAtividade, $perguntas, $respostas)
    {
        try {
            $conexao = Conexao::LigarConexao();

            // Inicia uma transação para garantir atomicidade das operações
            $conexao->beginTransaction();


            // Inserção de uma nova atividade com status 'PENDENTE'
            $sqlAtividade = "INSERT INTO tbl_atividade (nomeAtividade, idAluno, statusAtividade) VALUES (:nomeAtividade, :idAluno, 'PENDENTE')";
            $stmtAtividade = $conexao->prepare($sqlAtividade);
            $stmtAtividade->bindParam(':idAluno', $idAluno);
            $stmtAtividade->bindParam(':nomeAtividade', $nomeAtividade);
            $stmtAtividade->execute();

            // Recupera o ID da atividade inserida
            $idAtividade = $conexao->lastInsertId();

            // Inserção das perguntas e respostas associadas à atividade
            $sqlPergunta = "INSERT INTO tbl_pergunta (idAtividade, textoPergunta, respostaPergunta) VALUES (:idAtividade, :textoPergunta, :respostaPergunta)";
            $stmtPergunta = $conexao->prepare($sqlPergunta);

            for ($i = 0; $i < count($perguntas); $i++) {
                $textoPergunta = $perguntas[$i];
                $respostaPergunta = $respostas[$i];

                $stmtPergunta->bindParam(':idAtividade', $idAtividade);
                $stmtPergunta->bindParam(':textoPergunta', $textoPergunta);
                $stmtPergunta->bindParam(':respostaPergunta', $respostaPergunta);
                $stmtPergunta->execute();
            }

            // Finaliza a transação
            $conexao->commit();

            return true;
        } catch (PDOException $e) {
            // Em caso de erro, desfaz a transação
            $conexao->rollback();
            throw new Exception("Erro ao cadastrar atividade: " . $e->getMessage());
        }
    }


    // ATUALIZAR ATIVIDADE PELO DASHBOARD(TEACHER)
    public static function atualizarAtividade($idAtividade, $idAluno, $nomeAtividade, $perguntas, $respostas)
    {
        try {
            $conexao = Conexao::LigarConexao();

            // Inicia uma transação para garantir atomicidade das operações
            $conexao->beginTransaction();

            // Atualização do nome da atividade e idAluno
            $sqlUpdateAtividade = "UPDATE tbl_atividade SET nomeAtividade = :nomeAtividade, idAluno = :idAluno WHERE idAtividade = :idAtividade";
            $stmtUpdateAtividade = $conexao->prepare($sqlUpdateAtividade);
            $stmtUpdateAtividade->bindParam(':nomeAtividade', $nomeAtividade);
            $stmtUpdateAtividade->bindParam(':idAluno', $idAluno);
            $stmtUpdateAtividade->bindParam(':idAtividade', $idAtividade);
            $stmtUpdateAtividade->execute();

            // Remove perguntas existentes associadas à atividade
            $sqlRemovePerguntas = "DELETE FROM tbl_pergunta WHERE idAtividade = :idAtividade";
            $stmtRemovePerguntas = $conexao->prepare($sqlRemovePerguntas);
            $stmtRemovePerguntas->bindParam(':idAtividade', $idAtividade);
            $stmtRemovePerguntas->execute();

            // Atualização ou inserção das perguntas e respostas associadas
            foreach ($perguntas as $index => $pergunta) {
                $textoPergunta = $pergunta;
                $respostaPergunta = $respostas[$index];

                // Insere a nova pergunta e resposta
                $sqlInserePergunta = "INSERT INTO tbl_pergunta (idAtividade, textoPergunta, respostaPergunta) VALUES (:idAtividade, :textoPergunta, :respostaPergunta)";
                $stmtInserePergunta = $conexao->prepare($sqlInserePergunta);
                $stmtInserePergunta->bindParam(':idAtividade', $idAtividade);
                $stmtInserePergunta->bindParam(':textoPergunta', $textoPergunta);
                $stmtInserePergunta->bindParam(':respostaPergunta', $respostaPergunta);
                $stmtInserePergunta->execute();
            }
            echo "<script> document.location='index.php?p=atividade' </script>";
            // Finaliza a transação
            $conexao->commit();

            return true;
        } catch (PDOException $e) {
            // Em caso de erro, desfaz a transação
            $conexao->rollback();
            throw new Exception("Erro ao atualizar atividade: " . $e->getMessage());
        }
    }


   //MÉTODO SEMELHANTE AO ATUALIZAR PARA O ALUNO RESPONDER
   public static function atualizarRespostas($idAtividade, $respostas)
   {
       try {
           $conexao = Conexao::LigarConexao();

           // Inicia uma transação para garantir atomicidade das operações
           $conexao->beginTransaction();

           // Atualiza o status da atividade para "concluída"
           $sqlUpdateStatus = "UPDATE tbl_atividade SET statusAtividade = 'CONCLUÍDA' WHERE idAtividade = :idAtividade";
           $stmtUpdateStatus = $conexao->prepare($sqlUpdateStatus);
           $stmtUpdateStatus->bindParam(':idAtividade', $idAtividade);
           $stmtUpdateStatus->execute();

           // Atualização das respostas das perguntas
           $respostas = $_POST['respostas']; // Isso será um array

foreach ($respostas as $index => $resposta) {
   // Sua lógica aqui
   $sqlPergunta = "SELECT textoPergunta FROM tbl_pergunta WHERE idAtividade = :idAtividade LIMIT 1 OFFSET :offset";
   $stmtPergunta = $conexao->prepare($sqlPergunta);
   $stmtPergunta->bindParam(':idAtividade', $idAtividade);
   $stmtPergunta->bindParam(':offset', $index, PDO::PARAM_INT);
   $stmtPergunta->execute();
   $pergunta = $stmtPergunta->fetch(PDO::FETCH_ASSOC);

   if ($pergunta) {
       $textoPergunta = $pergunta['textoPergunta'];

       // Atualiza a resposta da pergunta
       $sqlAtualizaPergunta = "UPDATE tbl_pergunta SET respostaPergunta = :respostaPergunta WHERE idAtividade = :idAtividade AND textoPergunta = :textoPergunta";
       $stmtAtualizaPergunta = $conexao->prepare($sqlAtualizaPergunta);
       $stmtAtualizaPergunta->bindParam(':respostaPergunta', $resposta);
       $stmtAtualizaPergunta->bindParam(':idAtividade', $idAtividade);
       $stmtAtualizaPergunta->bindParam(':textoPergunta', $textoPergunta);
       $stmtAtualizaPergunta->execute();
   }
            }

            // Finaliza a transação
            $conexao->commit();

            return true;
        } catch (PDOException $e) {
            // Em caso de erro, desfaz a transação
            $conexao->rollback();
            throw new Exception("Erro ao atualizar respostas: " . $e->getMessage());
        }
    }

    //BUSCAR ATIVIDADE
    public function buscarAtividade($idAtividade)
    {
        try {
            $conexao = Conexao::LigarConexao();

            // Busca a atividade pelo ID
            $sqlAtividade = "SELECT * FROM tbl_atividade WHERE idAtividade = :idAtividade";
            $stmtAtividade = $conexao->prepare($sqlAtividade);
            $stmtAtividade->bindParam(':idAtividade', $idAtividade);
            $stmtAtividade->execute();
            $atividade = $stmtAtividade->fetch(PDO::FETCH_ASSOC);

            if ($atividade) {
                // Busca as perguntas associadas à atividade
                $sqlPerguntas = "SELECT * FROM tbl_pergunta WHERE idAtividade = :idAtividade";
                $stmtPerguntas = $conexao->prepare($sqlPerguntas);
                $stmtPerguntas->bindParam(':idAtividade', $idAtividade);
                $stmtPerguntas->execute();
                $perguntas = $stmtPerguntas->fetchAll(PDO::FETCH_ASSOC);

                // Prepara os dados das perguntas e respostas para serem preenchidos no formulário
                $perguntasParaFormulario = [];
                $respostasParaFormulario = [];
                foreach ($perguntas as $pergunta) {
                    $perguntasParaFormulario[] = $pergunta['textoPergunta'];
                    $respostasParaFormulario[] = $pergunta['respostaPergunta'];
                }

                return [
                    'atividade' => $atividade,
                    'perguntas' => $perguntasParaFormulario,
                    'respostas' => $respostasParaFormulario,
                ];
            } else {
                return null; // Atividade não encontrada
            }
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar atividade: " . $e->getMessage());
        }
    }


    //DESATIVAR

    public function Desativar($id)
    {
        $sql = "UPDATE tbl_atividade set statusAtividade = 'DESATIVADA' where idAtividade = $id";
        $conn = Conexao::LigarConexao();
        $resultado = $conn->query($sql);

        echo "<script> document.location='index.php?p=atividade' </script>";
    }
}
//CONTAR ATIVIDADES
function contarAtividade()
{
    $query = "SELECT COUNT(*) AS total FROM tbl_atividade";
    $conn = Conexao::LigarConexao();
    $stmt = $conn->query($query);

    if ($stmt !== false) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    } else {
        return 0; // Retorna 0 se houver erro na consulta
    }
}
