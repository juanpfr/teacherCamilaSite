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

// Mensagem de sucesso ou erro
$message = '';

// Variáveis para desabilitar horários e dias
$occupiedTimes = [];
$disabledDates = [];

// Obtenha os horários ocupados e dias desabilitados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['dataSolicitacao']) && isset($_POST['horaSolicitacao'])) {
        // Obtenha os dados do formulário
        $dataSolicitacao = $_POST['dataSolicitacao'];
        $horaSolicitacao = $_POST['horaSolicitacao'];

        // Verifique se o horário já está ocupado
        try {
            $pdo = Conexao::LigarConexao();
            $sql = 'SELECT COUNT(*) FROM tbl_solicitacao_aula WHERE dataSolicitacao = :dataSolicitacao AND horaSolicitacao = :horaSolicitacao';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':dataSolicitacao' => $dataSolicitacao,
                ':horaSolicitacao' => $horaSolicitacao
            ]);
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                $message = 'Este horário já está reservado. Por favor, escolha outro horário.';
            } else {
                // Data e hora atuais no horário de São Paulo
                $dataCriacao = date('Y-m-d H:i:s');

                // Prepare a instrução SQL para inserir os dados na tabela
                $sql = 'INSERT INTO tbl_solicitacao_aula (idAluno, dataSolicitacao, horaSolicitacao, statusSolicitacao, dataCriacao) VALUES (:idAluno, :dataSolicitacao, :horaSolicitacao, "PENDENTE", :dataCriacao)';
                $stmt = $pdo->prepare($sql);

                // Execute a instrução SQL com os dados fornecidos
                $stmt->execute([
                    ':idAluno' => $idAluno,
                    ':dataSolicitacao' => $dataSolicitacao,
                    ':horaSolicitacao' => $horaSolicitacao,
                    ':dataCriacao' => $dataCriacao
                ]);

                // Mensagem de sucesso
                $message = 'Aula solicitada com sucesso!';
            }
        } catch (PDOException $e) {
            // Mensagem de erro
            $message = 'Erro ao solicitar aula. Por favor, tente novamente.';
        }
    } else {
        // Dados não enviados corretamente
        $message = 'Erro ao solicitar aula. Por favor, tente novamente.';
    }
}

// Obtenha os horários ocupados do banco de dados
$selectedDate = isset($_GET['date']) ? $_GET['date'] : '';
if ($selectedDate) {
    try {
        $pdo = Conexao::LigarConexao();
        $sql = 'SELECT horaSolicitacao FROM tbl_solicitacao_aula WHERE dataSolicitacao = :date';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':date' => $selectedDate]);
        $occupiedTimes = $stmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        // Mensagem de erro ao obter horários ocupados
        $message = 'Erro ao obter horários ocupados.';
    }
}

// Defina o dia de hoje
$today = new DateTime();
$today->setTime(0, 0, 0);

// Obtenha o primeiro dia do mês atual
$firstDayOfCurrentMonth = new DateTime($today->format('Y-m-01'));

