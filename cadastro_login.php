<?php
// cadastro_login.php
// Página de cadastro de CONTA (login e senha) — diferente de index.html,
// que cadastra CONTATOS (nome, endereço, etc.). Aqui criamos o usuário que
// vai poder entrar em login.php.

require_once "conexao.php";

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';

    if ($login === '' || $senha === '') {
        $mensagem = "Preencha login e senha.";
    } elseif ($senha !== $confirmar_senha) {
        $mensagem = "As senhas não coincidem.";
    } else {
        // Nunca guardamos a senha em texto puro: password_hash gera um hash seguro
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (login, senha) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ss", $login, $senha_hash);
            if ($stmt->execute()) {
                echo "Conta criada com sucesso! <a href='login.php'>Fazer login</a>";
                $stmt->close();
                $conn->close();
                exit;
            } else {
                // errno 1062 = login duplicado (violação da restrição UNIQUE)
                if ($conn->errno === 1062) {
                    $mensagem = "Esse login já está em uso. Escolha outro.";
                } else {
                    $mensagem = "Erro ao criar conta: " . $stmt->error;
                }
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
    <title>Cadastro de Conta</title>
</head>
<body>
    <h2>Criar Conta</h2>

    <?php if ($mensagem !== ""): ?>
        <p style="color:red;"><?php echo htmlspecialchars($mensagem); ?></p>
    <?php endif; ?>

    <form action="cadastro_login.php" method="POST">
        <div class="campo">
            <label for="login">Login</label>
            <input type="text" id="login" name="login" required maxlength="50">
        </div>
        <div class="campo">
            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" required>
        </div>
        <div class="campo">
            <label for="confirmar_senha">Confirmar senha</label>
            <input type="password" id="confirmar_senha" name="confirmar_senha" required>
        </div>
        <button type="submit">Cadastrar</button>
    </form>

    <p>Já tem conta? <a href="login.php">Entrar</a></p>
    <p><a href="index.html">Voltar</a></p>
</body>
</html>
