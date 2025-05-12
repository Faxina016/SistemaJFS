<?php
include '../../conect.php';

if (isset($_GET['id_retirada'])) {
    $id_retirada = $_GET['id_retirada'];

    // Dados da retirada
    $sql = "SELECT r.id_retirada, r.data_retirada, r.hora_retirada, p.id_professor, p.nome 
            FROM retirada r 
            JOIN professor p ON r.id_professor = p.id_professor 
            WHERE r.id_retirada = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_retirada);
    $stmt->execute();
    $result = $stmt->get_result();
    $retirada = $result->fetch_assoc();
    $stmt->close();

    // Equipamentos utilizados
    $equipamentosUsados = [];
    $sql = "SELECT e.id_equipamento, e.tipo, e.numero_patrimonio 
            FROM equipamento e 
            JOIN equipamento_retirado er ON e.id_equipamento = er.id_equipamento 
            WHERE er.id_retirada = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_retirada);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $equipamentosUsados[] = $row;
    }
    $stmt->close();
}

// Atualizar dados da retirada
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data_retirada = $_POST['data_retirada'];
    $hora_retirada = $_POST['hora_retirada'];
    $id_professor = $_POST['id_professor'];
    $equipamentosSelecionados = $_POST['equipamentos'] ?? [];

    $conn->begin_transaction();

    try {
        // Atualizar retirada
        $sql = "UPDATE retirada SET data_retirada = ?, hora_retirada = ?, id_professor = ? WHERE id_retirada = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssii", $data_retirada, $hora_retirada, $id_professor, $id_retirada);
        $stmt->execute();
        $stmt->close();

        // Remover equipamentos antigos
        $sql = "SELECT id_equipamento FROM equipamento_retirado WHERE id_retirada = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_retirada);
        $stmt->execute();
        $result = $stmt->get_result();
        $equipamentosAntigos = [];
        while ($row = $result->fetch_assoc()) {
            $equipamentosAntigos[] = $row['id_equipamento'];
        }
        $stmt->close();

        // Remover da tabela de equipamentos retirados e atualizar o status para "Disponível"
        $sql = "DELETE FROM equipamento_retirado WHERE id_retirada = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_retirada);
        $stmt->execute();
        $stmt->close();

        // Atualizar o status dos equipamentos removidos para "Disponível"
        foreach ($equipamentosAntigos as $equipId) {
            $sql = "UPDATE equipamento SET status = 'Disponível' WHERE id_equipamento = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $equipId);
            $stmt->execute();
            $stmt->close();
        }

        // Inserir os novos equipamentos retirados e atualizar o status para "Em_uso"
        $stmt = $conn->prepare("INSERT INTO equipamento_retirado (id_retirada, id_equipamento) VALUES (?, ?)");
        foreach ($equipamentosSelecionados as $idEquipamento) {
            $stmt->bind_param("ii", $id_retirada, $idEquipamento);
            $stmt->execute();

            // Alterar o status do equipamento para "Em_uso"
            $sql = "UPDATE equipamento SET status = 'Em_uso' WHERE id_equipamento = ?";
            $stmtStatus = $conn->prepare($sql);
            $stmtStatus->bind_param("i", $idEquipamento);
            $stmtStatus->execute();
            $stmtStatus->close();
        }
        $stmt->close();

        $conn->commit();
        header("Location: listaRetirada.php");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        echo "Erro ao atualizar retirada: " . $e->getMessage();
    }
}
?>

<?php include '../../menu.php'; ?>
<link rel="stylesheet" href="../../style.css?v=<?php echo time(); ?>">
<br><br><br>
<center><h2>Alterar Retirada</h2></center>
<br>

<form method="POST">
    <label>Data da Retirada:</label>
    <input type="date" name="data_retirada" value="<?php echo htmlspecialchars($retirada['data_retirada']); ?>" required>

    <label>Hora da Retirada:</label>
    <input type="time" name="hora_retirada" value="<?php echo htmlspecialchars($retirada['hora_retirada']); ?>" required>

    <label>Professor:</label>
    <select name="id_professor" required>
        <?php
        $conn = new mysqli($servername, $username, $password, $dbname);
        $prof_query = "SELECT id_professor, nome FROM professor";
        $prof_result = $conn->query($prof_query);
        while ($prof = $prof_result->fetch_assoc()) {
            $selected = ($prof['id_professor'] == $retirada['id_professor']) ? 'selected' : '';
            echo "<option value='{$prof['id_professor']}' $selected>{$prof['nome']}</option>";
        }
        ?>
    </select>

    <label>Equipamentos Utilizados:</label><br>
    <?php
    // Mostrar todos os equipamentos com os já selecionados marcados
    $equipamentosQuery = "SELECT id_equipamento, tipo, numero_patrimonio FROM equipamento";
    $equipamentos = $conn->query($equipamentosQuery);
    $equipamentosSelecionadosIds = array_column($equipamentosUsados, 'id_equipamento');

    while ($equip = $equipamentos->fetch_assoc()) {
        $checked = in_array($equip['id_equipamento'], $equipamentosSelecionadosIds) ? 'checked' : '';
        echo "<input type='checkbox' name='equipamentos[]' value='{$equip['id_equipamento']}' $checked>
              {$equip['tipo']} ({$equip['numero_patrimonio']})<br>";
    }
    ?>
    
    <br><input type="submit" value="Salvar">
    <center><a href="listaRetirada.php" class="btn btn-back">Voltar</a></center>
    <a href="../../CentralAtendimento.php" class="help-button" id="randomLink" target="_blank">?</a>
</form>
