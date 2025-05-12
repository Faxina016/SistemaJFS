<?php
include '../../conect.php'; 

// Monta a consulta com filtro, se houver
$sql = "SELECT id_professor, nome FROM professor WHERE 1=1";

if (!empty($_GET['nome'])) {
    $nome = $conn->real_escape_string($_GET['nome']);
    $sql .= " AND nome LIKE '%$nome%'";
}

$result = $conn->query($sql);

if (!$result) {
    die("Erro na consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Professores</title>
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
        <h2>Professores Cadastrados</h2>
        
        <!-- Botão de filtro com ícone -->
        <button class="toggle-btn" onclick="toggleFiltro()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="vertical-align: text-bottom; margin-right: 5px;">
                <path d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z"/>
            </svg>
            Filtrar Professores
        </button>

        <!-- Formulário de Filtro (inicialmente oculto) -->
        <form method="GET" action="" id="filtro-form">
            <div class="form-group">
                <label for="nome">Nome do Professor:</label>
                <input type="text" name="nome" id="nome" value="<?php echo isset($_GET['nome']) ? htmlspecialchars($_GET['nome']) : ''; ?>" placeholder="Digite o nome do professor">
            </div>

            <div class="form-actions">
                <input type="submit" value="Aplicar Filtros">
                <button type="button" class="btn-secondary" onclick="window.location.href='listaProfessor.php'">Limpar Filtros</button>
            </div>
        </form>

        <!-- Tabela de Professores -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id_professor']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                        echo "<td class='acoes'>";
                        echo "<a href='editarProfessor.php?id_professor=" . $row['id_professor'] . "' class='acao-link editar'>Editar</a>";
                        echo "<a href='excluirProfessor.php?id_professor=" . $row['id_professor'] . "' onclick='return confirm(\"Deseja realmente excluir este professor?\")' class='acao-link excluir'>Excluir</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' class='no-results'>Nenhum professor cadastrado.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="actions-container">
            <div>
                <a href="../../Formularios/formProfessor.php" class="btn-back">Voltar para Cadastro</a>
            </div>
            <div>
                <a href="CadastroProfessor.php" class="btn">Cadastrar Novo Professor</a>
            </div>
        </div>
    </div>

    <a href="../../CentralAtendimento.php" class="help-button" id="randomLink" target="_blank">?</a>

    <footer>
        <p>© Pedro e Antony, 2025. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
