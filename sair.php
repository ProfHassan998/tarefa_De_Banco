<?php
// sair.php
// Encerra a sessão do usuário logado e volta para a tela de login.

session_start();
session_destroy();
header("Location: login.php");
exit;
?>
