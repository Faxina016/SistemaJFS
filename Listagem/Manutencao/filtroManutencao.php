<?php
include '../../conect.php';

$filtro_tipo = $_POST['filtro_tipo'] ?? '';
$filtro_data_inicio = $_POST['data_inicio'] ?? '';
$filtro_data_fim = $_POST['data_fim'] ?? '';
$filtro_numero_patrimonio = $_POST['numero_patrimonio'] ?? '';

$tabela_visivel = false;

$sql = "SELECT equipamento.id_equipamento, equipamento.tipo, equipamento.numero_patrimonio, 
               historico_manutencao.descricao, historico_manutencao.data_inicio, historico_manutencao.data_fim, 
               equipamento.status 
        FROM historico_manutencao 
        INNER JOIN equipamento ON historico_manutencao.id_equipamento = equipamento.id_equipamento 
        WHERE 1 = 1";

if ($filtro_tipo == 'data') {
    if (!empty($filtro_data_inicio) && !empty($filtro_data_fim)) {
        $sql .= " AND historico_manutencao.data_inicio BETWEEN '$filtro_data_inicio' AND '$filtro_data_fim'";
    } elseif (!empty($filtro_data_inicio)) {
        $sql .= " AND historico_manutencao.data_inicio >= '$filtro_data_inicio'";
    } elseif (!empty($filtro_data_fim)) {
        $sql .= " AND historico_manutencao.data_fim <= '$filtro_data_fim'";
    }
    $tabela_visivel = true;
} elseif ($filtro_tipo == 'patrimonio' && !empty($filtro_numero_patrimonio)) {
    $sql .= " AND equipamento.numero_patrimonio LIKE '%$filtro_numero_patrimonio%'";
    $tabela_visivel = true;
}

$sql .= " ORDER BY historico_manutencao.data_inicio DESC";

$result = $tabela_visivel ? $conn->query($sql) : null;

if ($result && !$result) {
    die("Erro na consulta SQL: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Manutenção</title>
    <link rel="stylesheet" href="../RD/listaRetirada.css?v=<?php echo time(); ?>">
</head>
<body>
<?php include '../../menu.php'; ?>
<br><br><br>


<form method="POST" action="" style="margin-bottom: 20px;">
<center>
<h2>Histórico de Manutenções</h2>
</center>
    <label for="filtro_tipo">Filtrar por:</label>
    <select name="filtro_tipo" id="filtro_tipo">
        <option value="">Selecione</option>
        <option value="data" <?php echo ($filtro_tipo == 'data') ? 'selected' : ''; ?>>Data</option>
        <option value="patrimonio" <?php echo ($filtro_tipo == 'patrimonio') ? 'selected' : ''; ?>>Número de Patrimônio</option>
    </select>

    <div id="filtro_data" style="display: none;">
        <label for="data_inicio">Data Início:</label>
        <input type="date" name="data_inicio" value="<?php echo $filtro_data_inicio; ?>">

        <label for="data_fim">Data Fim:</label>
        <input type="date" name="data_fim" value="<?php echo $filtro_data_fim; ?>">
    </div>

    <div id="filtro_patrimonio" style="display: none;">
        <label for="numero_patrimonio">Número Patrimônio:</label>
        <input type="text" name="numero_patrimonio" placeholder="Digite o número" value="<?php echo $filtro_numero_patrimonio; ?>">
    </div>

    <center>
        <button type="submit">Filtrar</button>
    </center>
<center>
    <div>
        <a href="listaManutencao.php" class="equip">Lista De Manutenções</a>
    </div>
</center>
</form>

<?php if ($tabela_visivel && $result->num_rows > 0): ?>
    <table>
        <tr>
            <th>ID Equipamento</th>
            <th>Tipo</th>
            <th>Número Patrimônio</th>
            <th>Descrição</th>
            <th>Data Início</th>
            <th>Data Final</th>
            <th>Status</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id_equipamento']; ?></td>
                <td><?php echo $row['tipo']; ?></td>
                <td><?php echo $row['numero_patrimonio']; ?></td>
                <td><?php echo $row['descricao']; ?></td>
                <td><?php echo $row['data_inicio']; ?></td>
                <td><?php echo $row['data_fim']; ?></td>
                <td><?php echo $row['status']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php elseif ($tabela_visivel): ?>
    <p style="color: red; text-align: center;">Nenhuma manutenção encontrada</p>
<?php endif; ?>

<a href="../../CentralAtendimento.php" class="help-button" id="randomLink" target="_blank">?</a>

<script>
    document.getElementById('filtro_tipo').addEventListener('change', function () {
        let filtroData = document.getElementById('filtro_data');
        let filtroPatrimonio = document.getElementById('filtro_patrimonio');

        filtroData.style.display = 'none';
        filtroPatrimonio.style.display = 'none';

        if (this.value === 'data') {
            filtroData.style.display = 'block';
        } else if (this.value === 'patrimonio') {
            filtroPatrimonio.style.display = 'block';
        }
    });

    window.onload = function () {
        document.getElementById('filtro_tipo').dispatchEvent(new Event('change'));
    };
</script>

<footer style="text-align: center; padding: 10px; margin-top: 20px; opacity: 0.3;">
    <p>© Pedro e Antony, 2025. Todos os direitos reservados.</p>
</footer>
</body>
</html>
