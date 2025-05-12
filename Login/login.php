<?php
session_start();
require_once '../conect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['usuario']);
    $senha = trim($_POST['senha']);

    if (!empty($usuario) && !empty($senha)) {
        try {
            $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT usuario, senha FROM login WHERE usuario = :usuario";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':usuario', $usuario);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($senha === $user['senha']) {
                    $_SESSION['nome_usuario'] = $user['usuario'];
                    header('Location: bemvindo.html');
                    exit();
                } else {
                    header('Location: login.html?erro=' . urlencode('Senha incorreta!'));
                    exit();
                }
            } else {
                header('Location: login.html?erro=' . urlencode('Usuário não encontrado!'));
                exit();
            }
        } catch (PDOException $e) {
            header('Location: login.html?erro=' . urlencode('Erro na conexão com o banco!'));
            exit();
        }
    } else {
        header('Location: login.html?erro=' . urlencode('Por favor, preencha todos os campos!'));
        exit();
    }
}