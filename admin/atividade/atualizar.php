<?php
require_once('class/ClassAtividade.php');
require_once('class/Conexao.php');

// Verifica se o ID da atividade a ser atualizada foi enviado via GET
if (isset($_GET['id'])) {
    $idAtividade = $_GET['id'];

    // Recuperar informações da atividade e suas perguntas associadas do banco de dados
    try {
        $conexao = Conexao::LigarConexao();

        // Busca a atividade pelo ID
        $sqlAtividade = "SELECT * FROM tbl_atividade WHERE idAtividade = :idAtividade";
        $stmtAtividade = $conexao->prepare($sqlAtividade);
        $stmtAtividade->bindParam(':idAtividade', $idAtividade);
        $stmtAtividade->execute();
        $atividade = $stmtAtividade->fetch(PDO::FETCH_ASSOC);

        // Se encontrou a atividade, busca as perguntas associadas
        if ($atividade) {
            $idAluno = $atividade['idAluno'];
            $nomeAtividade = $atividade['nomeAtividade'];

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
        } else {
            // Caso não encontre a atividade pelo ID
            echo '<div class="container mt-3"><div class="alert alert-danger" role="alert">Atividade não encontrada.</div></div>';
        }

    } catch (Exception $e) {
        echo '<div class="container mt-3"><div class="alert alert-danger" role="alert">' . $e->getMessage() . '</div></div>';
    }
}

// Verifica se houve envio do formulário (cadastro ou atualização)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['idAtividade'])) {
        // Atualização de atividade existente
        $idAtividade = $_POST['idAtividade'];
        $idAluno = $_POST['idAluno']; // Inclui o idAluno
        $nomeAtividade = $_POST['nomeAtividade'];
        $perguntas = $_POST['perguntas'];
        $respostas = $_POST['respostas'];
        
        try {
            $atualizado = ClassAtividade::atualizarAtividade($idAtividade, $idAluno, $nomeAtividade, $perguntas, $respostas);
            
            if ($atualizado) {
                echo '<div class="container mt-3"><div class="alert alert-success" role="alert">Atividade atualizada com sucesso!</div></div>';
            } else {
                echo '<div class="container mt-3"><div class="alert alert-danger" role="alert">Erro ao atualizar atividade.</div></div>';
            }
            
        } catch (Exception $e) {
            echo '<div class="container mt-3"><div class="alert alert-danger" role="alert">' . $e->getMessage() . '</div></div>';
        }
    } 
}
?>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h2 class="mb-4">Atualizar Atividade</h2>
            <form class="table-scroll" action="index.php?p=atividade&at=atualizar" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="idAtividade" value="<?php echo isset($idAtividade) ? htmlspecialchars($idAtividade) : ''; ?>">
                
                <div class="mb-3">
                    <label for="idAluno" class="form-label">ID do Aluno:</label>
                    <input type="number" class="form-control" id="idAluno" name="idAluno" value="<?php echo isset($idAluno) ? htmlspecialchars($idAluno) : ''; ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="nomeAtividade" class="form-label">Nome da Atividade:</label>
                    <input type="text" class="form-control" id="nomeAtividade" name="nomeAtividade" value="<?php echo isset($nomeAtividade) ? htmlspecialchars($nomeAtividade) : ''; ?>" required>
                </div>
                
                <div id="perguntas">
                    <?php
                    if (isset($perguntasParaFormulario)) {
                        for ($i = 0; $i < count($perguntasParaFormulario); $i++) {
                            $pergunta = $perguntasParaFormulario[$i];
                            $resposta = $respostasParaFormulario[$i];
                            ?>
                            <div class="mb-3">
                                <label for="pergunta<?php echo $i + 1; ?>" class="form-label">Pergunta <?php echo $i + 1; ?>:</label>
                                <input type="text" class="form-control" id="pergunta<?php echo $i + 1; ?>" name="perguntas[]" value="<?php echo htmlspecialchars($pergunta); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="resposta<?php echo $i + 1; ?>" class="form-label">Resposta <?php echo $i + 1; ?>:</label>
                                <textarea class="form-control" id="resposta<?php echo $i + 1; ?>" name="respostas[]"><?php echo htmlspecialchars($resposta); ?></textarea>
                            </div>
                            <?php
                        }
                    } else {
                        ?>
                        <div class="mb-3">
                            <label for="pergunta1" class="form-label">Pergunta 1:</label>
                            <input type="text" class="form-control" id="pergunta1" name="perguntas[]" required>
                        </div>
                        <div class="mb-3">
                            <label for="resposta1" class="form-label">Resposta 1:</label>
                            <textarea class="form-control" id="resposta1" name="respostas[]"></textarea>
                        </div>
                    <?php } ?>
                </div>
                
                <button type="button" class="btn btn-primary mb-3" onclick="adicionarPergunta()">Adicionar Pergunta</button><br>
                <input type="submit" class="btn btn-success" value="Enviar">
            </form>
        </div>
    </div>
</div>

<!-- JavaScript do Bootstrap v5.3 (coloque antes do fechamento do body) -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<script>
    let perguntaContador = <?php echo isset($perguntasParaFormulario) ? count($perguntasParaFormulario) + 1 : 2; ?>;
    
    function adicionarPergunta() {
        const divPerguntas = document.getElementById('perguntas');
        
        const divPergunta = document.createElement('div');
        divPergunta.className = 'mb-3';
        
        const labelPergunta = document.createElement('label');
        labelPergunta.setAttribute('for', 'pergunta' + perguntaContador);
        labelPergunta.className = 'form-label';
        labelPergunta.textContent = 'Pergunta ' + perguntaContador + ':';
        divPergunta.appendChild(labelPergunta);
        
        const inputPergunta = document.createElement('input');
        inputPergunta.type = 'text';
        inputPergunta.className = 'form-control';
        inputPergunta.id = 'pergunta' + perguntaContador;
        inputPergunta.name = 'perguntas[]';
        inputPergunta.required = true;
        divPergunta.appendChild(inputPergunta);
        
        divPerguntas.appendChild(divPergunta);
        
        const divResposta = document.createElement('div');
        divResposta.className = 'mb-3';
        
        const labelResposta = document.createElement('label');
        labelResposta.setAttribute('for', 'resposta' + perguntaContador);
        labelResposta.className = 'form-label';
        labelResposta.textContent = 'Resposta ' + perguntaContador + ':';
        divResposta.appendChild(labelResposta);
        
        const textareaResposta = document.createElement('textarea');
        textareaResposta.className = 'form-control';
        textareaResposta.id = 'resposta' + perguntaContador;
        textareaResposta.name = 'respostas[]';
        divResposta.appendChild(textareaResposta);
        
        divPerguntas.appendChild(divResposta);
        
        perguntaContador++;
    }
</script>
