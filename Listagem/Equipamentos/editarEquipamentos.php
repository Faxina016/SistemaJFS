<?php
include '../../conect.php';

$equipamento = null; // Inicializa a variável para evitar warnings

if (isset($_GET['numero_patrimonio'])) {
    $numero_patrimonio = $_GET['numero_patrimonio'];

    $sql = "SELECT * FROM equipamento WHERE numero_patrimonio = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $numero_patrimonio);
        $stmt->execute();
        $result = $stmt->get_result();
        $equipamento = $result->fetch_assoc();
        $stmt->close();
    } else {
        die("Erro na preparação da consulta: " . $conn->error);
    }
}

// Verifica se foi enviado um formulário POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo = $_POST['tipo'] ?? '';
    $numero_patrimonio = $_POST['numero_patrimonio'] ?? '';
    $data_aquisicao = $_POST['data_aquisicao'] ?? '';
    $status = $_POST['status'] ?? '';

    // Valida se os campos obrigatórios foram preenchidos
    if (empty($tipo) || empty($numero_patrimonio) || empty($data_aquisicao) || empty($status)) {
        die("Erro: Todos os campos são obrigatórios.");
    }

    // Atualiza os dados no banco
    $sql = "UPDATE equipamento SET tipo = ?, data_aquisicao = ?, status = ? WHERE numero_patrimonio = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssss", $tipo, $data_aquisicao, $status, $numero_patrimonio);

        if ($stmt->execute()) {
            echo "<script>alert('Equipamento atualizado com sucesso!'); window.location.href='listaEquipamentos.php';</script>";
            exit;
        } else {
            die("Erro ao atualizar equipamento: " . $stmt->error);
        }
        $stmt->close();
    } else {
        die("Erro na preparação da consulta: " . $conn->error);
    }
}

// Removido: $conn->close();
?>

<?php include '../../menu.php'; ?>

<link rel="stylesheet" href="../../style.css?v=<?php echo time(); ?>">
<br><br><br>    
<center>
    <h2> Alterar Dados: </h2>
</center>

<form method="POST">
    <label>Tipo:</label>
    <input type="text" name="tipo" value="<?php echo htmlspecialchars($equipamento['tipo'] ?? ''); ?>" required>

    <label>Número de Patrimônio:</label>
    <input type="text" name="numero_patrimonio" value="<?php echo htmlspecialchars($equipamento['numero_patrimonio'] ?? ''); ?>" readonly>

    <label>Data de Aquisição:</label>
    <input type="date" name="data_aquisicao" value="<?php echo htmlspecialchars($equipamento['data_aquisicao'] ?? ''); ?>" required>

    <label>Status:</label>
    <select name="status" required>
        <option value="disponivel" <?= (isset($equipamento['status']) && $equipamento['status'] == 'disponivel') ? 'selected' : ''; ?>>Disponível</option>
        <option value="em_manutencao" <?= (isset($equipamento['status']) && $equipamento['status'] == 'em_manutencao') ? 'selected' : ''; ?>>Em Manutenção</option>
        <option value="indisponivel" <?= (isset($equipamento['status']) && $equipamento['status'] == 'indisponivel') ? 'selected' : ''; ?>>Indisponível</option>
    </select>
<center>
    <input type="submit" value="Salvar">
</center>
    <center>
    <a href="listaEquipamentos.php" class="btn btn-back">Voltar</a>
</center>
</form>

<script>
document.querySelector("form").addEventListener("submit", function(event) {
    let tipo = document.querySelector("input[name='tipo']").value.trim();
    let data_aquisicao = document.querySelector("input[name='data_aquisicao']").value.trim();
    let status = document.querySelector("select[name='status']").value.trim();

    if (!tipo || !data_aquisicao || !status) {
        alert("Por favor, preencha todos os campos.");
        event.preventDefault();
    }
});
</script>