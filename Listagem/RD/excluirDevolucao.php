<?php
include '../../conect.php'; 

$id_devolucao = intval($_GET['id_devolucao']);

$conn->begin_transaction();

try {
    $sql_retirada = "SELECT id_retirada FROM devolucao WHERE id_devolucao = ?";
    $stmt = $conn->prepare($sql_retirada);
    $stmt->bind_param("i", $id_devolucao);
    $stmt->execute();
    $result = $stmt->get_result();
    $devolucao = $result->fetch_assoc();

    if (!$devolucao) {
        throw new Exception("Devolução não encontrada.");
    }

    $id_retirada = $devolucao['id_retirada'];

    $sql_delete_equipamentos = "DELETE FROM equipamento_retirado WHERE id_retirada = ?";
    $stmt = $conn->prepare($sql_delete_equipamentos);
    $stmt->bind_param("i", $id_retirada);
    if (!$stmt->execute()) {
        throw new Exception("Erro ao excluir equipamentos retirados: " . $stmt->error);
    }

    $sql_delete_devolucao = "DELETE FROM devolucao WHERE id_devolucao = ?";
    $stmt = $conn->prepare($sql_delete_devolucao);
    $stmt->bind_param("i", $id_devolucao);
    if (!$stmt->execute()) {
        throw new Exception("Erro ao excluir devolução: " . $stmt->error);
    }

    $sql_delete_retirada = "DELETE FROM retirada WHERE id_retirada = ?";
    $stmt = $conn->prepare($sql_delete_retirada);
    $stmt->bind_param("i", $id_retirada);
    if (!$stmt->execute()) {
        throw new Exception("Erro ao excluir retirada: " . $stmt->error);
    }

    $conn->commit();
    
    echo "<script>alert('Devolução, equipamentos e retirada excluídos com sucesso!'); window.location.href='listaDevolucao.php';</script>";

} catch (Exception $e) {
    $conn->rollback();
    echo "<script>alert('Erro: " . $e->getMessage() . "'); window.location.href='listaDevolucao.php';</script>";
}
?>
