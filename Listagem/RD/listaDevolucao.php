<?php
include '../../conect.php';

$where = "";

if (!empty($_GET['professor'])) {
    $professor = $conn->real_escape_string($_GET['professor']);
    $where .= ($where ? " AND " : " WHERE ") . "p.nome LIKE '%$professor%'";
}

if (!empty($_GET['equipamento'])) {
    $equipamento = $conn->real_escape_string($_GET['equipamento']);
    $where .= ($where ? " AND " : " WHERE ") . "(e.tipo LIKE '%$equipamento%' OR e.numero_patrimonio LIKE '%$equipamento%')";
}

if (!empty($_GET['hora'])) {
    $hora = $conn->real_escape_string($_GET['hora']);
    $where .= ($where ? " AND " : " WHERE ") . "d.hora_devolucao LIKE '%$hora%'";
}

$sql = "SELECT 
            d.id_devolucao, 
            p.nome AS professor, 
            GROUP_CONCAT(e.tipo, ' (', e.numero_patrimonio, ') ' SEPARATOR ', ') AS equipamentos,
            d.hora_devolucao, 
            d.data_devolucao 
        FROM devolucao d
        JOIN retirada r ON d.id_retirada = r.id_retirada
        JOIN professor p ON r.id_professor = p.id_professor
        JOIN equipamento_retirado er ON r.id_retirada = er.id_retirada
        JOIN equipamento e ON er.id_equipamento = e.id_equipamento
        $where
        GROUP BY d.id_devolucao, p.nome, d.hora_devolucao, d.data_devolucao";

$result = $conn->query($sql);

if (!$result) {
    die("Erro na consulta SQL: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Devoluções</title>
    <link rel="stylesheet" href="../../style.css?v=<?php echo time(); ?>">
    <script>
        function toggleFiltro() {
            const form = document.getElementById("filtro-form");
            form.style.display = form.style.display === "none" ? "block" : "none";
        }
    </script>
</head>
<body>
    <?php include '../../menu.php'; ?>
    <br><br><br>
    <div class="container">
        <h2>Relatório de Devoluções</h2>
        
        <!-- Botão de filtro com ícone -->
        <button class="toggle-btn" onclick="toggleFiltro()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="vertical-align: text-bottom; margin-right: 5px;">
                <path d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z"/>
            </svg>
            Filtrar Devoluções
        </button>

        <!-- Formulário de Filtro (inicialmente oculto) -->
        <form method="GET" action="" id="filtro-form">
            <div class="form-group">
                <label for="professor">Professor:</label>
                <input type="text" name="professor" id="professor" value="<?php echo isset($_GET['professor']) ? htmlspecialchars($_GET['professor']) : ''; ?>" placeholder="Digite o nome do professor">
            </div>

            <div class="form-group">
                <label for="equipamento">Equipamento (tipo ou patrimônio):</label>
                <input type="text" name="equipamento" id="equipamento" value="<?php echo isset($_GET['equipamento']) ? htmlspecialchars($_GET['equipamento']) : ''; ?>" placeholder="Digite o tipo ou número de patrimônio">
            </div>

            <div class="form-group">
                <label for="hora">Hora da Devolução:</label>
                <input type="text" name="hora" id="hora" value="<?php echo isset($_GET['hora']) ? htmlspecialchars($_GET['hora']) : ''; ?>" placeholder="Formato: HH:MM:SS">
            </div>

            <div class="form-actions">
                <input type="submit" value="Aplicar Filtros">
                <button type="button" class="btn-secondary" onclick="window.location.href='listaDevolucao.php'">Limpar Filtros</button>
            </div>
        </form>

        <!-- Tabela de Devoluções -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Professor</th>
                    <th>Equipamentos</th>
                    <th>Hora</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id_devolucao']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['professor']) . "</td>";
                        
                        $equipamentos = htmlspecialchars($row['equipamentos']);
                        // Limita o tamanho do texto de equipamentos para não quebrar o layout
                        echo "<td title=\"$equipamentos\">" . (strlen($equipamentos) > 50 ? substr($equipamentos, 0, 47) . '...' : $equipamentos) . "</td>";
                        
                        echo "<td>" . htmlspecialchars($row['hora_devolucao']) . "</td>";
                        echo "<td>" . date('d/m/Y', strtotime($row['data_devolucao'])) . "</td>";
                        
                        echo "<td class='acoes'>";
                        echo "<a href='editarDevolucao.php?id_devolucao=" . urlencode($row['id_devolucao']) . "' class='acao-link editar'>Editar</a>";
                        echo "<a href='excluirDevolucao.php?id_devolucao=" . urlencode($row['id_devolucao']) . "' class='acao-link excluir' onclick='return confirm(\"Tem certeza que deseja excluir esta devolução?\");'>Excluir</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='no-results'>Nenhuma devolução encontrada</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="actions-container">
            <a href="../../Formularios/formDevolucaoEquipamento.php" class="btn-back">Voltar para Cadastro</a>
        </div>
    </div>

    <a href="../../CentralAtendimento.php" class="help-button" id="randomLink" target="_blank">?</a>

    <footer>
        <p>© Pedro e Antony, 2025. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
