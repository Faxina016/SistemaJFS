<?php
include '../../conect.php';

if (isset($_GET['numero_patrimonio'])) {
    $numero_patrimonio = $_GET['numero_patrimonio'];

    try {
        $sql = "DELETE FROM equipamento WHERE numero_patrimonio = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $numero_patrimonio);
        $stmt->execute();

        echo "<script>
                alert('Equipamento excluído com sucesso.');
                window.location.href = 'listaEquipamentos.php';
              </script>";
    } catch (mysqli_sql_exception $e) {
        if (strpos($e->getMessage(), 'foreign key constraint fails') !== false) {
            echo "<script>
                    alert('Este equipamento não pode ser excluído porque está vinculado a um histórico de manutenção.');
                    window.location.href = 'listaEquipamentos.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Erro ao excluir equipamento: " . addslashes($e->getMessage()) . "');
                    window.location.href = 'listaEquipamentos.php';
                  </script>";
        }
    }

    $stmt->close();
} else {
    echo "<script>
            alert('Nenhum equipamento selecionado para exclusão.');
            window.location.href = 'listaEquipamentos.php';
          </script>";
}

$conn->close();
?>
