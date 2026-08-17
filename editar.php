<?php
// editar.php
// Passo 1: Conectar ao banco de dados
require_once "conexao.php";

// Inicializa variáveis para os campos do formulário
$codigo = "";
$nome = "";
$sobrenome = "";
$endereco = "";
$cidade = "";
$telefone = "";
$comentario = "";
$mensagem_erro = "";

// --- PARTE 1: CARREGAR OS DADOS DO REGISTRO ---
if (isset($_GET['codigo'])) {
    $codigo = intval($_GET['codigo']);

    // Busca o registro correspondente ao código passado pela URL
    $sql = "SELECT nome, sobrenome, endereco, cidade, telefone, comentario FROM cadastro WHERE codigo = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $codigo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $linha = $resultado->fetch_assoc();

            // O operador ?? '' evita o erro se a chave não existir
            $nome       = $linha['nome'] ?? '';
            $sobrenome  = $linha['sobrenome'] ?? '';
            $endereco   = $linha['endereco'] ?? '';
            $cidade     = $linha['cidade'] ?? '';
            $telefone   = $linha['telefone'] ?? '';
            $comentario = $linha['comentario'] ?? '';
        } else {
            die("Registro não encontrado.");
        }
        $stmt->close();
    }
} else if (!isset($_POST['atualizar'])) {
    die("Código de registro inválido.");
}

// --- PARTE 2: SALVAR AS ALTERAÇÕES (POST) ---
if (isset($_POST['atualizar'])) {
    $codigo = intval($_POST['codigo']);
    $nome = trim($_POST['nome']);
    $sobrenome = trim($_POST['sobrenome']);
    $endereco = trim($_POST['endereco']);
    $cidade = trim($_POST['cidade']);
    $telefone = trim($_POST['telefone']);
    $comentario = trim($_POST['comentario']);

    // Prepara o comando SQL de atualização de forma segura
    $sql_update = "UPDATE cadastro SET nome = ?, sobrenome = ?, endereco = ?, cidade = ?, telefone = ?, comentario = ? WHERE codigo = ?";
    $stmt_update = $conn->prepare($sql_update);

    if ($stmt_update) {
        $stmt_update->bind_param("sssssi", $nome, $sobrenome, $endereco, $cidade, $telefone, $comentario, $codigo);

        if ($stmt_update->execute()) {
            // Alerta de sucesso e redireciona de volta para a lista principal
            echo "<script>alert('Registro atualizado com sucesso!'); window.location.href='edicao.php';</script>";
        } else {
            $mensagem_erro = "Erro ao atualizar: " . $stmt_update->error;
        }
        $stmt_update->close();
    }
}

$conn->close();
?>

<!-- --- PARTE 3: FORMULÁRIO HTML PREENCHIDO --- -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Cadastro</title>
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
        <h2>Editar Registro (Código: <?php echo $codigo; ?>)</h2>

        <?php if ($mensagem_erro !== ""): ?>
            <p class="mensagem-erro"><?php echo htmlspecialchars($mensagem_erro); ?></p>
        <?php endif; ?>

        <!-- O formulário envia os dados via POST para ele mesmo -->
        <form method="POST" action="editar.php">
            <!-- Campo oculto que carrega o código do registro sem o usuário poder alterá-lo -->
            <input type="hidden" name="codigo" value="<?php echo $codigo; ?>">

            <div class="campo">
                <label>Nome:</label>
                <input type="text" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required>
            </div>
            <div class="campo">
                <label>Sobrenome:</label>
                <input type="text" name="sobrenome" value="<?php echo htmlspecialchars($sobrenome); ?>">
            </div>
            <div class="campo">
                <label>Endereço:</label>
                <input type="text" name="endereco" value="<?php echo htmlspecialchars($endereco); ?>">
            </div>
            <div class="campo">
                <label>Cidade:</label>
                <input type="text" name="cidade" value="<?php echo htmlspecialchars($cidade); ?>">
            </div>
            <div class="campo">
                <label>Telefone:</label>
                <input type="text" name="telefone" value="<?php echo htmlspecialchars($telefone); ?>">
            </div>
            <div class="campo">
                <label>Comentário:</label>
                <textarea name="comentario" rows="4"><?php echo htmlspecialchars($comentario); ?></textarea>
            </div>
            <div class="campo">
                <button type="submit" name="atualizar">Salvar Alterações</button>
                <a href="edicao.php" class="botao">Cancelar</a>
            </div>
        </form>
    </div>

    <footer class="rodape">
        Feito com PHP + MySQL &middot; XAMPP &middot; projeto de aula
    </footer>

</div>
</body>
</html>
