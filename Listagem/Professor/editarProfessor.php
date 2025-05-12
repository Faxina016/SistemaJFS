<?php
include '../../conect.php'; 

$id_professor = $_GET['id_professor'] ?? null;

if (!$id_professor) {
    header("Location: listaProfessor.php");
    exit;
}

$sql = "SELECT nome FROM professor WHERE id_professor = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_professor);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Professor não encontrado!";
    echo "<br><a href='formProf.php'>Voltar</a>";
    exit;
}

$professor = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];

    $updateSql = "UPDATE professor SET nome = ? WHERE id_professor = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("si", $nome, $id_professor);

    if ($updateStmt->execute()) {
        echo "Professor atualizado com sucesso!";
        echo "<br><a href='listaProfessor.php'>Voltar</a>";
        exit;
    } else {
        echo "Erro ao atualizar: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Professor</title>
    <link rel="stylesheet" href="../../style.css">
</head>
<body>
    <h2>Alterar Professor</h2>
    <form method="POST" action="">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($professor['nome']); ?>" required>
        <br><br>
        <input type="submit" value="Salvar Alterações">
        <center>
        <a href="listaProfessor.php" style="display: inline-block; margin-left: 20px; background-color: #ccc; color: #000; padding: 8px 12px; text-decoration: none; border-radius: 4px;">Voltar</a>
        </center>
    </form>
    <a href="../../CentralAtendimento.php" class="help-button" id="randomLink" target="_blank">?</a>
</body>
</html>
