<?php
// consulta_a_z.php
// Mesma ideia de consulta.php, mas mostrando o uso do ORDER BY:
// os registros aparecem em ordem alfabética pelo nome (A a Z).

require_once "conexao.php";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Consulta em Ordem A-Z</title>
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
        <h2>Lista de Cadastros (ordem A-Z)</h2>

        <?php
        // Passo 1: Preparar a consulta SQL com ORDER BY nome ASC (A -> Z)
        $sql = "SELECT codigo, nome, sobrenome, endereco, cidade, telefone, comentario
                FROM cadastro
                ORDER BY nome ASC";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Executa a consulta
            $stmt->execute();
            // Obtém o resultado da busca
            $resultado = $stmt->get_result();

            // Passo 2: Verificar se existem registros e exibi-los
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
                echo "<p class='total-registros'>Total de registros: " . $resultado->num_rows . "</p>";
            } else {
                echo "<p>Nenhum registro encontrado no banco de dados.</p>";
            }

            // Fecha a declaração preparada
            $stmt->close();
        } else {
            echo "<p class='mensagem-erro'>Erro na preparação da consulta: " . htmlspecialchars($conn->error) . "</p>";
        }

        // Passo 3: Fechar conexão
        $conn->close();
        ?>
    </div>

    <footer class="rodape">
        Feito com PHP + MySQL &middot; XAMPP &middot; projeto de aula
    </footer>

</div>
</body>
</html>
