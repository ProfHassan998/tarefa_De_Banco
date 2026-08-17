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
            echo "<p style='color:red;'>Erro ao atualizar: " . $stmt_update->error . "</p>";
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
</head>
<body>

    <h2>Editar Registro (Código: <?php echo $codigo; ?>)</h2>

    <!-- O formulário envia os dados via POST para ele mesmo -->
    <form method="POST" action="editar.php">
        <!-- Campo oculto que carrega o código do registro sem o usuário poder alterá-lo -->
        <input type="hidden" name="codigo" value="<?php echo $codigo; ?>">

        <table cellpadding="8">
            <tr>
                <td><label>Nome:</label></td>
                <td><input type="text" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required></td>
            </tr>
            <tr>
                <td><label>Sobrenome:</label></td>
                <td><input type="text" name="sobrenome" value="<?php echo htmlspecialchars($sobrenome); ?>"></td>
            </tr>
            <tr>
                <td><label>Endereço:</label></td>
                <td><input type="text" name="endereco" value="<?php echo htmlspecialchars($endereco); ?>"></td>
            </tr>
            <tr>
                <td><label>Cidade:</label></td>
                <td><input type="text" name="cidade" value="<?php echo htmlspecialchars($cidade); ?>"></td>
            </tr>
            <tr>
                <td><label>Telefone:</label></td>
                <td><input type="text" name="telefone" value="<?php echo htmlspecialchars($telefone); ?>"></td>
            </tr>
            <tr>
                <td><label>Comentário:</label></td>
                <td><textarea name="comentario" rows="4" cols="30"><?php echo htmlspecialchars($comentario); ?></textarea></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit" name="atualizar">Salvar Alterações</button>
                    <a href="edicao.php" style="margin-left: 10px; text-decoration: none; color: gray;">Cancelar</a>
                </td>
            </tr>
        </table>
    </form>

</body>
</html>
