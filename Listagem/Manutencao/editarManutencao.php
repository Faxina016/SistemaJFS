<?php
include '../../conect.php';

// Buscar todos os equipamentos em manutenção para o select
$sql_equipamentos = "SELECT id_equipamento, numero_patrimonio FROM equipamento WHERE status = 'em_manutencao'";
$result_equipamentos = $conn->query($sql_equipamentos);
$equipamentos = [];
while ($row = $result_equipamentos->fetch_assoc()) {
    $equipamentos[] = $row;
}

// Inicializar variáveis de mensagem
$mensagem = '';
$tipo = '';

// Verificar se foi enviado o formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_manutencao = intval($_POST['id_manutencao']);
    $id_equipamento = intval($_POST['id_equipamento']);
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];
    $descricao = $_POST['descricao'];

    // Atualizar dados da manutenção no banco
    $sql_update = "UPDATE historico_manutencao SET id_equipamento = ?, data_inicio = ?, data_fim = ?, descricao = ? WHERE id_manutencao = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("isssi", $id_equipamento, $data_inicio, $data_fim, $descricao, $id_manutencao);

    if ($stmt->execute()) {
        // Se a atualização foi bem-sucedida
        $mensagem = "Manutenção atualizada com sucesso!";
        $tipo = "success";
    } else {
        // Se houve erro na atualização
        $mensagem = "Erro ao atualizar manutenção.";
        $tipo = "error";
    }
    $stmt->close();
}

// Verificar se foi passado um id de manutenção
if (!isset($_GET['id_manutencao']) || empty($_GET['id_manutencao'])) {
    die("ID da manutenção não fornecido.");
}

$id_manutencao = intval($_GET['id_manutencao']);
$sql = "SELECT * FROM historico_manutencao WHERE id_manutencao = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_manutencao);
$stmt->execute();
$result = $stmt->get_result();
$manutencao = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Manutenção</title>
    <link rel="stylesheet" href="../../style.css?v=<?php echo time(); ?>">
    <style>
        /* Estilo para o popup/modal */
        .modal {
            display: none; /* Inicialmente escondido */
            position: fixed;
            z-index: 1; /* Coloca o modal acima de outros elementos */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4); /* Fundo semitransparente */
            padding-top: 60px;
        }

        /* Modal Content */
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Largura do modal */
            text-align: center;
            border-radius: 10px;
        }

        /* Estilo para sucesso */
        .success {
            background-color: #4CAF50;
            color: white;
            font-size: 18px;
        }

        /* Estilo para erro */
        .error {
            background-color: #f44336;
            color: white;
            font-size: 18px;
        }

        /* Adiciona animação de fade-out */
        .fade-out {
            opacity: 0;
            transition: opacity 0.5s ease-out;
        }
    </style>
    <script>
        window.onload = function() {
            // Exibir o modal após o carregamento
            var modal = document.getElementById("myModal");
            modal.style.display = "block";
            
            // Fechar o modal automaticamente após 2,5 segundos
            setTimeout(function() {
                modal.classList.add('fade-out');
                setTimeout(function() {
                    modal.style.display = "none";
                    // Redireciona para a página formManutencao.php após a animação
                    window.location.href = 'listaManutencao.php';
                }, 500); // Tempo do fade-out (0,5 segundos)
            }, 2500); // Tempo para a mensagem desaparecer (2,5 segundos)
        };
    </script>
</head>
<body>

<div class="container">
    <h2>Editar Manutenção</h2>
    <form method="POST" action="">
        <input type="hidden" name="id_manutencao" value="<?php echo $id_manutencao; ?>">
        
        <label>Número de Patrimônio:</label>
        <select name="id_equipamento" required>
            <option value="">Selecione um equipamento</option>
            <?php foreach ($equipamentos as $equipamento) { ?>
                <option value="<?php echo $equipamento['id_equipamento']; ?>"
                    <?php echo ($equipamento['id_equipamento'] == $manutencao['id_equipamento']) ? 'selected' : ''; ?>>
                    <?php echo $equipamento['numero_patrimonio']; ?>
                </option>
            <?php } ?>
        </select>

        <label>Data Início:</label>
        <input type="date" name="data_inicio" value="<?php echo htmlspecialchars($manutencao['data_inicio']); ?>" required>

        <label>Data Fim:</label>
        <input type="date" name="data_fim" value="<?php echo htmlspecialchars($manutencao['data_fim']); ?>">

        <label>Descrição:</label>
        <textarea name="descricao" required><?php echo htmlspecialchars($manutencao['descricao']); ?></textarea>

        <center>
        <button type="submit">Salvar</button>
        <a href="listaManutencao.php" class="btn btn-back">Voltar</a>
        </center>
    </form>
</div>

<!-- Modal -->
<?php if ($mensagem): ?>
    <div id="myModal" class="modal">
        <div class="modal-content <?php echo $tipo; ?>">
            <?php echo $mensagem; ?>
        </div>
    </div>
<?php endif; ?>

</body>
</html>
