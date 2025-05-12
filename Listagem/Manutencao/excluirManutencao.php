<?php
include '../../conect.php'; 

$mensagem = '';
$tipo = '';

if (!isset($_GET['id_manutencao']) || empty($_GET['id_manutencao'])) {
    die("ID da manutenção não fornecido.");
}

$id_manutencao = intval($_GET['id_manutencao']);

$conn->begin_transaction();

try {
    // Primeiro, obtemos o id_equipamento da manutenção que será excluída
    $sql_equipamento = "SELECT id_equipamento FROM historico_manutencao WHERE id_manutencao = ?";
    $stmt = $conn->prepare($sql_equipamento);
    $stmt->bind_param("i", $id_manutencao);
    $stmt->execute();
    $result = $stmt->get_result();
    $manutencao = $result->fetch_assoc();
    $stmt->close();

    if (!$manutencao) {
        throw new Exception("Manutenção não encontrada.");
    }

    $id_equipamento = $manutencao['id_equipamento'];

    // Excluir a manutenção
    $sql_delete_manutencao = "DELETE FROM historico_manutencao WHERE id_manutencao = ?";
    $stmt = $conn->prepare($sql_delete_manutencao);
    $stmt->bind_param("i", $id_manutencao);
    
    if (!$stmt->execute()) {
        throw new Exception("Erro ao excluir manutenção: " . $stmt->error);
    }
    $stmt->close();

    // Atualizar o status do equipamento para "disponivel"
    $sql_update = "UPDATE equipamento SET status = 'disponivel' WHERE id_equipamento = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("i", $id_equipamento);
    
    if (!$stmt->execute()) {
        throw new Exception("Erro ao atualizar status do equipamento: " . $stmt->error);
    }
    $stmt->close();

    // Confirmar transação
    $conn->commit();

    $mensagem = "Manutenção excluída com sucesso!";
    $tipo = "success";
} catch (Exception $e) {
    $conn->rollback();
    $mensagem = "Erro: " . $e->getMessage();
    $tipo = "error";
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado da Manutenção</title>
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
            background: linear-gradient(135deg, #00aaff, #003366); /* Gradiente azul */
            overflow: auto;
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
