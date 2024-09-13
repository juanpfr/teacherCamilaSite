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

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dataAula = $_POST['dataAula'];
    $horaInicio = $_POST['horaInicio'];
    $duracaoAula = $_POST['duracaoAula'];
    $horaFim = $duracaoAula === '30min' ? date("H:i", strtotime($horaInicio) + 1800) : date("H:i", strtotime($horaInicio) + 3600);

    // Verificar se o pagamento foi aprovado
    if (verificarPagamento($codigoPix)) {
        $pdo = Conexao::LigarConexao();
        if ($pdo) {
            // Verificar se o horário já foi agendado
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_agendamento_aula WHERE dataAula = ? AND horaInicio = ? AND idAluno = ?");
            $stmt->execute([$dataAula, $horaInicio, $idAluno]);
            $horarioOcupado = $stmt->fetchColumn();

            if ($horarioOcupado > 0) {
                echo "<div class='alert alert-danger'>O horário selecionado já está agendado. Escolha outro horário.</div>";
            } else {
                // Inserir o agendamento no banco de dados
                $stmt = $pdo->prepare("INSERT INTO tbl_agendamento_aula (idAluno, dataAula, horaInicio, horaFim) 
                                       VALUES (?, ?, ?, ?)");
                $stmt->execute([$idAluno, $dataAula, $horaInicio, $horaFim]);

                echo "<div class='alert alert-success'>Agendamento realizado com sucesso!</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Erro ao conectar com o banco de dados.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Pagamento não aprovado. Verifique o pagamento e tente novamente.</div>";
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
    </style>
</head>

<body>

    <div class="container mt-5">
        <h1 class="mb-4">Agendamento de Aulas</h1>
        <form id="agendamentoForm" action="" method="POST" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Duração da Aula</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="duracaoAula" id="duracao30min" value="30min" checked onchange="atualizarHorarios()">
                    <label class="form-check-label" for="duracao30min">30 minutos</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="duracaoAula" id="duracao60min" value="60min" onchange="atualizarHorarios()">
                    <label class="form-check-label" for="duracao60min">1 hora</label>
                </div>
            </div>

            <div class="col-md-6">
                <label for="dataAula" class="form-label">Data da Aula</label>
                <input type="date" class="form-control" id="dataAula" name="dataAula" required>
            </div>

            <div class="col-md-6">
                <label for="horaInicio" class="form-label">Hora de Início</label>
                <select class="form-select" id="horaInicio" name="horaInicio" required>
                    <!-- Horários serão preenchidos dinamicamente -->
                </select>
            </div>

            <div class="col-12">
                <button type="button" class="btn btn-primary" onclick="abrirModal()">Agendar Aula</button>
            </div>
        </form>
    </div>

    <!-- Modal -->
    <div id="pagamentoModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="fecharModal()">&times;</span>
            <p>O status de pagamento foi alterado para <strong>Pendente</strong>.</p>
            <p>Faça o pagamento usando o QR Code abaixo:</p>
            <img src="<?php echo $qrCodeUrl; ?>" alt="QR Code PIX" class="qr-code">
            <p>Após realizar o pagamento, confirme o agendamento clicando no botão abaixo.</p>
            <button type="button" class="btn btn-success" onclick="submitForm()">Confirmar Agendamento</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Função para abrir o pop-up de pagamento
        function abrirModal() {
            const modal = document.getElementById('pagamentoModal');
            modal.style.display = 'block';
        }

        // Função para fechar o pop-up de pagamento
        function fecharModal() {
            const modal = document.getElementById('pagamentoModal');
            modal.style.display = 'none';
        }

        // Submeter o formulário após confirmar no pop-up
        function submitForm() {
            fecharModal();
            document.getElementById('agendamentoForm').submit();
        }

        // Função para atualizar os horários com base na duração escolhida
        function atualizarHorarios() {
            const duracao30min = document.getElementById('duracao30min').checked;
            const horaInicio = document.getElementById('horaInicio');

            // Limpar as opções atuais
            horaInicio.innerHTML = '';

            const horarios = duracao30min ?
                ['08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30'] :
                ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00'];

            horarios.forEach(function(hora) {
                const option = document.createElement('option');
                option.value = hora;
                option.textContent = hora;
                horaInicio.appendChild(option);
            });
        }

        // Validar se a data é um dia útil e se é a partir de hoje
        function validarDiasUteis() {
            const dataAula = document.getElementById('dataAula');
            const hoje = new Date();
            const diasUteis = [1, 2, 3, 4, 5]; // Seg, Ter, Qua, Qui, Sex

            dataAula.addEventListener('change', function() {
                const dataSelecionada = new Date(this.value);
                const diaSemana = dataSelecionada.getDay();

                if (!diasUteis.includes(diaSemana)) {
                    alert('Apenas dias úteis são permitidos para agendamento.');
                    this.value = '';
                }
            });

            // Configurar a data mínima e desabilitar sábados e domingos
            dataAula.setAttribute('min', hoje.toISOString().split('T')[0]);
        }

        // Função para desabilitar sábados e domingos no campo de data
        function desabilitarDiasNaoUteis() {
            const dataAula = document.getElementById('dataAula');
            const hoje = new Date();
            let html = '';

            // Loop para construir as opções de data do calendário
            for (let i = 0; i < 30; i++) { // Exibir próximo mês
                let data = new Date(hoje);
                data.setDate(hoje.getDate() + i);
                let dia = data.getDate();
                let mes = data.getMonth() + 1;
                let ano = data.getFullYear();
                let diaSemana = data.getDay();

                if (diaSemana === 6 || diaSemana === 0 || data < hoje) { // Sábado, Domingo ou dia passado
                    html += `<option value="${ano}-${mes < 10 ? '0' : ''}${mes}-${dia < 10 ? '0' : ''}${dia}" disabled>${ano}-${mes < 10 ? '0' : ''}${mes}-${dia < 10 ? '0' : ''}${dia}</option>`;
                } else {
                    html += `<option value="${ano}-${mes < 10 ? '0' : ''}${mes}-${dia < 10 ? '0' : ''}${dia}">${ano}-${mes < 10 ? '0' : ''}${mes}-${dia < 10 ? '0' : ''}${dia}</option>`;
                }
            }

            dataAula.innerHTML = html;
        }

        // Chamar as funções ao carregar a página
        window.onload = function() {
            atualizarHorarios();
            validarDiasUteis();
            desabilitarDiasNaoUteis();
        };
    </script>
</body>

</html>