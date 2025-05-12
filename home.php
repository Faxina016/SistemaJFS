<?php
session_start();

if (!isset($_SESSION['nome_usuario'])) {
    header('Location: Login/login.html');
    exit();
}
$nome_usuario = $_SESSION['nome_usuario'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Início</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">

</head>
<body>
<h1 style="text-align: center; font-size: 3rem; margin-top: 100px;">
    Olá, <?php echo htmlspecialchars($nome_usuario); ?>!
</h1><?php include 'menu.php'; ?>
<a href="CentralAtendimento.php" class="help-button" id="randomLink">?</a>

<footer style="text-align: center; padding: 10px; margin-top: 20px; opacity: 0.3;">
    <p id="phdev">© Pedro e Antony, 2025. Todos os direitos reservados.</p>
</footer>

<script>
// Script para contar cliques no Pedro e Antony e mostrar easter egg
document.addEventListener('DOMContentLoaded', function() {
    const phdevTag = document.getElementById('phdev');
    let clickCount = localStorage.getItem('phdevClickCount') || 0;
    
    phdevTag.addEventListener('click', function() {
        clickCount++;
        localStorage.setItem('phdevClickCount', clickCount);
        
        if (clickCount >= 50) {
            // Reseta o contador
            localStorage.setItem('phdevClickCount', 0);
            // Redireciona para a página do easter egg
            window.location.href = 'easter_egg.php';
        }
    });
});
</script>
</body>
</html>