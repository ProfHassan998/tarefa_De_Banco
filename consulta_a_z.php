<?php
// consulta_a_z.php
// Mesma ideia de consulta.php, mas mostrando o uso do ORDER BY:
// os registros aparecem em ordem alfabética pelo nome (A a Z).

require_once "conexao.php";

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
        echo "<h2>Lista de Cadastros (ordem A-Z)</h2>";
        echo "<table border='1' cellpadding='10' cellspacing='0'>";
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
        echo "<br>Total de registros: " . $resultado->num_rows;
    } else {
        echo "Nenhum registro encontrado no banco de dados.";
    }

    // Fecha a declaração preparada
    $stmt->close();
} else {
    echo "Erro na preparação da consulta: " . $conn->error;
}

// Passo 3: Fechar conexão
$conn->close();
?>
<br>
<a href="index.html">Voltar ao cadastro</a>
