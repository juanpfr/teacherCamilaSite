<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Inicia a sessão apenas se ainda não estiver ativa
}
require_once 'admin/class/Conexao.php';

if (!isset($_SESSION['idAluno'])) {
    header('Location: login.php');
    exit();
}

// Defina o fuso horário para São Paulo
date_default_timezone_set('America/Sao_Paulo');

// Obtenha o ID do aluno da sessão
$idAluno = $_SESSION['idAluno'];

// Gerar o código PIX (exemplo, substitua pelo seu código real)
$codigoPix = '1234-5678-9012-3456'; // Substitua pelo seu código PIX real
$qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=$codigoPix";

// Função fictícia para verificar o status do pagamento
function verificarPagamento($codigoPix)
{
    // Aqui você deve implementar a lógica real para verificar o status do pagamento
    // Isso pode ser feito com uma API de pagamento ou uma consulta ao banco de dados
    // Exemplo fictício:
    $pagamentoAprovado = true; // Simular que o pagamento foi aprovado
    return $pagamentoAprovado;
}

// Função para verificar se a data e horário já estão agendados
function verificarHorarioOcupado($pdo, $dataAula, $horaInicio, $idAluno)
{
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_agendamento_aula WHERE dataAula = ? AND horaInicio = ? AND idAluno = ?");
    $stmt->execute([$dataAula, $horaInicio, $idAluno]);
    return $stmt->fetchColumn() > 0;
}

// Gerar lista de horários fixos entre 8:00 e 16:00 com intervalo de 30 minutos
function gerarHorariosDisponiveis()
{
    $horarios = [];
    $inicio = strtotime('08:00');
    $fim = strtotime('16:00');

    while ($inicio <= $fim) {
        $horarios[] = date('H:i', $inicio);
        $inicio = strtotime('+30 minutes', $inicio);
    }

    return $horarios;
}

