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

// Função para verificar se o horário está ocupado em agendamentos normais
function verificarHorarioOcupadoNormal($pdo, $dataAula, $horaInicio, $idAluno) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_agendamento_aula WHERE dataAula = ? AND horaInicio = ? AND idAluno = ?");
    $stmt->execute([$dataAula, $horaInicio, $idAluno]);
    return $stmt->fetchColumn() > 0;
}

// Função para verificar se o horário está ocupado em agendamentos mensais
function verificarHorarioOcupadoMensal($pdo, $diaSemana, $horaInicio, $idAluno) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_agendamento_mensal WHERE diaSemana = ? AND horaInicio = ? AND idAluno = ?");
    $stmt->execute([$diaSemana, $horaInicio, $idAluno]);
    return $stmt->fetchColumn() > 0;
}

// Gerar lista de horários fixos entre 8:00 e 16:00 com intervalo de 30 minutos
function gerarHorariosDisponiveis() {
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
        // Verifica se é agendamento mensal
        if ($opcaoMensal) {
            $diaSemana = $_POST['opcaoMensal']; // Dia da semana selecionado
            if (verificarHorarioOcupadoMensal($pdo, $diaSemana, $horaInicio, $idAluno)) {
                echo "<div class='alert alert-danger'>O horário selecionado já está agendado para este dia da semana. Escolha outro horário.</div>";
            } else {
                // Inserir o agendamento mensal no banco de dados
                $stmt = $pdo->prepare("INSERT INTO tbl_agendamento_mensal (idAluno, diaSemana, horaInicio, horaFim) VALUES (?, ?, ?, ?)");
                $horaFim = date("H:i", strtotime($horaInicio) + ($duracaoAula === '30min' ? 1800 : 3600));
                $stmt->execute([$idAluno, $diaSemana, $horaInicio, $horaFim]);

                $mostrarModal = true;
            }
        } else if ($duracaoAula) { // Verifica se é agendamento normal
            $diaSemana = date('N', strtotime($dataAula)); // Dia da semana (1 = Segunda, 7 = Domingo)
            $horaFim = date("H:i", strtotime($horaInicio) + ($duracaoAula === '30min' ? 1800 : 3600));

            // Verificar se o horário está ocupado em agendamentos normais ou mensais
            if (verificarHorarioOcupadoNormal($pdo, $dataAula, $horaInicio, $idAluno) || verificarHorarioOcupadoMensal($pdo, $diaSemana, $horaInicio, $idAluno)) {
                echo "<div class='alert alert-danger'>O horário selecionado já está agendado. Escolha outro horário.</div>";
            } else {
                // Inserir o agendamento normal no banco de dados
                $stmt = $pdo->prepare("INSERT INTO tbl_agendamento_aula (idAluno, dataAula, horaInicio, horaFim) VALUES (?, ?, ?, ?)");
                $stmt->execute([$idAluno, $dataAula, $horaInicio, $horaFim]);

                $mostrarModal = true;
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
    </div>
</body>

</html>
