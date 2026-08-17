<?php
// edicao.php
// Lista os cadastros com um campo de busca por nome e, para cada linha,
// oferece links para Editar (editar.php) ou Excluir (DELETE no MySQL).

require_once "conexao.php";

// --- Lógica de Exclusão de Registro ---
if (isset($_GET['excluir'])) {
    $codigo_excluir = intval($_GET['excluir']); // Garante que é um número inteiro

    // Prepara a consulta de exclusão de forma segura
    $sql_delete = "DELETE FROM cadastro WHERE codigo = ?";
    $stmt_delete = $conn->prepare($sql_delete);

    if ($stmt_delete) {
        $stmt_delete->bind_param("i", $codigo_excluir);
        if ($stmt_delete->execute()) {
            echo "<script>alert('Registro excluído com sucesso!'); window.location.href='edicao.php';</script>";
        } else {
            echo "<p style='color:red;'>Erro ao excluir registro: " . $stmt_delete->error . "</p>";
        }
        $stmt_delete->close();
    }
}

// Captura o termo de busca enviado pelo formulário
$nome_busca = "";
if (isset($_GET['busca'])) {
    $nome_busca = trim($_GET['busca']);
}
?>

<!-- Formulário HTML de Consulta -->
<h2>Consultar por Nome</h2>
<form method="GET" action="">
    <input type="text" name="busca" placeholder="Digite o nome..." value="<?php echo htmlspecialchars($nome_busca); ?>">
    <button type="submit">Buscar</button>
    <a href="edicao.php">Limpar Busca</a>
</form>
<br>

<?php
// Passo 2: Preparar a consulta SQL dinâmica com base na busca
if ($nome_busca !== "") {
    $sql = "SELECT codigo, nome, sobrenome, endereco, cidade, telefone, comentario
            FROM cadastro
            WHERE nome LIKE ?
            ORDER BY nome ASC";
} else {
    $sql = "SELECT codigo, nome, sobrenome, endereco, cidade, telefone, comentario
            FROM cadastro
            ORDER BY nome ASC";
}

$stmt = $conn->prepare($sql);

if ($stmt) {
    if ($nome_busca !== "") {
        $param_busca = "%" . $nome_busca . "%";
        $stmt->bind_param("s", $param_busca);
    }

    $stmt->execute();
    $resultado = $stmt->get_result();

    // Passo 3: Verificar se existem registros e exibi-los
    if ($resultado->num_rows > 0) {
        echo "<h2>Lista de Cadastros</h2>";
        echo "<table border='1' cellpadding='10' cellspacing='0'>";
        echo "<tr><th>Codigo</th>
              <th>Nome</th><th>Sobrenome</th><th>Endereço</th><th>Cidade</th>
              <th>Telefone</th><th>Comentário</th>
              <th>Ações</th></tr>";

        while ($linha = $resultado->fetch_assoc()) {
            $id = (int) $linha['codigo'];
            echo "<tr>";
            echo "<td>" . htmlspecialchars($linha['codigo']) . "</td>";
            echo "<td>" . htmlspecialchars($linha['nome']) . "</td>";
            echo "<td>" . htmlspecialchars($linha['sobrenome']) . "</td>";
            echo "<td>" . htmlspecialchars($linha['endereco']) . "</td>";
            echo "<td>" . htmlspecialchars($linha['cidade']) . "</td>";
            echo "<td>" . htmlspecialchars($linha['telefone']) . "</td>";
            echo "<td>" . htmlspecialchars($linha['comentario']) . "</td>";

            // --- Botões de Editar e Excluir ---
            echo "<td>";
            echo " <a href='editar.php?codigo=" . $id . "' style='color: blue; font-weight: bold; text-decoration: none;'>📝 Editar</a> | ";
            echo " <a href='edicao.php?excluir=" . $id . "' onclick=\"return confirm('Tem certeza que deseja excluir este registro?');\" style='color: red; font-weight: bold; text-decoration: none;'>❌ Excluir</a>";
            echo "</td>";

            echo "</tr>";
        }
        echo "</table>";
        echo "<br>Total de registros encontrados: " . $resultado->num_rows;
    } else {
        echo "Nenhum registro encontrado.";
    }

    $stmt->close();
} else {
    echo "Erro na preparação da consulta: " . $conn->error;
}

$conn->close();
?>
<br>
<a href="index.html">Voltar ao cadastro</a>
