<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Equipamentos</title>
    <link rel="stylesheet" href="../style.css?v=<?php echo time(); ?>">

 
</head>
<body class="container">
<?php 
include '../conect.php'; 
include '../menu.php'; 
?>
<br><br><br><br><br>
<center>
<div class="content">
    <form action="../Gravar/gravarEquipamento.php" method="POST" class="form-container">
        <h2>Cadastro de Equipamentos</h2>
        <label for="tipo">Tipo:</label>
    <select id="tipo" name="tipo" required>
        <option value="">Selecione um tipo</option>
        <option value="notebook">Notebook</option>
        <option value="tablet">Tablet</option>
    </select>

        <label for="numero_patrimonio">Número Patrimonio:</label>
        <input type="text" id="numero_patrimonio" name="numero_patrimonio" required>

        <label for="data_aquisicao">Data de Aquisição:</label>
        <input type="date" id="data_aquisicao" name="data_aquisicao" required>

        <input type="submit" value="Cadastrar"> <br>
        <a href="../Listagem/Equipamentos/listaEquipamentos.php" class="equip">Equipamentos Cadastrados</a>
    </form>
   
</div>
</center>

    </form>
    <footer style="text-align: center; padding: 10px; margin-top: 20px; opacity: 0.3;">
    <p>© Pedro e Antony, 2025. Todos os direitos reservados.</p>
</footer>
<a href="../CentralAtendimento.php" class="help-button" id="randomLink" target="_blank">?</a>

</body>
</html>
