<?php
if (!defined('ROOT_DIR')) {
    include('../config.php');
}
require_once '../conect.php';
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Reserva de Equipamentos</title>
    <link rel="stylesheet" href="../style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .tabs {
            display: flex;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }
        
        .tab-btn {
            padding: 10px 20px;
            background: #f1f1f1;
            border: none;
            border-radius: 5px 5px 0 0;
            cursor: pointer;
            margin-right: 5px;
        }
        
        .tab-btn.active {
            background: #3b82f6;
            color: white;
        }
        
        .tab-content {
            display: none;
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .form-control {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .btn-primary {
            background: #3b82f6;
            color: white;
        }
        
        .btn-primary:hover {
            background: #2563eb;
        }
        
        .calendar {
            margin-top: 20px;
        }
        
        .calendar-day {
            background: #f9f9f9;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 5px;
        }
        
        .reservation-item {
            background: white;
            border-left: 4px solid #3b82f6;
            padding: 10px;
            margin: 5px 0;
            border-radius: 4px;
        }
        
        .equipment-selector {
            margin-top: 20px;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 4px;
        }
        
        .equipment-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        
        .equipment-item {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 4px;
            width: calc(33.333% - 10px);
            box-sizing: border-box;
        }
        
        .equipment-item.selected {
            background: #e6f2ff;
            border-color: #3b82f6;
        }
    </style>
</head>
<body>
    <?php include '../menu.php'; ?>
    <br><br>
    <div class="container">
        <h1>Sistema de Reserva de Equipamentos</h1>
        
        <div class="tabs">
            <button class="tab-btn active" data-tab="nova-reserva">Nova Reserva</button>
            <button class="tab-btn" data-tab="minhas-reservas">Reservas Cadastradas</button>
            <button class="tab-btn" data-tab="calendario">Calendário</button>
        </div>
        
        <div id="nova-reserva" class="tab-content active">
            <h2>Fazer Nova Reserva</h2>
            <form id="reservationForm" method="post" action="processarReserva.php">
                <div class="form-group">
                    <label for="professor">Professor:</label>
                    <select name="professor" id="professor" class="form-control" required>
                        <option value="">Selecione um professor</option>
                        <?php
                        $sql_professores = "SELECT id_professor, nome FROM professor ORDER BY nome";
                        $result_professores = $conn->query($sql_professores);
                        
                        if ($result_professores && $result_professores->num_rows > 0) {
                            while($row = $result_professores->fetch_assoc()) {
                                echo '<option value="' . $row['id_professor'] . '">' . $row['nome'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="data">Data da Reserva:</label>
                    <input type="text" id="data" name="data" class="form-control datepicker" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="hora_inicio">Hora de Início:</label>
                        <input type="text" id="hora_inicio" name="hora_inicio" class="form-control timepicker" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="hora_fim">Hora de Término:</label>
                        <input type="text" id="hora_fim" name="hora_fim" class="form-control timepicker" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="id_sala">Série:</label>
                    <select name="id_sala" id="id_sala" class="form-control" required>
                        <option value="">Selecione uma série</option>
                        <?php
                        $sql_salas = "SELECT id_sala, serie, tipo FROM sala ORDER BY tipo, serie";
                        $result_salas = $conn->query($sql_salas);
                        
                        if ($result_salas && $result_salas->num_rows > 0) {
                            while($row = $result_salas->fetch_assoc()) {
                                echo '<option value="' . $row['id_sala'] . '">[ID: ' . $row['id_sala'] . '] ' . $row['serie'] . ' - ' . $row['tipo'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="disciplina">Disciplina:</label>
                    <input type="text" id="disciplina" name="disciplina" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="observacoes">Observações:</label>
                    <textarea id="observacoes" name="observacoes" class="form-control" rows="3"></textarea>
                </div>
                
                <div class="equipment-selector">
                    <h3>Selecione os Equipamentos</h3>
                    <p>Escolha os equipamentos que deseja reservar:</p>
                    
                    <div class="equipment-list" id="equipmentList">
                        <!-- Carregado via AJAX quando a data/hora for selecionada -->
                        <p>Selecione a data e horário para ver os equipamentos disponíveis</p>
                    </div>
                </div>
                <br>
                <center>
                <button type="submit" class="btn btn-primary">Fazer Reserva</button>
                </center>
            </form>
        </div>
        
        <div id="minhas-reservas" class="tab-content">
            <h2>Reservas Cadastradas</h2>
            
            <div class="reservations-list">
                <?php
                $sql_reservas = "SELECT r.*, p.nome as professor 
                                FROM reservas r 
                                JOIN professor p ON r.id_professor = p.id_professor 
                                ORDER BY r.data_reserva DESC, r.hora_inicio";
                $result_reservas = $conn->query($sql_reservas);
                
                if ($result_reservas && $result_reservas->num_rows > 0) {
                    while($reserva = $result_reservas->fetch_assoc()) {
                        // Buscar informações da sala
                        $sala_info = "N/A";
                        if (isset($reserva['id_sala'])) {
                            $sql_sala = "SELECT serie, tipo FROM sala WHERE id_sala = " . $reserva['id_sala'];
                            $result_sala = $conn->query($sql_sala);
                            if ($result_sala && $row_sala = $result_sala->fetch_assoc()) {
                                $sala_info = '[ID: ' . $reserva['id_sala'] . '] ' . $row_sala['serie'] . ' - ' . $row_sala['tipo'];
                            }
                        } elseif (isset($reserva['sala'])) {
                            $sql_sala = "SELECT serie, tipo FROM sala WHERE id_sala = " . $reserva['sala'];
                            $result_sala = $conn->query($sql_sala);
                            if ($result_sala && $row_sala = $result_sala->fetch_assoc()) {
                                $sala_info = '[ID: ' . $reserva['sala'] . '] ' . $row_sala['serie'] . ' - ' . $row_sala['tipo'];
                            } else {
                                $sala_info = '[ID: ' . $reserva['sala'] . ']';
                            }
                        }
                        
                        echo '<div class="reservation-item">';
                        echo '<h3>Reserva #' . $reserva['id'] . '</h3>';
                        echo '<p><strong>Professor:</strong> ' . $reserva['professor'] . '</p>';
                        echo '<p><strong>Data:</strong> ' . date('d/m/Y', strtotime($reserva['data_reserva'])) . '</p>';
                        echo '<p><strong>Horário:</strong> ' . substr($reserva['hora_inicio'], 0, 5) . ' - ' . substr($reserva['hora_fim'], 0, 5) . '</p>';
                        echo '<p><strong>Série:</strong> ' . $sala_info . '</p>';
                        echo '<p><strong>Disciplina:</strong> ' . $reserva['disciplina'] . '</p>';
                        echo '<p><strong>Status:</strong> ' . ucfirst($reserva['status']) . '</p>';
                        
                        // Listar equipamentos reservados
                        $sql_equipamentos = "SELECT e.tipo, e.numero_patrimonio 
                                            FROM itens_reserva ir 
                                            JOIN equipamento e ON ir.id_equipamento = e.id_equipamento 
                                            WHERE ir.id_reserva = " . $reserva['id'];
                        $result_equipamentos = $conn->query($sql_equipamentos);
                        
                        if ($result_equipamentos && $result_equipamentos->num_rows > 0) {
                            echo '<p><strong>Equipamentos:</strong></p>';
                            echo '<ul>';
                            while($equip = $result_equipamentos->fetch_assoc()) {
                                echo '<li>' . $equip['tipo'] . ' - Patrimônio: ' . $equip['numero_patrimonio'] . '</li>';
                            }
                            echo '</ul>';
                        }
                        
                        // Botões de ação dependendo do status
                        if ($reserva['status'] == 'pendente' || $reserva['status'] == 'confirmada') {
                            echo '<a href="cancelarReserva.php?id=' . $reserva['id'] . '" class="btn btn-danger" onclick="return confirm(\'Tem certeza que deseja cancelar esta reserva?\')">Cancelar</a> ';
                        }
                        
                        if ($reserva['status'] == 'pendente') {
                            echo '<a href="confirmarReserva.php?id=' . $reserva['id'] . '" class="btn btn-success">Confirmar</a>';
                        }
                        
                        echo '</div>';
                    }
                } else {
                    echo '<p>Nenhuma reserva encontrada.</p>';
                }
                ?>
            </div>
        </div>
        
        <div id="calendario" class="tab-content">
            <h2>Calendário de Reservas</h2>
            
            <div class="calendar">
                <?php
                // Mostrar os próximos 7 dias
                $hoje = new DateTime();
                
                for ($i = 0; $i < 7; $i++) {
                    $data = clone $hoje;
                    $data->modify("+$i day");
                    $dataStr = $data->format('Y-m-d');
                    $diaSemana = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'][$data->format('w')];
                    
                    echo '<div class="calendar-day">';
                    echo '<h3>' . $diaSemana . ', ' . $data->format('d/m/Y') . '</h3>';
                    
                    // Buscar reservas para este dia
                    $sql_dia = "SELECT r.*, p.nome as professor 
                               FROM reservas r 
                               JOIN professor p ON r.id_professor = p.id_professor 
                               WHERE r.data_reserva = '$dataStr' 
                               AND r.status != 'cancelada' 
                               ORDER BY r.hora_inicio";
                    $result_dia = $conn->query($sql_dia);
                    
                    if ($result_dia && $result_dia->num_rows > 0) {
                        while($evento = $result_dia->fetch_assoc()) {
                            // Buscar informações da sala
                            $sala_info = "N/A";
                            if (isset($evento['id_sala'])) {
                                $sql_sala = "SELECT serie, tipo FROM sala WHERE id_sala = " . $evento['id_sala'];
                                $result_sala = $conn->query($sql_sala);
                                if ($result_sala && $row_sala = $result_sala->fetch_assoc()) {
                                    $sala_info = '[ID: ' . $evento['id_sala'] . '] ' . $row_sala['serie'] . ' - ' . $row_sala['tipo'];
                                }
                            } elseif (isset($evento['sala'])) {
                                $sql_sala = "SELECT serie, tipo FROM sala WHERE id_sala = " . $evento['sala'];
                                $result_sala = $conn->query($sql_sala);
                                if ($result_sala && $row_sala = $result_sala->fetch_assoc()) {
                                    $sala_info = '[ID: ' . $evento['sala'] . '] ' . $row_sala['serie'] . ' - ' . $row_sala['tipo'];
                                } else {
                                    $sala_info = '[ID: ' . $evento['sala'] . ']';
                                }
                            }
                            
                            echo '<div class="reservation-item">';
                            echo '<p><strong>' . substr($evento['hora_inicio'], 0, 5) . ' - ' . substr($evento['hora_fim'], 0, 5) . '</strong> | Série: ' . $sala_info . '</p>';
                            echo '<p>Professor: ' . $evento['professor'] . ' | ' . $evento['disciplina'] . '</p>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>Nenhuma reserva</p>';
                    }
                    
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar Flatpickr para seleção de data
            flatpickr(".datepicker", {
                locale: "pt",
                dateFormat: "Y-m-d",
                minDate: "today",
                disableMobile: true
            });
            
            // Inicializar Flatpickr para seleção de hora
            flatpickr(".timepicker", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                minuteIncrement: 15,
                disableMobile: true
            });
            
            // Tabs
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');
            
            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const tabId = button.getAttribute('data-tab');
                    
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));
                    
                    button.classList.add('active');
                    document.getElementById(tabId).classList.add('active');
                });
            });
            
            // Verificar disponibilidade de equipamentos quando data/hora for selecionada
            const dataInput = document.getElementById('data');
            const horaInicioInput = document.getElementById('hora_inicio');
            const horaFimInput = document.getElementById('hora_fim');
            const equipmentList = document.getElementById('equipmentList');
            
            function verificarDisponibilidade() {
                const data = dataInput.value;
                const horaInicio = horaInicioInput.value;
                const horaFim = horaFimInput.value;
                
                console.log("Verificando disponibilidade:", data, horaInicio, horaFim);
                
                if (data && horaInicio && horaFim) {
                    // Fazer requisição AJAX para verificar equipamentos disponíveis
                    const url = `verificarDisponibilidade.php?data=${data}&hora_inicio=${horaInicio}&hora_fim=${horaFim}`;
                    console.log("URL:", url);
                    
                    fetch(url)
                        .then(response => {
                            console.log("Status resposta:", response.status);
                            return response.json();
                        })
                        .then(data => {
                            console.log("Dados recebidos:", data);
                            equipmentList.innerHTML = '';
                            
                            if (!data || data.error) {
                                console.error("Erro:", data ? data.error : "Dados inválidos");
                                equipmentList.innerHTML = '<p>Erro ao carregar equipamentos. Consulte o console para mais detalhes.</p>';
                                return;
                            }
                            
                            if (data.length === 0) {
                                equipmentList.innerHTML = '<p>Nenhum equipamento disponível para este horário.</p>';
                                return;
                            }
                            
                            data.forEach((equip, index) => {
                                console.log(`Equipamento #${index}:`, equip);
                                const item = document.createElement('div');
                                item.className = 'equipment-item';
                                item.setAttribute('data-id', equip.id_equipamento);
                                
                                const tipo = equip.tipo || 'Sem tipo';
                                const patrimonio = equip.numero_patrimonio || 'Sem patrimônio';
                                
                                item.innerHTML = `
                                    <input type="checkbox" name="equipamentos[]" value="${equip.id_equipamento}" id="equip-${equip.id_equipamento}">
                                    <label for="equip-${equip.id_equipamento}">
                                        <strong>${tipo}</strong>
                                        <br>Patrimônio: ${patrimonio}
                                    </label>
                                `;
                                
                                item.addEventListener('click', function() {
                                    const checkbox = this.querySelector('input[type="checkbox"]');
                                    checkbox.checked = !checkbox.checked;
                                    this.classList.toggle('selected', checkbox.checked);
                                });
                                
                                equipmentList.appendChild(item);
                            });
                        })
                        .catch(error => {
                            console.error("Erro ao verificar disponibilidade:", error);
                            equipmentList.innerHTML = '<p>Erro ao verificar disponibilidade de equipamentos.</p>';
                        });
                } else {
                    equipmentList.innerHTML = '<p>Preencha a data e horários para verificar equipamentos disponíveis.</p>';
                }
            }
            
            // Adicionar evento para verificar disponibilidade
            dataInput.addEventListener('change', verificarDisponibilidade);
            horaInicioInput.addEventListener('change', verificarDisponibilidade);
            horaFimInput.addEventListener('change', function() {
                verificarDisponibilidade();
                // Mostrar mensagem explícita pedindo para aguardar caso a lista esteja vazia
                if (!equipmentList.hasChildNodes() || equipmentList.innerHTML.trim() === '') {
                    equipmentList.innerHTML = '<p>Verificando disponibilidade de equipamentos, por favor aguarde...</p>';
                }
            });
        });
    </script>
</body>
</html>