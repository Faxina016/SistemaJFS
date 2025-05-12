<?php
include '../../conect.php'; 

// Início da consulta com condição genérica
$sql = "SELECT tipo, numero_patrimonio, data_aquisicao, status FROM equipamento WHERE 1=1";

// Aplica filtros, se houverem
if (!empty($_GET['numero_patrimonio'])) {
    $numero_patrimonio = $conn->real_escape_string($_GET['numero_patrimonio']);
    $sql .= " AND numero_patrimonio LIKE '%$numero_patrimonio%'";
}

if (!empty($_GET['data_aquisicao'])) {
    $data_aquisicao = $conn->real_escape_string($_GET['data_aquisicao']);
    $sql .= " AND data_aquisicao = '$data_aquisicao'";
}

$result = $conn->query($sql);

// Não feche a conexão aqui, pois ela será usada no menu.php
// O PHP fechará a conexão automaticamente no final do script
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Equipamentos</title>
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
        <h2>Equipamentos Cadastrados</h2>
        
        <!-- Botão de filtro com ícone -->
        <button class="toggle-btn" onclick="toggleFiltro()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="vertical-align: text-bottom; margin-right: 5px;">
                <path d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z"/>
            </svg>
            Filtrar Equipamentos
        </button>

        <!-- Formulário de Filtro (inicialmente oculto) -->
        <form method="GET" action="" id="filtro-form">
            <div class="form-group">
                <label for="numero_patrimonio">Número de Patrimônio:</label>
                <input type="text" name="numero_patrimonio" id="numero_patrimonio" value="<?php echo isset($_GET['numero_patrimonio']) ? htmlspecialchars($_GET['numero_patrimonio']) : ''; ?>" placeholder="Digite o número do patrimônio">
            </div>

            <div class="form-group">
                <label for="data_aquisicao">Data de Aquisição:</label>
                <input type="date" name="data_aquisicao" id="data_aquisicao" value="<?php echo isset($_GET['data_aquisicao']) ? htmlspecialchars($_GET['data_aquisicao']) : ''; ?>">
            </div>

            <div class="form-actions">
                <input type="submit" value="Aplicar Filtros">
                <button type="button" class="btn-secondary" onclick="window.location.href='listaEquipamentos.php'">Limpar Filtros</button>
            </div>
        </form>

        <!-- Tabela de Equipamentos -->
        <table>
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Número de Patrimônio</th>
                    <th>Data de Aquisição</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['tipo']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['numero_patrimonio']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['data_aquisicao']) . "</td>";
                        
                        // Adiciona classes diferentes baseadas no status
                        $statusClass = '';
                        switch (strtolower($row['status'])) {
                            case 'disponível':
                            case 'disponivel':
                                $statusClass = 'status-disponivel';
                                break;
                            case 'em uso':
                            case 'em_uso':
                                $statusClass = 'status-em-uso';
                                break;
                            case 'manutenção':
                            case 'manutencao':
                                $statusClass = 'status-manutencao';
                                break;
                            default:
                                $statusClass = '';
                        }
                        
                        echo "<td><span class='status-badge " . $statusClass . "'>" . htmlspecialchars($row['status']) . "</span></td>";
                        
                        echo "<td class='acoes'>";
                        echo "<a href='editarEquipamentos.php?numero_patrimonio=" . urlencode($row['numero_patrimonio']) . "' class='acao-link editar'>Editar</a>";
                        echo "<a href='excluirEquipamentos.php?numero_patrimonio=" . urlencode($row['numero_patrimonio']) . "' onclick='return confirm(\"Tem certeza que deseja excluir este equipamento?\");' class='acao-link excluir'>Excluir</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='no-results'>Nenhum equipamento encontrado.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="actions-container">
            <a href="../../Formularios/formEquipamentos.php" class="btn-back">Voltar para Cadastro</a>           
            <a href="../../CentralAtendimento.php" class="help-button" id="randomLink" target="_blank">?</a>
        </div>
    </div>

    <footer>
        <p>© Pedro e Antony, 2025. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
