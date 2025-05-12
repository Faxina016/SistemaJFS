<?php
require_once '../conect.php';
session_start();

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $sql = "UPDATE reservas SET status = 'confirmada' WHERE id = $id AND status = 'pendente'";
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['mensagem'] = "Reserva confirmada com sucesso!";
        $_SESSION['tipo'] = "sucesso";
    } else {
        $_SESSION['mensagem'] = "Erro ao confirmar a reserva.";
        $_SESSION['tipo'] = "erro";
    }
} else {
    $_SESSION['mensagem'] = "ID de reserva não fornecido.";
    $_SESSION['tipo'] = "erro";
}

header("Location: formReservas.php");
exit;
?> 