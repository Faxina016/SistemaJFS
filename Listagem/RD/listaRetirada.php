<?php
include '../../conect.php';

$where = "WHERE d.id_devolucao IS NULL";

if (!empty($_GET['professor'])) {
    $professor = $conn->real_escape_string($_GET['professor']);
    $where .= " AND p.nome LIKE '%$professor%'";
}

if (!empty($_GET['equipamento'])) {
    $equipamento = $conn->real_escape_string($_GET['equipamento']);
    $where .= " AND (e.tipo LIKE '%$equipamento%' OR e.numero_patrimonio LIKE '%$equipamento%')";
}

if (!empty($_GET['hora'])) {
    $hora = $conn->real_escape_string($_GET['hora']);
    $where .= " AND r.hora_retirada LIKE '%$hora%'";
}

$sql = "SELECT 
            r.id_retirada, 
            p.nome AS professor, 
            GROUP_CONCAT(e.tipo, ' (', e.numero_patrimonio, ') ' SEPARATOR ', ') AS equipamentos,
            r.hora_retirada, 
            r.data_retirada 
        FROM retirada r
        JOIN professor p ON r.id_professor = p.id_professor
        JOIN equipamento_retirado er ON r.id_retirada = er.id_retirada
        JOIN equipamento e ON er.id_equipamento = e.id_equipamento
        LEFT JOIN devolucao d ON r.id_retirada = d.id_retirada
        $where
        GROUP BY r.id_retirada, p.nome, r.hora_retirada, r.data_retirada";

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
    <title>Relatório de Retiradas</title>
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
        <h2>Relatório de Retiradas</h2>
        
        <!-- Botão de filtro com ícone -->
        <button class="toggle-btn" onclick="toggleFiltro()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="vertical-align: text-bottom; margin-right: 5px;">
                <path d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z"/>
            </svg>
            Filtrar Retiradas
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
                <label for="hora">Hora da Retirada:</label>
                <input type="text" name="hora" id="hora" value="<?php echo isset($_GET['hora']) ? htmlspecialchars($_GET['hora']) : ''; ?>" placeholder="Formato: HH:MM:SS">
            </div>

            <div class="form-actions">
                <input type="submit" value="Aplicar Filtros">
                <button type="button" class="btn-secondary" onclick="window.location.href='listaRetirada.php'">Limpar Filtros</button>
            </div>
        </form>

        <!-- Tabela de Retiradas -->
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
                        echo "<td>" . htmlspecialchars($row['id_retirada']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['professor']) . "</td>";
                        
                        $equipamentos = htmlspecialchars($row['equipamentos']);
                        // Limita o tamanho do texto de equipamentos para não quebrar o layout
                        echo "<td title=\"$equipamentos\">" . (strlen($equipamentos) > 50 ? substr($equipamentos, 0, 47) . '...' : $equipamentos) . "</td>";
                        
                        echo "<td>" . htmlspecialchars($row['hora_retirada']) . "</td>";
                        echo "<td>" . date('d/m/Y', strtotime($row['data_retirada'])) . "</td>";
                        
                        echo "<td class='acoes'>";
                        echo "<a href='editarRetirada.php?id_retirada=" . urlencode($row['id_retirada']) . "' class='acao-link editar'>Editar</a>";
                        echo "<a href='excluirRetirada.php?id_retirada=" . urlencode($row['id_retirada']) . "' class='acao-link excluir' onclick='return confirm(\"Tem certeza que deseja excluir esta retirada?\");'>Excluir</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='no-results'>Nenhuma retirada encontrada</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="actions-container">
            <a href="../../Formularios/formRetiradaEquipamento.php" class="btn-back">Voltar para Cadastro</a>
        </div>
    </div>

    <a href="../../CentralAtendimento.php" class="help-button" id="randomLink" target="_blank">?</a>

    <footer>
        <p>© Pedro e Antony, 2025. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
