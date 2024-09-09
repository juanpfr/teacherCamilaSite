<?php
// Verifica se a sessão já foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o idAluno está definido na sessão
if (!isset($_SESSION['idAluno'])) {
    header('Location: login.php');
    exit();
}

// Obtém o idAluno da sessão
$idAluno = $_SESSION['idAluno'];

require_once('admin/class/ClassAtividade.php');

$atividade = new ClassAtividade();
$lista = $atividade->ListarPorAluno($idAluno); // Alterado para listar atividades do aluno específico

// Variável para armazenar a mensagem de sucesso
$mensagemSucesso = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['idAtividade']) && isset($_POST['idAluno']) && isset($_POST['nomeAtividade']) && isset($_POST['perguntas']) && isset($_POST['respostas'])) {
        $idAtividade = htmlspecialchars($_POST['idAtividade']);
        $idAluno = htmlspecialchars($_POST['idAluno']);
        $nomeAtividade = htmlspecialchars($_POST['nomeAtividade']);
        $perguntas = array_map('htmlspecialchars', $_POST['perguntas']);
        $respostas = array_map('htmlspecialchars', $_POST['respostas']);

        $novoStatus = 'CONCLUÍDA'; // Defina isso conforme necessário

        try {
            $atualizadoResposta = ClassAtividade::atualizarRespostas($idAtividade, $idAluno);

            if ($atualizadoResposta) {
                // Mensagem de sucesso para ser exibida no próximo carregamento da página
                $mensagemSucesso = 'Atividade atualizada com sucesso!';

                // Redireciona após um atraso para exibir a mensagem
                echo "<script>
                    setTimeout(function() {
                        window.location.href = 'index.php?pa=aluno&al=atividade';
                    }, 2000); // 2 segundos de atraso
                </script>";
            } else {
                echo '<div class="container mt-3"><div class="alert alert-danger" role="alert">Erro ao atualizar atividade.</div></div>';
            }
        } catch (Exception $e) {
            echo '<div class="container mt-3"><div class="alert alert-danger" role="alert">' . htmlspecialchars($e->getMessage()) . '</div></div>';
        }
    }
}

// Verifica se o ID da atividade a ser atualizada foi enviado via GET
if (isset($_GET['atividade_id'])) {
    $idAtividade = htmlspecialchars($_GET['atividade_id']);

    // Cria uma instância da classe ClassAtividade
    $atividadeClass = new ClassAtividade();

    // Busca a atividade e suas perguntas associadas
    try {
        $dadosAtividade = $atividadeClass->buscarAtividade($idAtividade);

        if ($dadosAtividade) {
            $atividade = $dadosAtividade['atividade'];
            $perguntasParaFormulario = $dadosAtividade['perguntas'];
            $respostasParaFormulario = $dadosAtividade['respostas'];
            $idAluno = htmlspecialchars($atividade['idAluno']);
            $nomeAtividade = htmlspecialchars($atividade['nomeAtividade']);
        } else {
            // Caso não encontre a atividade pelo ID
            echo '<div class="container mt-3"><div class="alert alert-danger" role="alert">Atividade não encontrada.</div></div>';
        }
    } catch (Exception $e) {
        echo '<div class="container mt-3"><div class="alert alert-danger" role="alert">' . htmlspecialchars($e->getMessage()) . '</div></div>';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atividades</title>
    <link rel="stylesheet" href="assets/css/reset.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body id="sim">
    <section class="conteudoAtividade">
        <div class="smallCard">
            <h2 class="atCardTitulo">Atividades</h2>
            <table class="table table-dark table-hover">
                <caption>Dados do formulário de atividade</caption>
                <thead>
                    <tr>
                        <td scope="col">Nome da Atividade</td>
                        <td scope="col">Status</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista as $linha) : ?>
                        <tr>
                            <td>
                                <a href="#" data-id="<?php echo htmlspecialchars($linha['idAtividade']); ?>">
                                    <?php echo htmlspecialchars($linha['nomeAtividade']); ?>
                                </a>
                            </td>
                            <td><?php echo htmlspecialchars($linha['statusAtividade']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="bigCard">
            <h2 class="atCardTitulo">Atividade</h2>
            <div id="atividadeDetails" class="container mt-5" style="display: none;">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <h2 class="mb-4 nameAtividade"><?php echo isset($nomeAtividade) ? htmlspecialchars($nomeAtividade) : ''; ?></h2>
                        <form class="formAtividade table-scroll" action="atividade.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="idAtividade" value="<?php echo isset($idAtividade) ? htmlspecialchars($idAtividade) : ''; ?>">
                            <input type="hidden" class="form-control" id="idAluno" name="idAluno" value="<?php echo isset($idAluno) ? htmlspecialchars($idAluno) : ''; ?>" required>
                            <input type="hidden" class="form-control" id="nomeAtividade" name="nomeAtividade" value="<?php echo isset($nomeAtividade) ? htmlspecialchars($nomeAtividade) : ''; ?>" required>
                            <div id="perguntas">
                                <?php
                                if (isset($perguntasParaFormulario)) {
                                    for ($i = 0; $i < count($perguntasParaFormulario); $i++) {
                                        $pergunta = $perguntasParaFormulario[$i];
                                        $resposta = $respostasParaFormulario[$i];
                                ?>
                                        <div class="mb-3">
                                            <h3><?php echo htmlspecialchars($pergunta); ?></h3>
                                            <input type="hidden" class="form-control" id="pergunta<?php echo $i + 1; ?>" name="perguntas[]" value="<?php echo htmlspecialchars($pergunta); ?>" required>
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
                                        <input type="text" class="form-control" id="pergunta1" name="perguntas[]" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="resposta1" class="form-label">Resposta 1:</label>
                                        <textarea class="form-control" id="resposta1" name="respostas[]"></textarea>
                                    </div>
                                <?php } ?>
                            </div>
                            <input type="submit" class="btn btn-success" value="Enviar">
                        </form>
                        <?php if ($mensagemSucesso) : ?>
                            <div class="container mt-3">
                                <div class="alert alert-success" role="alert">
                                    <?php echo htmlspecialchars($mensagemSucesso); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="assets/js/wow.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/scrollreveal@4.0.0/dist/scrollreveal.min.js"></script>
    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="assets/js/slick.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const activityLinks = document.querySelectorAll('a[data-id]');

            activityLinks.forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault();

                    const atividadeId = this.getAttribute('data-id');
                    const redirectUrl = `index.php?pa=aluno&al=atividade&atividade_id=${atividadeId}`;

                    window.location.href = redirectUrl;
                });
            });

            // Exibir a div com os detalhes da atividade se o id estiver presente
            if (<?php echo isset($idAtividade) ? 'true' : 'false'; ?>) {
                document.getElementById('atividadeDetails').style.display = 'block';
            }
        });
    </script>
</body>

</html>