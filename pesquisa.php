<?php
// pesquisa.php
// Mostra um formulário de busca por nome e filtra os registros no MySQL
// usando a cláusula WHERE ... LIKE.

require_once "conexao.php";

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
    <title>Pesquisar Cadastros</title>
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

        <form method="GET" action="" style="margin-bottom: 18px;">
            <input type="search" name="busca" placeholder="Digite o nome..."
            value="<?php echo htmlspecialchars($nome_busca); ?>">
            <button type="submit">Buscar</button>
            <a href="pesquisa.php" class="botao">Limpar Busca</a>
        </form>

        <?php
        // Passo 2: Preparar a consulta SQL dinâmica com base na busca
        if ($nome_busca !== "") {
            // Filtra pelo nome usando LIKE para buscas parciais
            $sql = "SELECT codigo, nome, sobrenome, endereco, cidade, telefone, comentario
                    FROM cadastro
                    WHERE nome LIKE ?
                    ORDER BY nome ASC";
        } else {
            // Se não houver busca, traz todos os registros
            $sql = "SELECT codigo, nome, sobrenome, endereco, cidade, telefone, comentario
                    FROM cadastro
                    ORDER BY nome ASC";
        }

        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Se houver um termo de busca, vincula o parâmetro com os curingas '%'
            if ($nome_busca !== "") {
                $param_busca = "%" . $nome_busca . "%";
                $stmt->bind_param("s", $param_busca);
            }

            // Executa a consulta
            $stmt->execute();
            // Obtém o resultado da busca
            $resultado = $stmt->get_result();

            // Passo 3: Verificar se existem registros e exibi-los
            if ($resultado->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Codigo</th>
                      <th>Nome</th><th>Sobrenome</th><th>Endereço</th><th>Cidade</th>
                      <th>Telefone</th><th>Comentário</th></tr>";

                // Loop que percorre cada linha retornada pelo banco de dados
                while ($linha = $resultado->fetch_assoc()) {
                    echo "<tr>";
                    // htmlspecialchars protege contra ataques XSS ao exibir os dados
                    echo "<td>" . htmlspecialchars($linha['codigo']) . "</td>";
                    echo "<td>" . htmlspecialchars($linha['nome']) . "</td>";
                    echo "<td>" . htmlspecialchars($linha['sobrenome']) . "</td>";
                    echo "<td>" . htmlspecialchars($linha['endereco']) . "</td>";
                    echo "<td>" . htmlspecialchars($linha['cidade']) . "</td>";
                    echo "<td>" . htmlspecialchars($linha['telefone']) . "</td>";
                    echo "<td>" . htmlspecialchars($linha['comentario']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                echo "<p class='total-registros'>Total de registros encontrados: " . $resultado->num_rows . "</p>";
            } else {
                echo "<p>Nenhum registro encontrado para: '<strong>" . htmlspecialchars($nome_busca) . "</strong>'</p>";
            }

            // Fecha a declaração preparada
            $stmt->close();
        } else {
            echo "<p class='mensagem-erro'>Erro na preparação da consulta: " . htmlspecialchars($conn->error) . "</p>";
        }

        // Passo 4: Fechar conexão
        $conn->close();
        ?>
    </div>

    <footer class="rodape">
        Feito com PHP + MySQL &middot; XAMPP &middot; projeto de aula
    </footer>

</div>
</body>
</html>
