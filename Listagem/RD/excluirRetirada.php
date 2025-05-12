<?php
include '../../conect.php'; 

if (isset($_GET['id_retirada'])) {
    $id_retirada = $_GET['id_retirada'];

    $sql_delete_equipamentos = "DELETE FROM equipamento_retirado WHERE id_retirada = ?";
    $stmt = $conn->prepare($sql_delete_equipamentos);
    $stmt->bind_param("i", $id_retirada);
    $stmt->execute();
    $stmt->close();

    $sql_delete_retirada = "DELETE FROM retirada WHERE id_retirada = ?";
    $stmt = $conn->prepare($sql_delete_retirada);
    $stmt->bind_param("i", $id_retirada);

    if ($stmt->execute()) {
        echo "<script>alert('Retirada excluída com sucesso!'); window.location.href='listaRetirada.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir retirada!'); window.location.href='listaRetirada.php';</script>";
    }
    $stmt->close();
}

$conn->close();
?>
