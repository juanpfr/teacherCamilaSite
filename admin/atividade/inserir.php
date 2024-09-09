<?php
require_once('class/ClassAtividade.php');

// Verifica se houve envio do formulário (cadastro)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura os dados do formulário
    $idAluno = isset($_POST['idAluno']) ? $_POST['idAluno'] : null;
    $nomeAtividade = $_POST['nomeAtividade'];
    $perguntas = $_POST['perguntas'];
    $respostas = $_POST['respostas'];
    $statusAtividade = 'PENDENTE';

    // Verifica se o idAluno está definido
    if (!$idAluno) {
        echo '<div class="container mt-3"><div class="alert alert-danger" role="alert">ID do aluno não fornecido.</div></div>';
    } else {
        try {
            // Cadastro da nova atividade
            $cadastrado = ClassAtividade::cadastrarAtividade($idAluno, $nomeAtividade, $perguntas, $respostas, $statusAtividade);

            if ($cadastrado) {
                echo '<div class="container mt-3"><div class="alert alert-success" role="alert">Atividade cadastrada com sucesso!</div></div>';
            } else {
                echo '<div class="container mt-3"><div class="alert alert-danger" role="alert">Erro ao cadastrar atividade.</div></div>';
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
            <h2 class="mb-4">Cadastrar Atividade</h2>
            <form class="table-scroll" action="index.php?p=atividade&at=inserir" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="idAluno" class="form-label">ID do Aluno:</label>
                    <input type="number" class="form-control" id="idAluno" name="idAluno" required>
                </div>
                <div class="mb-3">
                    <label for="nomeAtividade" class="form-label">Nome da Atividade:</label>
                    <input type="text" class="form-control" id="nomeAtividade" name="nomeAtividade" required>
                </div>

                <div id="perguntas">
                    <div class="mb-3">
                        <label for="pergunta1" class="form-label">Pergunta 1:</label>
                        <input type="text" class="form-control" id="pergunta1" name="perguntas[]" required>
                    </div>
                    <div class="mb-3">
                        <label for="resposta1" class="form-label">Resposta 1:</label>
                        <textarea class="form-control" id="resposta1" name="respostas[]"></textarea>
                    </div>
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
    let perguntaContador = 2;

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
