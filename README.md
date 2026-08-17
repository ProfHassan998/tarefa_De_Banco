# Tarefa de Banco

Projeto didático em PHP + MySQL de CRUD de cadastros (nome, endereço, telefone etc.), com uma tela de login simples para proteger a listagem de registros.

## Funcionalidades

- **Cadastro de contatos** — formulário que grava nome, sobrenome, endereço, cidade, telefone e comentário.
- **Conta de acesso (login/senha)** — cadastro de usuário com senha protegida por hash (`password_hash`/`password_verify`).
- **Login com sessão** — a listagem geral de cadastros só pode ser acessada por quem estiver autenticado.
- **Consulta** — lista todos os registros, em ordem natural ou ordenados de A-Z por nome.
- **Pesquisa** — busca por nome usando `LIKE`.
- **Edição e exclusão** — lista com busca e ações para editar ou excluir cada registro.

## Estrutura do projeto

| Arquivo | Descrição |
|---|---|
| `banco.sql` | Script SQL que cria o banco `aula` e as tabelas `cadastro` e `usuarios`. |
| `conexao.php` | Configuração central da conexão com o MySQL (usada via `require_once` pelos demais arquivos). |
| `index.html` | Formulário de cadastro de contatos e menu de navegação para as demais páginas. |
| `salvar.php` | Recebe o POST de `index.html` e insere o registro na tabela `cadastro`. |
| `cadastro_login.php` | Cria uma conta de acesso (login e senha) na tabela `usuarios`. |
| `login.php` | Autentica o usuário e inicia a sessão. |
| `sair.php` | Encerra a sessão (logout). |
| `consulta.php` | Lista todos os cadastros (página protegida, exige login). |
| `consulta_a_z.php` | Lista os cadastros ordenados por nome (A-Z). |
| `pesquisa.php` | Pesquisa cadastros por nome. |
| `edicao.php` | Lista os cadastros com opções de editar/excluir. |
| `editar.php` | Formulário de edição de um cadastro específico. |

## Requisitos

- PHP 8.1+ com a extensão `mysqli`
- MySQL/MariaDB (por exemplo, via [XAMPP](https://www.apachefriends.org/))

## Como rodar

1. Instale e inicie o XAMPP (ou outro ambiente com Apache + MySQL + PHP).
2. Copie a pasta do projeto para `htdocs` (ex.: `C:\xampp\htdocs\tarefa_De_Banco`).
3. Abra o [phpMyAdmin](http://localhost/phpmyadmin/), vá na aba **SQL**, cole o conteúdo de `banco.sql` e clique em **Executar** (isso cria o banco `aula` e as tabelas `cadastro` e `usuarios`).
4. Se o seu MySQL tiver usuário/senha diferentes do padrão do XAMPP, ajuste `conexao.php`.
5. Acesse `http://localhost/tarefa_De_Banco/index.html` no navegador.

## Fluxo de uso

1. Em `index.html`, cadastre um contato ou vá em **Criar conta** para criar um login.
2. Crie uma conta em `cadastro_login.php` e faça login em `login.php`.
3. Após o login, você é redirecionado para `consulta.php`, que lista todos os cadastros.
4. Use `pesquisa.php` para buscar por nome ou `edicao.php` para editar/excluir registros.

## Observações de segurança

- Todas as consultas usam **prepared statements** (`mysqli::prepare` + `bind_param`), evitando SQL Injection.
- Toda saída de dados do usuário passa por `htmlspecialchars`, evitando XSS.
- Senhas nunca são armazenadas em texto puro — são salvas com `password_hash` e comparadas com `password_verify`.
- Este projeto tem fins didáticos: `consulta_a_z.php`, `pesquisa.php` e `edicao.php` não exigem login, apenas `consulta.php` está protegida por sessão.
