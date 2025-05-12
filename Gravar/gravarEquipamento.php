<?php
include '../conect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tipo = $_POST['tipo'];
    $numero_patrimonio = $_POST['numero_patrimonio'];
    $data_aquisicao = $_POST['data_aquisicao'];

    $sql = "INSERT INTO equipamento (tipo, numero_patrimonio, data_aquisicao) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    $stmt->bind_param("sss", $tipo, $numero_patrimonio, $data_aquisicao);

    if ($stmt->execute()) {
        $mensagem = "Cadastro realizado com sucesso!";
        $cor_mensagem = "#4CAF50"; // Verde
    } else {
        $mensagem = "Erro ao cadastrar: " . $stmt->error;
        $cor_mensagem = "#f44336"; // Vermelho
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Equipamento</title>
    <link rel="stylesheet" href="stylelogin.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .mensagem {
            background-color: #F5F5F5;
            color: <?php echo isset($cor_mensagem) ? $cor_mensagem : '#000'; ?>;
            padding: 20px 40px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            font-size: 1.5rem;
            text-align: center;
            margin-bottom: 20px;
        }

        .btn {
            padding: 15px 30px;
            font-size: 1rem;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: inline-block;
            text-align: center;
            position: absolute;
            bottom: 20px;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <!-- Exibe a mensagem de sucesso ou erro -->
    <div class="mensagem">
        <?php echo isset($mensagem) ? $mensagem : ''; ?>
    </div>

    <!-- Botão de voltar -->
    <a href="../Formularios/formEquipamentos.php" class="btn">Voltar</a>
</body>
</html>
