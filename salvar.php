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
        echo "Dados inseridos com sucesso! <a href='consulta.php'>Ver cadastros</a> | <a href='index.html'>Novo cadastro</a>";
    } else {
        echo "Erro ao inserir dados: " . $stmt->error;
    }
    // Fecha a declaração preparada
    $stmt->close();
} else {
    echo "Erro na preparação da consulta: " . $conn->error;
}

// Passo 4: Fechar conexão
$conn->close();
?>
