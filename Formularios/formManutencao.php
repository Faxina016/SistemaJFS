<?php
include '../conect.php';

// Buscar todos os equipamentos disponíveis
$sql = "SELECT id_equipamento, numero_patrimonio FROM equipamento WHERE status = 'disponivel'";
$result = $conn->query($sql);
$equipamentos = $result->fetch_all(MYSQLI_ASSOC);

// Se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_equipamento = $_POST['id_equipamento'];
    $data_inicio = $_POST['data_inicio'];
    $data_fim = !empty($_POST['data_fim']) ? $_POST['data_fim'] : NULL; // Se vazio, seta NULL
    $descricao = $_POST['descricao'];

    // Inserir manutenção no banco
    $sql = "INSERT INTO historico_manutencao (id_equipamento, data_inicio, data_fim, descricao) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $id_equipamento, $data_inicio, $data_fim, $descricao);

    if ($stmt->execute()) {
        // Atualiza o status do equipamento para "em_manutencao"
        $sql_update = "UPDATE equipamento SET status = 'em_manutencao' WHERE id_equipamento = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("i", $id_equipamento);
        $stmt_update->execute();
        $stmt_update->close();

        echo "<script>alert('Manutenção cadastrada com sucesso!'); window.location.href='formManutencao.php';</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar manutenção!');</script>";
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Manutenção</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h2>Cadastrar Nova Manutenção</h2>
    <form method="POST">
        <label>Número de Patrimônio:</label>
        <select name="id_equipamento" required>
            <option value="">Selecione um equipamento</option>
            <?php foreach ($equipamentos as $equipamento): ?>
                <option value="<?= $equipamento['id_equipamento']; ?>">
                    <?= $equipamento['numero_patrimonio']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Data Início:</label>
        <input type="date" name="data_inicio" required>

        <label>Data Fim (opcional):</label>
        <input type="date" name="data_fim">

        <label>Descrição:</label>
        <textarea name="descricao" required></textarea>

        <button type="submit">Cadastrar</button>
        <a href="../Listagem/Manutencao/listaManutencao.php" class="btn btn-back">Voltar</a>
    </form>
    <a href="../CentralAtendimento.php" class="help-button" id="randomLink" target="_blank">?</a>
</body>
</html>