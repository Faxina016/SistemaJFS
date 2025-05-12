<?php
require_once '../conect.php';
header('Content-Type: application/json');

if (isset($_GET['data']) && isset($_GET['hora_inicio']) && isset($_GET['hora_fim'])) {
    $data = $_GET['data'];
    $hora_inicio = $_GET['hora_inicio'];
    $hora_fim = $_GET['hora_fim'];
    
    // Log para depuração
    error_log("Verificando disponibilidade para data=$data, hora_inicio=$hora_inicio, hora_fim=$hora_fim");
    
    // Consulta simplificada que retorna todos os equipamentos que não estão em uso no horário solicitado
    $sql = "SELECT e.* FROM equipamento e
            WHERE e.id_equipamento NOT IN (
                SELECT ir.id_equipamento 
                FROM itens_reserva ir
                JOIN reservas r ON ir.id_reserva = r.id
                WHERE r.data_reserva = '$data'
                AND r.status IN ('pendente', 'confirmada')
                AND (
                    (r.hora_inicio <= '$hora_inicio' AND r.hora_fim > '$hora_inicio') OR
                    (r.hora_inicio < '$hora_fim' AND r.hora_fim >= '$hora_fim') OR
                    ('$hora_inicio' <= r.hora_inicio AND '$hora_fim' > r.hora_inicio)
                )
            )";
    
    // Log da consulta SQL
    error_log("SQL: $sql");
    
    $result = $conn->query($sql);
    
    if (!$result) {
        error_log("Erro SQL: " . $conn->error);
        echo json_encode(['error' => 'Erro na consulta: ' . $conn->error]);
        exit;
    }
    
    $equipamentos = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $equipamentos[] = $row;
        }
        error_log("Encontrados " . count($equipamentos) . " equipamentos disponíveis");
    } else {
        error_log("Nenhum equipamento disponível encontrado");
    }
    
    echo json_encode($equipamentos);
} else {
    echo json_encode(['error' => 'Parâmetros incompletos']);
}
?> 