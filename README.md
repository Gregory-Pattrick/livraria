# Library API MVC

API simples para gerenciamento de livros.

## Tecnologias utilizadas

- PHP 8+
- HTML5
- Bootstrap 5
- JavaScript puro
- JSON como persistência local
- Arquitetura MVC adaptada para API

## Estrutura do projeto

```text
api_books_mvc/
├── app/
│   ├── Controllers/
│   │   ├── BookController.php
│   │   └── HomeController.php
│   ├── Core/
│   │   ├── Request.php
│   │   ├── Response.php
│   │   └── Router.php
│   ├── Models/
│   │   └── Book.php
│   └── Repositories/
│       └── BookRepository.php
├── data/
│   └── books.json
├── public/
│   ├── index.php
│   └── views/
│       └── home.html
├── .gitignore
└── README.md
```

## Pré-requisitos

Antes de iniciar, tenha instalado:

- PHP 8.0 ou superior
- Git, opcional, para versionamento
- Postman, Insomnia ou terminal para testar os endpoints

Não é necessário instalar Composer, banco de dados ou framework.

## Como executar localmente

Clone o repositório:

```bash
git clone https://github.com/SEU-USUARIO/api-books-mvc.git
cd api-books-mvc
```

Inicie o servidor local do PHP:

```bash
php -S localhost:8000 -t public
```

Acesse no navegador:

```text
http://localhost:8000
```

A API estará disponível em:

```text
http://localhost:8000/api/books
```

## Endpoints disponíveis

| Método | Endpoint | Descrição |
|---|---|---|
| GET | `/api/books` | Lista todos os livros |
| GET | `/api/books/{id}` | Busca um livro por ID |
| POST | `/api/books` | Cadastra um novo livro |
| PUT | `/api/books/{id}` | Atualiza um livro existente |
| PATCH | `/api/books/{id}` | Atualiza parcialmente um livro |
| DELETE | `/api/books/{id}` | Remove um livro |

## Exemplo de uso

### Listar livros

```bash
curl http://localhost:8000/api/books
```

### Buscar livro por ID

```bash
curl http://localhost:8000/api/books/1
```

### Criar livro

```bash
curl -X POST http://localhost:8000/api/books \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Refactoring",
    "author": "Martin Fowler",
    "category": "Software Engineering",
    "published_year": 1999,
    "status": "available"
  }'
```

### Atualizar livro

```bash
curl -X PUT http://localhost:8000/api/books/1 \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Clean Code - Updated",
    "author": "Robert C. Martin",
    "category": "Software Engineering",
    "published_year": 2008,
    "status": "borrowed"
  }'
```

### Remover livro

```bash
curl -X DELETE http://localhost:8000/api/books/1
```

## Exemplos com PowerShell

### Criar livro

```powershell
$body = @{
    title = "Refactoring"
    author = "Martin Fowler"
    category = "Software Engineering"
    published_year = 1999
    status = "available"
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://localhost:8000/api/books" -Method POST -Body $body -ContentType "application/json"
```

### Listar livros

```powershell
Invoke-RestMethod -Uri "http://localhost:8000/api/books" -Method GET
```

## Regras de validação

- `title`, `author`, `category` e `published_year` são obrigatórios no cadastro.
- `published_year` deve ser um ano válido entre 1000 e o ano atual.
- `status` aceita apenas `available` ou `borrowed`.

## Arquitetura MVC

Este projeto segue uma estrutura MVC adaptada para API:

- **Model:** representa a entidade `Book`.
- **Controller:** recebe as requisições e coordena as respostas da API.
- **Repository:** centraliza a leitura e escrita dos dados em JSON.
- **View/Presentation:** a camada visual está em `public/views/home.html` e as respostas JSON são tratadas pela classe `Response`.

## Observações

A persistência foi feita com arquivo JSON para manter o projeto simples.
