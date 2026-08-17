<?php
// login.php
// Autentica o usuário (login + senha) contra a tabela "usuarios".
// Se der certo, guarda o login na sessão e redireciona para consulta.php.

session_start();
require_once "conexao.php";

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($login === '' || $senha === '') {
        $mensagem = "Preencha login e senha.";
    } else {
        $sql = "SELECT senha FROM usuarios WHERE login = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $login);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {
                $linha = $resultado->fetch_assoc();
                // password_verify compara a senha digitada com o hash salvo no banco
                if (password_verify($senha, $linha['senha'])) {
                    $_SESSION['usuario_login'] = $login;
                    $stmt->close();
                    $conn->close();
                    // Login certo: redireciona para a página de consulta
                    header("Location: consulta.php");
                    exit;
                } else {
                    $mensagem = "Login ou senha inválidos.";
                }
            } else {
                $mensagem = "Login ou senha inválidos.";
            }
            $stmt->close();
        } else {
            $mensagem = "Erro na preparação da consulta: " . $conn->error;
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>

    <?php if ($mensagem !== ""): ?>
        <p style="color:red;"><?php echo htmlspecialchars($mensagem); ?></p>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="campo">
            <label for="login">Login</label>
            <input type="text" id="login" name="login" required maxlength="50">
        </div>
        <div class="campo">
            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" required>
        </div>
        <button type="submit">Entrar</button>
    </form>

    <p>Ainda não tem conta? <a href="cadastro_login.php">Cadastre-se</a></p>
    <p><a href="index.html">Voltar</a></p>
</body>
</html>
