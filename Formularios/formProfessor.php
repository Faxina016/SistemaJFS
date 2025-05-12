<?php
include '../conect.php'; 
include '../menu.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];

    $sql_check = "SELECT COUNT(*) FROM professor WHERE nome = ?";
    $stmt_check = $conn->prepare($sql_check);

    if ($stmt_check === false) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    $stmt_check->bind_param("s", $nome);
    $stmt_check->execute();
    $stmt_check->bind_result($count);
    $stmt_check->fetch();
    $stmt_check->close();

    if ($count > 0) {
        $mensagem = "Professor já cadastrado!";
        $cor_mensagem = "#f44336"; // Vermelho
    } else {
        $sql = "INSERT INTO professor (nome) VALUES (?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Erro na preparação da consulta: " . $conn->error);
        }

        $stmt->bind_param("s", $nome);

        if ($stmt->execute()) {
            $mensagem = "Professor cadastrado com sucesso!";
            $cor_mensagem = "#4CAF50"; // Verde
        } else {
            $mensagem = "Erro ao cadastrar: " . $stmt->error;
            $cor_mensagem = "#f44336"; // Vermelho
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Professor</title>
    <link rel="stylesheet" href="formProfessor.css?v=<?php echo time(); ?>">
</head>
<body><br><br>
<div class="container">
    <center>
    <h2>Cadastro de Professor</h2>
    </center>

    <?php if (isset($mensagem)) { ?>
        <div class="mensagem" id="mensagem" style="color: <?php echo $cor_mensagem; ?>;">
            <?php echo $mensagem; ?>
        </div>
    <?php } ?>

    <form method="POST" action="">
        <label for="nome">Nome do Professor:</label>
        <input type="text" id="nome" name="nome" required>
        <center>
        <input type="submit" value="Cadastrar">
        </center>
    </form>

    <div class="btn-listagem">
        <a href="../Listagem/Professor/listaProfessor.php">Professores Cadastrados</a>
    </div>
</div>

<footer style="text-align: center; padding: 10px; margin-top: 20px; opacity: 0.3;">
    <p>© Pedro e Antony, 2025. Todos os direitos reservados.</p>
</footer>
<a href="../CentralAtendimento.php" class="help-button" id="randomLink" target="_blank">?</a>

<script>
  setTimeout(function() {
    var mensagem = document.getElementById("mensagem");
    if (mensagem) {
      mensagem.style.display = "none";
    }
  }, 5000);
</script>

</body>
</html>
