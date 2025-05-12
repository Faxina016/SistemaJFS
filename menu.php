<?php
// Verificar se ROOT_DIR já está definido antes de incluir config.php
if (!defined('ROOT_DIR')) {
    include('config.php');
}

// Garantir que temos uma conexão com o banco
if (!isset($conn) || !$conn) {
    require_once __DIR__ . '/conect.php';
}
?>

<head>
    <link rel="stylesheet" href="<?php echo ROOT_DIR; ?>/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <header>
        <nav class="nav-bar">
            <div class="logo">
                <h1>
                    <a href="<?php echo ROOT_DIR; ?>/home.php">
                    <img src="<?php echo ROOT_DIR; ?>/img/logo.png" width="50" height="40" alt="Logo" class="logo-img">
                    </a>
                </h1>
            </div>
            <div class="nav-list">
                <ul>
                    <li class="nav-item"><a href="<?php echo ROOT_DIR; ?>/Formularios/formEquipamentos.php" class="nav-link">Equipamentos</a></li>
                    <li class="nav-item"><a href="<?php echo ROOT_DIR; ?>/Formularios/formProfessor.php" class="nav-link">Professores</a></li>
                    <li class="nav-item"><a href="<?php echo ROOT_DIR; ?>/Formularios/formRetiradaEquipamento.php" class="nav-link">Retirada/Devolução</a></li>
                    <li class="nav-item"><a href="<?php echo ROOT_DIR; ?>/Formularios/formReservas.php" class="nav-link">Reservas</a></li>
                    <li class="nav-item"><a href="<?php echo ROOT_DIR; ?>/Listagem/Manutencao/filtroManutencao.php" class="nav-link">Histórico Manutenção</a></li>
                </ul>
            </div>
            <div class="suporte">
                <a href="https://assist.positivotecnologia.com.br/index.php?url=" class="suporte-link" target="blank">Suporte Positivo</a>
            </div>
            <div class="logout">
                <a href="<?php echo ROOT_DIR; ?>/Login/logout.php">
                    <button class="logout-btn">Sair</button>
                </a>
            </div>
            <div class="mobile-menu-icon">
                <button onclick="menuShow()">
                    <img class="icon" src="<?php echo ROOT_DIR; ?>/img/menu_white_36dp.svg" alt="Menu">
                </button>
            </div>
        </nav>
        <div class="mobile-menu"></div>
    </header>
</body>