// Verificar se o formulário foi enviado
$mostrarModal = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dataAula = $_POST['dataAula'];
    $horaInicio = $_POST['horaInicio'];
    $duracaoAula = $_POST['duracaoAula'];
    $opcaoMensal = isset($_POST['opcaoMensal']) ? $_POST['opcaoMensal'] : null;

    $pdo = Conexao::LigarConexao();
    if ($pdo) {
        if ($opcaoMensal) {
            // Inserir o agendamento mensal no banco de dados
            $stmt = $pdo->prepare("INSERT INTO tbl_agendamento_mensal (idAluno, diaSemana, horaInicio) VALUES (?, ?, ?)");
            $stmt->execute([$idAluno, $opcaoMensal, $horaInicio]);

            $mostrarModal = true;
        } else if ($duracaoAula) {
            if ($duracaoAula === '30min') {
                $horaFim = date("H:i", strtotime($horaInicio) + 1800);

                // Verificar se o horário já foi agendado
                if (verificarHorarioOcupado($pdo, $dataAula, $horaInicio, $idAluno)) {
                    echo "<div class='alert alert-danger'>O horário selecionado já está agendado. Escolha outro horário.</div>";
                } else {
                    // Inserir o agendamento no banco de dados
                    $stmt = $pdo->prepare("INSERT INTO tbl_agendamento_aula (idAluno, dataAula, horaInicio, horaFim) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$idAluno, $dataAula, $horaInicio, $horaFim]);

                    // Para cobrança de 1 hora, duplicar o horário
                    $dataAula2 = date("Y-m-d", strtotime($dataAula . ' + 30 minutes')); // Adiciona 30 minutos para a segunda aula
                    $stmt = $pdo->prepare("INSERT INTO tbl_agendamento_aula (idAluno, dataAula, horaInicio, horaFim) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$idAluno, $dataAula2, $horaFim, date("H:i", strtotime($horaFim) + 3600)]);

                    $mostrarModal = true;
                }
            } else if ($duracaoAula === '60min') {
                // Verificar se o horário já foi agendado
                if (verificarHorarioOcupado($pdo, $dataAula, $horaInicio, $idAluno)) {
                    echo "<div class='alert alert-danger'>O horário selecionado já está agendado. Escolha outro horário.</div>";
                } else {
                    // Inserir o agendamento no banco de dados
                    $horaFim = date("H:i", strtotime($horaInicio) + 3600);
                    $stmt = $pdo->prepare("INSERT INTO tbl_agendamento_aula (idAluno, dataAula, horaInicio, horaFim) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$idAluno, $dataAula, $horaInicio, $horaFim]);

                    $mostrarModal = true;
                }
            }
        } else {
            echo "<div class='alert alert-danger'>Por favor, escolha a duração da aula e defina a opção de agendamento mensal, se aplicável.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Erro ao conectar com o banco de dados.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento de Aulas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilo do pop-up */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .qr-code {
            max-width: 200px;
            max-height: 200px;
        }

        #mensalOptions {
            display: none;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <h1 class="mb-4">Agendamento de Aulas</h1>
        <form id="agendamentoForm" action="" method="POST" class="row g-3">
            <div class="col-md-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="opcaoMensal" name="opcaoMensal" value="1">
                    <label class="form-check-label" for="opcaoMensal">Agendar mensalmente</label>
                </div>
            </div>

            <div id="mensalOptions">
                <div class="col-md-6">
                    <label for="diaSemana" class="form-label">Dia da Semana</label>
                    <select class="form-select" id="diaSemana" name="opcaoMensal">
                        <option value="1">Segunda-feira</option>
                        <option value="2">Terça-feira</option>
                        <option value="3">Quarta-feira</option>
                        <option value="4">Quinta-feira</option>
                        <option value="5">Sexta-feira</option>
                    </select>
                </div>
            </div>

            <div id="calendarOptions">
                <div class="col-md-6">
                    <label for="dataAula" class="form-label">Data da Aula</label>
                    <input type="date" class="form-control" id="dataAula" name="dataAula" required>
                </div>
            </div>

            <div class="col-md-6">
                <label for="horaInicio" class="form-label">Hora de Início</label>
                <select class="form-select" id="horaInicio" name="horaInicio" required>
                    <?php foreach (gerarHorariosDisponiveis() as $horario): ?>
                        <option value="<?php echo $horario; ?>"><?php echo $horario; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="horaFim" class="form-label">Hora de Fim</label>
                <input type="text" class="form-control" id="horaFim" name="horaFim" readonly>
            </div>

            <div class="col-md-12">
                <label class="form-label">Duração da Aula</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" id="duracao30min" name="duracaoAula" value="30min">
                    <label class="form-check-label" for="duracao30min">30 minutos</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" id="duracao60min" name="duracaoAula" value="60min">
                    <label class="form-check-label" for="duracao60min">1 hora</label>
                </div>
            </div>

            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Agendar Aula</button>
            </div>
        </form>

        <div id="paymentModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Pagamento via PIX</h2>
                <img src="<?php echo $qrCodeUrl; ?>" alt="QR Code" class="qr-code">
                <p>Use o código abaixo para realizar o pagamento:</p>
                <p><strong><?php echo $codigoPix; ?></strong></p>
                <p>Após o pagamento, aguarde a confirmação.</p>
            </div>
        </div>
    </div>

    <script>
        // Mostrar o modal de pagamento após o envio do formulário
        window.addEventListener('load', function() {
            <?php if ($mostrarModal): ?>
                var modal = document.getElementById("paymentModal");
                modal.style.display = "block";
                var span = document.getElementsByClassName("close")[0];

                span.onclick = function() {
                    modal.style.display = "none";
                }

                window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = "none";
                    }
                }
            <?php endif; ?>
        });

        // Toggle visibility of mensalOptions based on checkbox
        document.getElementById('opcaoMensal').addEventListener('change', function() {
            const mensalOptions = document.getElementById('mensalOptions');
            const calendarOptions = document.getElementById('calendarOptions');
            if (this.checked) {
                mensalOptions.style.display = 'block';
                calendarOptions.style.display = 'none';
            } else {
                mensalOptions.style.display = 'none';
                calendarOptions.style.display = 'block';
            }
        });

        function atualizarHorarios() {
            const duracao = document.querySelector('input[name="duracaoAula"]:checked').value;
            const horaInicio = document.getElementById('horaInicio').value;
            let horaFim = '';

            if (duracao === '30min') {
                if (horaInicio) {
                    horaFim = new Date('1970-01-01T' + horaInicio + 'Z');
                    horaFim.setMinutes(horaFim.getMinutes() + 30);
                    document.getElementById('horaFim').value = horaFim.toISOString().substring(11, 16);
                }
            } else if (duracao === '60min') {
                if (horaInicio) {
                    horaFim = new Date('1970-01-01T' + horaInicio + 'Z');
                    horaFim.setMinutes(horaFim.getMinutes() + 60);
                    document.getElementById('horaFim').value = horaFim.toISOString().substring(11, 16);
                }
            }
        }

        // Atualiza hora de fim quando o horário de início muda
        document.getElementById('horaInicio').addEventListener('change', atualizarHorarios);
        document.querySelector('input[name="duracaoAula"]').addEventListener('change', atualizarHorarios);
    </script>
</body>

</html>
