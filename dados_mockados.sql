-- dados_mockados.sql
-- Dados de exemplo (mock) para testar o sistema sem precisar cadastrar tudo
-- manualmente pelo formulário.
--
-- Como usar no phpMyAdmin (http://localhost/phpmyadmin/):
--   1. Rode banco.sql primeiro (cria o banco "aula" e as tabelas).
--   2. Abra o phpMyAdmin, selecione o banco "aula", clique na aba "SQL".
--   3. Cole todo o conteúdo deste arquivo e clique em "Executar".

USE aula;

-- Conta de login pronta para testar (ver login.php):
--   login: demo
--   senha: demo123
-- A senha já está salva como hash (password_hash), igual o cadastro_login.php faz.
INSERT INTO usuarios (login, senha) VALUES
('demo', '$2y$10$Th68z7hGqWiLJ1zSKQg01OL/iH7wUQKuF.XUjG2mNDyx3/5cwkxji');

-- Contatos de exemplo (tabela "cadastro"):
INSERT INTO cadastro (nome, sobrenome, endereco, cidade, telefone, comentario) VALUES
('Ana',     'Souza',    'Rua das Flores, 123',      'São Paulo',      '(11) 91234-5678', 'Cliente antiga, prefere contato por telefone.'),
('Bruno',   'Oliveira', 'Av. Brasil, 456',          'Rio de Janeiro', '(21) 99876-5432', 'Indicado por outro cliente.'),
('Carla',   'Lima',     'Rua XV de Novembro, 789',  'Curitiba',       '(41) 98765-4321', ''),
('Daniel',  'Santos',   'Rua Bahia, 321',           'Belo Horizonte', '(31) 99123-4567', 'Aguardando retorno de orçamento.'),
('Eduarda', 'Pereira',  'Av. Boa Viagem, 654',      'Recife',         '(81) 99988-7766', 'Contato feito via redes sociais.'),
('Felipe',  'Costa',    'Rua da Praia, 987',        'Florianópolis',  '(48) 99654-3210', ''),
('Gabriela','Almeida',  'Rua dos Andradas, 159',    'Porto Alegre',   '(51) 99321-6540', 'Prefere ser atendida pela manhã.'),
('Hugo',    'Ferreira', 'Av. Sete de Setembro, 753','Salvador',       '(71) 99567-8901', ''),
('Isabela', 'Rodrigues','Rua Marechal Deodoro, 246','Fortaleza',      '(85) 99234-5670', 'Já comprou anteriormente.'),
('João',    'Martins',  'Rua Direita, 852',         'Belém',         '(91) 99876-1234', '');