// Verifique se a data selecionada é válida
if (isset($_GET['date'])) {
    $selectedDateTime = new DateTime($_GET['date']);
    if ($selectedDateTime < $today) {
        $disabledDates[] = $_GET['date'];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .calendar-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 2rem;
        }

        .calendar {
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            overflow: hidden;
            max-width: 600px;
            width: 100%;
        }

        .calendar-header {
            background-color: #f8f9fa;
            text-align: center;
            padding: 1rem;
        }

        .calendar-body {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            grid-auto-rows: 100px;
            gap: 1px;
        }

        .calendar-day {
            text-align: center;
            line-height: 100px;
            cursor: pointer;
            background-color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .calendar-day.inactive {
            color: #6c757d;
            cursor: default;
        }

        .calendar-day.disabled {
            background-color: #e9ecef;
            color: #6c757d;
            cursor: not-allowed;
        }

        .calendar-day.active {
            background-color: #007bff;
            color: white;
        }

        .calendar-day:hover:not(.disabled) {
            background-color: #e9ecef;
        }

        .calendar-weekday {
            background-color: #f8f9fa;
            text-align: center;
            line-height: 40px;
            font-weight: bold;
        }

        .modal-body {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            justify-content: center;
        }

        .time-button {
            flex: 0 0 100px;
            margin: 0.2rem;
            text-align: center;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 0.5rem;
            border-radius: 0.25rem;
        }

        .time-button.selected {
            background-color: #0056b3;
        }

        .time-button.disabled {
            background-color: #e9ecef;
            color: #6c757d;
            cursor: not-allowed;
        }

        .modal-footer {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
        }

        .hidden {
            display: none;
        }
    </style>
</head>

<body>
    <!-- Mensagem de erro ou sucesso -->
    <?php if ($message) : ?>
            <div class="alert alert-info" role="alert">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
    <div class="container">
        <div class="calendar-container">
            <div class="calendar">
                <div class="calendar-header">
                    <button class="btn btn-secondary" id="prevMonth">&laquo; Mês Anterior</button>
                    <span id="monthYear" class="mx-3"></span>
                    <button class="btn btn-secondary hidden" id="nextMonth">Próximo Mês &raquo;</button>
                </div>
                <div id="calendarBody" class="calendar-body">
                    <div class="calendar-weekday">Dom</div>
                    <div class="calendar-weekday">Seg</div>
                    <div class="calendar-weekday">Ter</div>
                    <div class="calendar-weekday">Qua</div>
                    <div class="calendar-weekday">Qui</div>
                    <div class="calendar-weekday">Sex</div>
                    <div class="calendar-weekday">Sáb</div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="timeModal" tabindex="-1" aria-labelledby="timeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="timeModalLabel">Escolha um horário</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="timeButtonsContainer">
                        <!-- Horários disponíveis serão adicionados aqui -->
                    </div>
                    <div class="modal-footer">
                        <form action="agendamento.php" method="POST">
                            <input type="hidden" name="dataSolicitacao" id="selectedDate">
                            <input type="hidden" name="horaSolicitacao" id="selectedTime">
                            <button type="submit" class="btn btn-primary">Solicitar Aula</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const calendarBody = document.getElementById('calendarBody');
            const monthYearElement = document.getElementById('monthYear');
            const prevMonthButton = document.getElementById('prevMonth');
            const nextMonthButton = document.getElementById('nextMonth');
            const today = new Date();
            const disabledDates = <?php echo json_encode($disabledDates); ?>;
            const occupiedTimes = <?php echo json_encode($occupiedTimes); ?>;

            let currentYear = today.getFullYear();
            let currentMonth = today.getMonth();

            const updateCalendar = () => {
                const firstDay = new Date(currentYear, currentMonth, 1);
                const lastDay = new Date(currentYear, currentMonth + 1, 0);
                const firstDayOfWeek = firstDay.getDay();
                const daysInMonth = lastDay.getDate();

                calendarBody.innerHTML = `
                    <div class="calendar-weekday">Dom</div>
                    <div class="calendar-weekday">Seg</div>
                    <div class="calendar-weekday">Ter</div>
                    <div class="calendar-weekday">Qua</div>
                    <div class="calendar-weekday">Qui</div>
                    <div class="calendar-weekday">Sex</div>
                    <div class="calendar-weekday">Sáb</div>
                `;

                // Adicione os espaços vazios antes do primeiro dia do mês
                for (let i = 0; i < firstDayOfWeek; i++) {
                    calendarBody.innerHTML += '<div class="calendar-day disabled"></div>';
                }

                // Adicione os dias do mês
                for (let day = 1; day <= daysInMonth; day++) {
                    const currentDay = new Date(currentYear, currentMonth, day);
                    const formattedDate = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    const isToday = currentDay.toDateString() === today.toDateString();
                    const isDisabled = disabledDates.includes(formattedDate) || currentDay.getDay() === 0 || currentDay.getDay() === 6 || currentDay < today;
                    const dayClass = isToday ? 'calendar-day active' : 'calendar-day';
                    const dayClassWithDisabled = isDisabled ? 'calendar-day disabled' : dayClass;

                    calendarBody.innerHTML += `<div class="${dayClassWithDisabled}" data-date="${formattedDate}">${day}</div>`;
                }

                // Adicione os espaços vazios após o último dia do mês
                const totalDaysInCalendar = 42; // 6 semanas * 7 dias
                const daysToAdd = totalDaysInCalendar - (firstDayOfWeek + daysInMonth);
                for (let i = 0; i < daysToAdd; i++) {
                    calendarBody.innerHTML += '<div class="calendar-day disabled"></div>';
                }

                // Verifique se o mês anterior deve ser oculto
                prevMonthButton.classList.toggle('hidden', currentYear === today.getFullYear() && currentMonth === today.getMonth());
                // Verifique se o próximo mês deve ser visível
                nextMonthButton.classList.toggle('hidden', false);
            };

            const updateMonthYear = () => {
                const monthNames = [
                    'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                    'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
                ];
                monthYearElement.textContent = `${monthNames[currentMonth]} ${currentYear}`;
            };

            const loadAvailableTimes = (date) => {
                const timeButtonsContainer = document.getElementById('timeButtonsContainer');
                const availableTimes = [
                    '08:00', '09:00', '10:00', '11:00', '12:00',
                    '13:00', '14:00', '15:00', '16:00', '17:00'
                ];

                timeButtonsContainer.innerHTML = '';

                availableTimes.forEach(time => {
                    const isOccupied = occupiedTimes.includes(time);
                    const button = document.createElement('button');
                    button.className = `time-button ${isOccupied ? 'disabled' : ''}`;
                    button.textContent = time;
                    button.disabled = isOccupied;
                    button.addEventListener('click', () => {
                        document.querySelectorAll('.time-button').forEach(btn => btn.classList.remove('selected'));
                        button.classList.add('selected');
                        document.getElementById('selectedTime').value = time;
                    });
                    timeButtonsContainer.appendChild(button);
                });
            };

            calendarBody.addEventListener('click', (event) => {
                if (event.target.classList.contains('calendar-day') && !event.target.classList.contains('disabled')) {
                    const selectedDate = event.target.getAttribute('data-date');
                    document.getElementById('selectedDate').value = selectedDate;
                    loadAvailableTimes(selectedDate);
                    new bootstrap.Modal(document.getElementById('timeModal')).show();
                }
            });

            prevMonthButton.addEventListener('click', () => {
                if (currentMonth === 0) {
                    currentMonth = 11;
                    currentYear--;
                } else {
                    currentMonth--;
                }
                updateCalendar();
                updateMonthYear();
            });

            nextMonthButton.addEventListener('click', () => {
                if (currentMonth === 11) {
                    currentMonth = 0;
                    currentYear++;
                } else {
                    currentMonth++;
                }
                updateCalendar();
                updateMonthYear();
            });

            updateCalendar();
            updateMonthYear();
        });
    </script>
</body>

</html>