<?php
include '../conect.php'; 

$msg = '';
$msg_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_retirada = $_POST['id_retirada'];
    $data_devolucao = $_POST['data_devolucao'];
    $hora_devolucao = $_POST['hora_devolucao'];
    $estadoposuso = $_POST['estadoposuso'];

    $conn->begin_transaction();

    try {
        $sql = "INSERT INTO devolucao (id_retirada, data_devolucao, hora_devolucao, estadoposuso) 
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Erro na preparação da consulta: " . $conn->error);
        }

        $stmt->bind_param("isss", $id_retirada, $data_devolucao, $hora_devolucao, $estadoposuso);

        if (!$stmt->execute()) {
            throw new Exception("Erro ao registrar devolução: " . $stmt->error);
        }

        $update_sql = "UPDATE equipamento e
                       JOIN equipamento_retirado er ON e.id_equipamento = er.id_equipamento
                       SET e.status = 'disponivel'
                       WHERE er.id_retirada = ?";
        $update_stmt = $conn->prepare($update_sql);
        if ($update_stmt === false) {
            throw new Exception("Erro na preparação da consulta de atualização: " . $conn->error);
        }

        $update_stmt->bind_param("i", $id_retirada);
        if (!$update_stmt->execute()) {
            throw new Exception("Erro ao atualizar status dos equipamentos: " . $update_stmt->error);
        }

        $conn->commit();

        $msg = "✅ Devolução registrada com sucesso! Equipamentos foram atualizados para 'disponível'.";
        $msg_type = 'success';

    } catch (Exception $e) {
        $conn->rollback();

        $msg = "❌ Erro ao registrar devolução: " . $e->getMessage();
        $msg_type = 'error';
    }
}
include '../menu.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devolução de Equipamento</title>
    <link rel="stylesheet" href="../style.css?v=<?php echo time(); ?>">
</head>
<body>
   <br><br><br>

    <?php if ($msg): ?>
        <div id="msgBox" class="msg <?php echo $msg_type; ?>">
            <?php echo $msg; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
    <h1>Devolução de Equipamento</h1>
        <label for="id_retirada">Retirada:</label>
        <select id="id_retirada" name="id_retirada" required>
            <?php
            $result = $conn->query("SELECT id_retirada FROM retirada
                                    WHERE id_retirada NOT IN (SELECT id_retirada FROM devolucao)");

            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['id_retirada']}'>{$row['id_retirada']}</option>";
            }
            ?>
        </select>

        <label for="data_devolucao">Data da Devolução:</label>
        <input type="date" id="data_devolucao" name="data_devolucao" required>

        <label for="hora_devolucao">Hora da Devolução:</label>
        <input type="time" id="hora_devolucao" name="hora_devolucao" required>

        <label for="estadoposuso">Estado Pós-Uso:</label>
        <input type="text" id="estadoposuso" name="estadoposuso" required>

        <div style="display: flex; justify-content: center; gap: 10px; margin-top: 20px;">
    <button type="submit">Registrar Devolução</button>
    <button type="button" onclick="window.location.href='formRetiradaEquipamento.php';">Registrar Retirada</button>
</div>
<center>
<a href="../Listagem/RD/listaDevolucao.php" class="ret">Lista de Devolução</a>
</center>
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const msgBox = document.getElementById("msgBox");
            if (msgBox) {
                setTimeout(function() {
                    msgBox.style.display = "none";
                }, 5000);
            }
        });
    </script>
<a href="formRetiradaEquipamento.php" class="btn btn-back">Voltar</a>
<footer style="text-align: center; padding: 10px; margin-top: 20px; opacity: 0.3;">
    <p>© Pedro e Antony, 2025. Todos os direitos reservados.</p>
</footer>
<a href="../CentralAtendimento.php" class="help-button" id="randomLink" target="_blank">?</a>
</body>
</html>
