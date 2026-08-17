<?php
// salvar.php
// Recebe os dados enviados pelo formulário (index.html) via POST
// e envia um comando SQL de inserção (INSERT) para o MySQL.

// Passo 1: Conectar (configuração centralizada em conexao.php)
require_once "conexao.php";

// Passo 2: Receber dados do formulário com segurança (evita erros se o campo estiver vazio)
$nome       = $_POST['nome'] ?? '';
$sobrenome  = $_POST['sobrenome'] ?? '';
$endereco   = $_POST['endereco'] ?? '';
$cidade     = $_POST['cidade'] ?? '';
$telefone   = $_POST['telefone'] ?? '';
$comentario = $_POST['comentario'] ?? '';

// Passo 3: Inserir dados de forma segura usando Prepared Statements
// 'codigo' não aparece aqui porque é AUTO_INCREMENT no MySQL (ver banco.sql)
$sql = "INSERT INTO cadastro (nome, sobrenome, endereco, cidade, telefone, comentario)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
if ($stmt) {
    // Vincula os parâmetros ("ssssss" indica que são 6 variáveis do tipo string)
    $stmt->bind_param("ssssss", $nome, $sobrenome, $endereco, $cidade, $telefone, $comentario);

    // Executa a consulta com os dados protegidos
    if ($stmt->execute()) {
        $mensagem = "Dados inseridos com sucesso!";
        $classe_mensagem = "mensagem-sucesso";
    } else {
        $mensagem = "Erro ao inserir dados: " . $stmt->error;
        $classe_mensagem = "mensagem-erro";
    }
    // Fecha a declaração preparada
    $stmt->close();
} else {
    $mensagem = "Erro na preparação da consulta: " . $conn->error;
    $classe_mensagem = "mensagem-erro";
}

// Passo 4: Fechar conexão
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro Enviado</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<div class="pagina">

    <header class="topo">
        <h1><span class="icone">💾</span>Sistema de Cadastro</h1>
        <p class="tagline">Cadastro, consulta e login &mdash; PHP + MySQL, do jeito clássico.</p>
    </header>

    <nav class="menu">
        <ul>
            <li><a href="index.html">Início</a></li>
            <li><a href="cadastro_login.php">Criar Conta</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="consulta.php">Consulta</a></li>
            <li><a href="consulta_a_z.php">Consulta A-Z</a></li>
            <li><a href="pesquisa.php">Pesquisar</a></li>
            <li><a href="edicao.php">Editar / Excluir</a></li>
        </ul>
    </nav>

    <div class="painel">
        <h2>Resultado do Cadastro</h2>
        <p class="<?php echo $classe_mensagem; ?>"><?php echo htmlspecialchars($mensagem); ?></p>
        <p><a href="consulta.php" class="botao">Ver cadastros</a> <a href="index.html" class="botao">Novo cadastro</a></p>
    </div>

    <footer class="rodape">
        Feito com PHP + MySQL &middot; XAMPP &middot; projeto de aula
    </footer>

</div>
</body>
</html>
