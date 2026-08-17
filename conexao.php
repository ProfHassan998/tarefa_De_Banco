<?php
// conexao.php
// Arquivo único de configuração da conexão com o banco de dados.
// Todos os outros arquivos PHP do projeto usam este arquivo (require_once).
// Assim, se precisar mudar usuário/senha/banco, só precisa alterar aqui.

$servername = "localhost"; // Servidor MySQL do XAMPP
$username   = "root";      // Usuário padrão do MySQL no XAMPP
$password   = "";          // Senha padrão do MySQL no XAMPP é vazia.
                            // Se o SEU MySQL tiver senha configurada, troque "" pela sua senha aqui.
$dbname     = "aula";       // Nome do banco criado no phpMyAdmin (ver banco.sql)
$port       = 3307;         // Porta do MySQL do XAMPP nesta máquina.
                            // Padrão do XAMPP é 3306; aqui foi reconfigurado para 3307
                            // porque a porta 3306 já era usada por outra instalação de
                            // MySQL (serviço "MySQL80"). Confira C:\xampp\mysql\bin\my.ini
                            // (linha "port=") se precisar conferir/alterar.

// Desde o PHP 8.1, o mysqli por padrão joga uma exceção (tela de erro feia,
// com stack trace) quando a conexão ou uma consulta falha. Para este projeto
// didático, preferimos o comportamento clássico: apenas verificar o erro
// manualmente com if/else, como é feito nos arquivos abaixo.
mysqli_report(MYSQLI_REPORT_OFF);

// Cria a conexão com o banco de dados usando MySQLi
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Verifica se a conexão deu certo
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Define o charset para lidar corretamente com acentuação (ç, ã, é, etc.)
$conn->set_charset("utf8mb4");
?>
