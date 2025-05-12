<?php
include '../conect.php'; 

$msg = '';
$msg_type = '';

// Inserir retirada
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data_retirada = $_POST['data_retirada'];
    $hora_retirada = $_POST['hora_retirada'];
    $id_professor = $_POST['id_professor'];
    $id_sala = $_POST['id_sala'];
    $equipamentos = $_POST['id_equipamento'] ?? []; // Garante que seja array

    if (empty($equipamentos)) {
        $msg = "Nenhum equipamento selecionado!";
        $msg_type = 'error';
    } else {
        $conn->autocommit(FALSE); // Inicia transação

        try {
            // Insere na tabela retirada
            $stmt_retirada = $conn->prepare("INSERT INTO retirada (data_retirada, hora_retirada, id_professor, id_sala) VALUES (?, ?, ?, ?)");
            if (!$stmt_retirada) {
                throw new Exception("Erro ao preparar inserção de retirada.");
            }
            $stmt_retirada->bind_param("ssii", $data_retirada, $hora_retirada, $id_professor, $id_sala);
            if (!$stmt_retirada->execute()) {
                throw new Exception("Erro ao inserir retirada: " . $stmt_retirada->error);
            }
            $id_retirada = $stmt_retirada->insert_id;

            // Insere cada equipamento retirado e atualiza o status para "em_uso"
            $stmt_equipamento = $conn->prepare("INSERT INTO equipamento_retirado (id_retirada, id_equipamento) VALUES (?, ?)");
            if (!$stmt_equipamento) {
                throw new Exception("Erro ao preparar inserção dos equipamentos.");
            }

            foreach ($equipamentos as $id_equipamento) {
                // Inserir o equipamento na tabela de equipamentos retirados
                $stmt_equipamento->bind_param("ii", $id_retirada, $id_equipamento);
                if (!$stmt_equipamento->execute()) {
                    throw new Exception("Erro ao inserir equipamento ID $id_equipamento: " . $stmt_equipamento->error);
                }

                // Atualizar o status do equipamento para "em_uso"
                $stmt_status = $conn->prepare("UPDATE equipamento SET status = 'em_uso' WHERE id_equipamento = ?");
                if (!$stmt_status) {
                    throw new Exception("Erro ao preparar atualização do status do equipamento.");
                }
                $stmt_status->bind_param("i", $id_equipamento);
                if (!$stmt_status->execute()) {
                    throw new Exception("Erro ao atualizar status do equipamento ID $id_equipamento: " . $stmt_status->error);
                }
            }

            $conn->commit(); // Confirma as inserções
            $msg = "✅ Equipamentos retirados com sucesso!";
            $msg_type = 'success';
        } catch (Exception $e) {
            $conn->rollback(); // Cancela a transação
            $msg = "❌ Erro ao registrar retirada: " . $e->getMessage();
            $msg_type = 'error';
        }
    }
}

include '../menu.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retirada de Equipamento</title>
    <link rel="stylesheet" href="../style.css?v=<?php echo time(); ?>">
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
</head>
<body>
   
<?php if ($msg): ?>
        <div class="popup <?php echo $msg_type; ?>" id="msgBox">
            <?php echo $msg; ?>
        </div>
    <?php endif; ?>

    <br><br><br>
    <form method="POST">
        <center>
    <h2>Retirada de Equipamento</h2>
    </center>
    <br>
        <label for="data_retirada">Data da Retirada:</label>
        <input type="date" id="data_retirada" name="data_retirada" required>

        <label for="hora_retirada">Hora da Retirada:</label>
        <input type="time" id="hora_retirada" name="hora_retirada" required>

        <label for="id_professor">Professor:</label>
        <select id="id_professor" name="id_professor" required>
            <?php
            $result = $conn->query("SELECT id_professor, nome FROM professor");

            if ($result === false) {
                echo "Erro na consulta para professores: " . $conn->error;
            } else {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id_professor']}'>{$row['nome']}</option>";
                }
            }
            ?>
        </select>

        <label for="id_sala">Sala:</label>
        <select id="id_sala" name="id_sala" required>
            <?php
            $sql = "SELECT id_sala, serie, tipo FROM sala ORDER BY tipo, serie";
            $result = $conn->query($sql);

            if ($result === false) {
                echo "Erro na consulta para salas: " . $conn->error;
            } else {
                $tipo_anterior = "";
                while ($row = $result->fetch_assoc()) {
                    if ($row['tipo'] !== $tipo_anterior) {
                        if ($tipo_anterior !== "") {
                            echo "</optgroup>";
                        }
                        echo "<optgroup label='{$row['tipo']}'>";
                        $tipo_anterior = $row['tipo'];
                    }
                    echo "<option value='{$row['id_sala']}'>{$row['serie']}</option>";
                }
                echo "</optgroup>";
            }
            ?>
        </select>

        <label>Equipamentos Disponíveis:</label>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; width: 100%;">
            <?php
            $sql = "SELECT e.id_equipamento, e.tipo, e.numero_patrimonio 
            FROM equipamento e
            LEFT JOIN equipamento_retirado er ON e.id_equipamento = er.id_equipamento
            LEFT JOIN devolucao d ON er.id_retirada = d.id_retirada
            WHERE (er.id_retirada IS NULL OR d.id_devolucao IS NOT NULL)
            AND e.status = 'disponivel'";
    

            $result = $conn->query($sql);

            if ($result === false) {
                echo "Erro na consulta para equipamentos: " . $conn->error;
            } else {
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<label style='display: flex; align-items: center; margin: 5px;'>";
                        echo "<input type='checkbox' name='id_equipamento[]' value='{$row['id_equipamento']}'>";
                        echo "{$row['tipo']} - N°: {$row['numero_patrimonio']}";
                        echo "</label>";
                    }
                } else {
                    echo "<p>Nenhum equipamento disponível para retirada.</p>";
                }
            }
            ?>
        </div>

        <div style="display: flex; justify-content: center; gap: 10px; margin-top: 20px;">
    <button type="submit">Registrar Retirada</button>
    <button type="button" onclick="window.location.href='formDevolucaoEquipamento.php';">Registrar Devolução</button>
</div>
<center>
<div class="btn-listagem">
        <a href="../Listagem/RD/listaRetirada.php" class="ret">Lista de Retiradas</a>
    </div>
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
    <footer style="text-align: center; padding: 10px; margin-top: 20px; opacity: 0.3;">
    <p>© Pedro e Antony, 2025. Todos os direitos reservados.</p>
</footer>

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
  <a href="../CentralAtendimento.php" class="help-button" id="randomLink" target="_blank">?</a>

</body>
</html>

<?php
$conn->close();
?>
