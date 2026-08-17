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

<!-- Formulário HTML de Consulta -->
<h2>Consultar por Nome</h2>
<form method="GET" action="">
    <input type="text" name="busca" placeholder="Digite o nome..."
    value="<?php echo htmlspecialchars($nome_busca); ?>">
    <button type="submit">Buscar</button>
    <a href="pesquisa.php">Limpar Busca</a>
</form>
<br>

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
        echo "<h2>Lista de Cadastros</h2>";
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
        echo "<br>Total de registros encontrados: " . $resultado->num_rows;
    } else {
        echo "Nenhum registro encontrado para: '<strong>" . htmlspecialchars($nome_busca) . "</strong>'";
    }

    // Fecha a declaração preparada
    $stmt->close();
} else {
    echo "Erro na preparação da consulta: " . $conn->error;
}

// Passo 4: Fechar conexão
$conn->close();
?>
<br>
<a href="index.html">Voltar ao cadastro</a>
