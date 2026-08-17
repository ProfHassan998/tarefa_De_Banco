-- banco.sql
-- Script de criação do banco de dados usado pelo projeto de cadastro.
--
-- Como usar no phpMyAdmin (http://localhost/phpmyadmin/):
--   1. Abra o phpMyAdmin.
--   2. Clique na aba "SQL" (na tela inicial, sem selecionar nenhum banco).
--   3. Cole todo o conteúdo deste arquivo.
--   4. Clique em "Executar".
-- Isso cria o banco "aula" e a tabela "cadastro" prontos para uso.
--
-- Também é possível usar "Importar" no phpMyAdmin e selecionar este arquivo.

CREATE DATABASE IF NOT EXISTS aula
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE aula;

CREATE TABLE IF NOT EXISTS cadastro (
    codigo     INT AUTO_INCREMENT PRIMARY KEY,
    nome       VARCHAR(50)  NOT NULL,
    sobrenome  VARCHAR(50)  NOT NULL,
    endereco   VARCHAR(100),
    cidade     VARCHAR(100),
    telefone   VARCHAR(20),
    comentario TEXT
);

-- Tabela de contas de acesso (login e senha), usada por cadastro_login.php
-- e login.php. Não confundir com a tabela "cadastro" acima, que guarda os
-- registros de contatos (nome, endereço, etc.), não contas de usuário.
CREATE TABLE IF NOT EXISTS usuarios (
    id    INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50)  NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
);
