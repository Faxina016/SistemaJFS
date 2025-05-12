<?php
include '../../conect.php'; 

if (isset($_GET['id_devolucao'])) {
    $id_devolucao = $_GET['id_devolucao'];

    $sql = "SELECT id_retirada, DATE_FORMAT(data_devolucao, '%Y-%m-%d') as data, DATE_FORMAT(hora_devolucao, '%H:%i') as hora FROM devolucao WHERE id_devolucao = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_devolucao);
    $stmt->execute();
    $result = $stmt->get_result();
    $devolucao = $result->fetch_assoc();
    $stmt->close();
    
    if (!$devolucao) {
        die("Devolução não encontrada.");
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hora_devolucao = $_POST['hora_devolucao'];
    $data_devolucao = $_POST['data_devolucao'];

    $sql = "UPDATE devolucao SET hora_devolucao = ?, data_devolucao = ? WHERE id_devolucao = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $hora_devolucao, $data_devolucao, $id_devolucao);

    if ($stmt->execute()) {
        echo "Devolução atualizada com sucesso.";
    } else {
        echo "Erro ao atualizar devolução: " . $conn->error;
    }
    $stmt->close();
    header("Location: listaDevolucao.php");
    exit;
}
?>

<?php include '../../menu.php'; ?>
<link rel="stylesheet" href="../../style.css?v=<?php echo time(); ?>">

<center>
    <h2>Editar Devolução</h2>
</center>

<form method="POST">
    <label>Hora da Devolução:</label>
    <input type="time" name="hora_devolucao" value="<?php echo htmlspecialchars($devolucao['hora_devolucao']); ?>" required>
    
    <label>Data da Devolução:</label>
    <input type="date" name="data_devolucao" value="<?php echo htmlspecialchars($devolucao['data_devolucao']); ?>" required>
    <input type="submit" value="Salvar">
    <center>
    <a href="listaDevolucao.php" class="btn btn-back">Voltar</a>
    </center>
</form>
<footer style="text-align: center; padding: 10px; margin-top: 20px; opacity: 0.3;">
    <p>© Pedro e Antony, 2025. Todos os direitos reservados.</p>
</footer>
