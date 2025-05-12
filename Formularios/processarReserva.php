<?php
require_once '../conect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar dados da reserva
    if (empty($_POST['professor']) || empty($_POST['data']) || empty($_POST['hora_inicio']) || 
        empty($_POST['hora_fim']) || empty($_POST['id_sala']) || empty($_POST['disciplina']) || 
        !isset($_POST['equipamentos']) || empty($_POST['equipamentos'])) {
        
        $_SESSION['mensagem'] = "Todos os campos são obrigatórios e pelo menos um equipamento deve ser selecionado.";
        $_SESSION['tipo'] = "erro";
        header("Location: formReservas.php");
        exit;
    }
    
    $id_professor = $_POST['professor'];
    $data = $_POST['data'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fim = $_POST['hora_fim'];
    $id_sala = $_POST['id_sala'];
    $disciplina = $_POST['disciplina'];
    $observacoes = isset($_POST['observacoes']) ? $_POST['observacoes'] : '';
    $equipamentos = $_POST['equipamentos'];
    
    // Validar horário
    if ($hora_inicio >= $hora_fim) {
        $_SESSION['mensagem'] = "O horário de início deve ser anterior ao horário de término.";
        $_SESSION['tipo'] = "erro";
        header("Location: formReservas.php");
        exit;
    }
    
    // Verificar conflitos de horário
    $sql_conflito = "SELECT r.* FROM reservas r
                   WHERE r.data_reserva = '$data'
                   AND r.status IN ('pendente', 'confirmada')
                   AND (
                       (r.hora_inicio <= '$hora_inicio' AND r.hora_fim > '$hora_inicio') OR
                       (r.hora_inicio < '$hora_fim' AND r.hora_fim >= '$hora_fim') OR
                       ('$hora_inicio' <= r.hora_inicio AND '$hora_fim' > r.hora_inicio)
                   )";
    
    $result_conflito = $conn->query($sql_conflito);
    
    if ($result_conflito && $result_conflito->num_rows > 0) {
        // Verificar se os equipamentos selecionados já estão reservados
        $ids_reservas = [];
        while ($row = $result_conflito->fetch_assoc()) {
            $ids_reservas[] = $row['id'];
        }
        
        if (!empty($ids_reservas)) {
            $ids_str = implode(',', $ids_reservas);
            $equipamentos_str = implode(',', $equipamentos);
            
            $sql_equip_reservados = "SELECT COUNT(*) as count FROM itens_reserva 
                                 WHERE id_reserva IN ($ids_str) 
                                 AND id_equipamento IN ($equipamentos_str)";
            
            $result_equip = $conn->query($sql_equip_reservados);
            $row_equip = $result_equip->fetch_assoc();
            
            if ($row_equip['count'] > 0) {
                $_SESSION['mensagem'] = "Alguns dos equipamentos selecionados já estão reservados neste horário.";
                $_SESSION['tipo'] = "erro";
                header("Location: formReservas.php");
                exit;
            }
        }
    }
    
    // Inserir a reserva
    $sql_reserva = "INSERT INTO reservas (id_professor, data_reserva, hora_inicio, hora_fim, id_sala, disciplina, observacoes, status) 
                   VALUES ('$id_professor', '$data', '$hora_inicio', '$hora_fim', '$id_sala', '$disciplina', '$observacoes', 'confirmada')";
    
    if ($conn->query($sql_reserva) === TRUE) {
        $id_reserva = $conn->insert_id;
        
        // Inserir os equipamentos
        $success = true;
        foreach ($equipamentos as $id_equipamento) {
            $sql_item = "INSERT INTO itens_reserva (id_reserva, id_equipamento) VALUES ($id_reserva, $id_equipamento)";
            if ($conn->query($sql_item) !== TRUE) {
                $success = false;
                break;
            }
        }
        
        if ($success) {
            $_SESSION['mensagem'] = "Reserva realizada com sucesso!";
            $_SESSION['tipo'] = "sucesso";
            
            // Buscar nome do professor
            $sql_professor = "SELECT nome FROM professor WHERE id_professor = $id_professor";
            $result_prof = $conn->query($sql_professor);
            $nome_professor = "";
            
            if ($result_prof && $row_prof = $result_prof->fetch_assoc()) {
                $nome_professor = $row_prof['nome'];
            }
            
            // Buscar nome da série
            $sql_sala = "SELECT serie, tipo FROM sala WHERE id_sala = $id_sala";
            $result_sala = $conn->query($sql_sala);
            $nome_sala = "";
            
            if ($result_sala && $row_sala = $result_sala->fetch_assoc()) {
                $nome_sala = $row_sala['serie'] . ' - ' . $row_sala['tipo'];
            }
        } else {
            // Se algo deu errado com os equipamentos, excluir a reserva
            $sql_delete = "DELETE FROM reservas WHERE id = $id_reserva";
            $conn->query($sql_delete);
            
            $_SESSION['mensagem'] = "Erro ao registrar os equipamentos.";
            $_SESSION['tipo'] = "erro";
        }
    } else {
        $_SESSION['mensagem'] = "Erro ao registrar a reserva.";
        $_SESSION['tipo'] = "erro";
    }
    
    header("Location: formReservas.php");
    exit;
} else {
    header("Location: formReservas.php");
    exit;
}
?> 