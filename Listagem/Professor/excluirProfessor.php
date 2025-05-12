<?php
include '../../conect.php'; 

$id_professor = $_GET['id_professor'] ?? null;

if ($id_professor) {
    try {
        $sql = "DELETE FROM professor WHERE id_professor = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $id_professor);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $message = "Professor excluído com sucesso!";
                $message_type = "success";
            } else {
                $message = "Nenhum professor encontrado com esse ID.";
                $message_type = "error";
            }

            $stmt->close();
        } else {
            $message = "Erro na preparação da consulta: " . $conn->error;
            $message_type = "error";
        }
    } catch (mysqli_sql_exception $e) {
        if (strpos($e->getMessage(), 'foreign key constraint fails') !== false) {
            $message = "Este professor não pode ser excluído porque está vinculado a outras informações no sistema.";
            $message_type = "error";
        } else {
            $message = "Erro ao excluir professor: " . $e->getMessage();
            $message_type = "error";
        }
    }
} else {
    $message = "ID do professor não especificado.";
    $message_type = "error";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exclusão de Professor</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                title: "<?php echo ($message_type == 'success') ? 'Sucesso!' : 'Erro!'; ?>",
                text: "<?php echo $message; ?>",
                icon: "<?php echo $message_type; ?>",
                confirmButtonText: "OK"
            }).then(() => {
                window.location.href = 'listaProfessor.php';
            });
        });
    </script>
</head>
<body>
</body>
</html>
