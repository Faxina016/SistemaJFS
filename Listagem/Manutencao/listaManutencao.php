<?php
include '../../conect.php'; 

$sql = "SELECT 
            hm.id_manutencao, 
            e.numero_patrimonio, 
            hm.descricao, 
            hm.data_inicio, 
            hm.data_fim, 
            e.status 
        FROM historico_manutencao hm
        JOIN equipamento e ON hm.id_equipamento = e.id_equipamento";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Manutenções</title>
    <link rel="stylesheet" href="../../style.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include '../../menu.php'; ?>
    <br><br><br>
    <div class="container">
        <h2>Manutenções Cadastradas</h2>
        
        <!-- Tabela de Manutenções -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Número de Patrimônio</th>
                    <th>Descrição</th>
                    <th>Data Inicio</th>
                    <th>Data Fim</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . (isset($row['id_manutencao']) ? htmlspecialchars($row['id_manutencao']) : 'N/A') . "</td>";
                        echo "<td>" . (isset($row['numero_patrimonio']) ? htmlspecialchars($row['numero_patrimonio']) : 'N/A') . "</td>";
                        
                        $descricao = isset($row['descricao']) ? htmlspecialchars($row['descricao']) : 'N/A';
                        // Limita o tamanho da descrição para não quebrar o layout
                        echo "<td title=\"$descricao\">" . (strlen($descricao) > 50 ? substr($descricao, 0, 47) . '...' : $descricao) . "</td>";
                        
                        echo "<td>" . (isset($row['data_inicio']) ? date('d/m/Y', strtotime($row['data_inicio'])) : 'N/A') . "</td>";
                        echo "<td>" . (isset($row['data_fim']) ? date('d/m/Y', strtotime($row['data_fim'])) : 'N/A') . "</td>";
                        
                        // Adiciona classes diferentes baseadas no status
                        $statusClass = '';
                        $status = isset($row['status']) ? strtolower($row['status']) : '';
                        
                        switch ($status) {
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
                        
                        echo "<td><span class='status-badge " . $statusClass . "'>" . (isset($row['status']) ? htmlspecialchars($row['status']) : 'N/A') . "</span></td>";
                        
                        echo "<td class='acoes'>";
                        echo "<a href='editarManutencao.php?id_manutencao=" . urlencode($row['id_manutencao']) . "' class='acao-link editar'>Editar</a>";
                        echo "<a href='excluirManutencao.php?id_manutencao=" . urlencode($row['id_manutencao']) . "' onclick='return confirm(\"Tem certeza que deseja excluir esta manutenção?\");' class='acao-link excluir'>Excluir</a>";
                        echo "</td>";
                        echo "</tr>";
                    }               
                } else {
                    echo "<tr><td colspan='7' class='no-results'>Nenhuma manutenção cadastrada.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="actions-container">
            <a href="filtroManutencao.php" class="btn-back">Voltar para Filtros</a>
            <a href="../../Formularios/formManutencao.php" class="btn">Cadastrar Nova Manutenção</a>
        </div>
    </div>
    
    <a href="../../CentralAtendimento.php" class="help-button" id="randomLink" target="_blank">?</a>

    <footer>
        <p>© Pedro e Antony, 2025. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
