<?php
// edicao.php
// Lista os cadastros com um campo de busca por nome e, para cada linha,
// oferece links para Editar (editar.php) ou Excluir (DELETE no MySQL).

require_once "conexao.php";

$mensagem_erro = "";

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
            $mensagem_erro = "Erro ao excluir registro: " . $stmt_delete->error;
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
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar / Excluir Cadastros</title>
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
        <h2>Consultar por Nome</h2>

        <?php if ($mensagem_erro !== ""): ?>
            <p class="mensagem-erro"><?php echo htmlspecialchars($mensagem_erro); ?></p>
        <?php endif; ?>

        <form method="GET" action="" style="margin-bottom: 18px;">
            <input type="search" name="busca" placeholder="Digite o nome..." value="<?php echo htmlspecialchars($nome_busca); ?>">
            <button type="submit">Buscar</button>
            <a href="edicao.php" class="botao">Limpar Busca</a>
        </form>

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
                echo "<table>";
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
                    echo "<td class='acoes'>";
                    echo " <a href='editar.php?codigo=" . $id . "' class='botao'>📝 Editar</a> ";
                    echo " <a href='edicao.php?excluir=" . $id . "' onclick=\"return confirm('Tem certeza que deseja excluir este registro?');\" class='botao excluir'>❌ Excluir</a>";
                    echo "</td>";

                    echo "</tr>";
                }
                echo "</table>";
                echo "<p class='total-registros'>Total de registros encontrados: " . $resultado->num_rows . "</p>";
            } else {
                echo "<p>Nenhum registro encontrado.</p>";
            }

            $stmt->close();
        } else {
            echo "<p class='mensagem-erro'>Erro na preparação da consulta: " . htmlspecialchars($conn->error) . "</p>";
        }

        $conn->close();
        ?>
    </div>

    <footer class="rodape">
        Feito com PHP + MySQL &middot; XAMPP &middot; projeto de aula
    </footer>

</div>
</body>
</html>
