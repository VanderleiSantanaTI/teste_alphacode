

# API REST MVC em PHP 7.4.33 - Gerenciamento de Contatos

Esta API REST foi desenvolvida em PHP nativo (versão 7.4.33) com MySQL para gerenciar uma tabela de contatos. Ela foi criada por **Vanderlei de Santana de Andrade**, estudante da Fatec Carapicuíba.

## Configuração do Ambiente

Para configurar e usar esta API, siga estas etapas:

1. Instale o XAMPP versão 7.4.33, que pode ser baixado [aqui](https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/7.4.33/).
2. Copie os arquivos da API para a pasta `htdocs` do XAMPP.
3. Execute o script SQL para criar o banco de dados MySQL necessário.
4. Ajuste as configurações de conexão do banco de dados no arquivo `models/database.php`, modificando o host, usuário e senha conforme necessário.
5. Se seu servidor estiver utilizando uma porta diferente, modifique a base da URI `http://localhost` no código da API.

## Estrutura da Tabela

A tabela `contatos` contém os seguintes campos:

- `id` (int)
- `nome` (string)
- `profissao` (string)
- `nascimento` (string)
- `email` (string)
- `telefone` (string)
- `celular` (string)
- `celular_whatsapp` (boolean)
- `recebe_email` (boolean)
- `recebe_sms` (boolean)

## Operações CRUD

### Listar Todos os Contatos (DTO Simplificado)

- **URI:** `GET http://localhost/Rest_API_PHP/user`

### Consultar um Contato Específico

- **URI:** `GET http://localhost/Rest_API_PHP/user/{id}`

### Cadastrar um Novo Contato

- **URI:** `POST http://localhost/Rest_API_PHP/user/`

### Atualizar um Contato Existente

- **URI:** `PUT http://localhost/Rest_API_PHP/user/`

### Excluir um Contato

- **URI:** `DELETE http://localhost/Rest_API_PHP/user/{id}`



## Contato do Desenvolvedor

- **Desenvolvedor:** Vanderlei de Santana de Andrade
  - **LinkedIn:** [Vanderlei de Santana de Andrade](https://www.linkedin.com/in/vanderlei-de-s-andrade-b00391265/)
  - **GitHub:** [VanderleiSantanaTI](https://github.com/VanderleiSantanaTI)
- **Instituição:** Fatec Carapicuíba

---

Esta versão reflete a mesma essência da original, com a reordenação de alguns elementos e a atualização das suas redes sociais.