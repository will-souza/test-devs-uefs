# Teste Técnico para a vaga de Engenheiro de Software no projeto UEFS - Avansys/ACP Group

## 📝 Visão Geral do Projeto
Este projeto consiste em uma API Restful desenvolvida em Laravel 12 que implementa um CRUD completo para Usuários, Posts e Tags, seguindo os princípios SOLID e padrões PSR.

A aplicação foi desenvolvida como parte do processo seletivo para a vaga de Engenheiro de Software no projeto UEFS - Avansys/ACP Group.

## 🛠️ Tecnologias Utilizadas
- Laravel 12
- PHP 8.2
- SQLite (banco de dados embutido)
- Docker + Docker Compose (Containerização)
- API Restful (Padrão arquitetural)

## 🚀 Execução do projeto

### Clone o repositório:
```bash
git clone git@github.com:will-souza/test-devs-uefs.git
cd test-devs-uefs
```

### Construa e inicie os containers:
```bash
docker-compose up -d --build
```

(Para fins de avaliação, o projeto não precisa de nenhum comando adicional para a sua execução, todas as instalações e scripts são executados pelo Dockerfile).

### Acesse a aplicação:
```
API: http://localhost:8000/api
```

## 📚 Documentação da API
A API segue o padrão RESTful e está disponível com o prefixo /api. Todos os endpoints retornam JSON.

### Endpoints Disponíveis
### 👤 Usuários (/api/users)
- ```GET /users``` - Lista todos os usuários
- ```POST /users``` - Cria um novo usuário
- ```GET /users/{id}``` - Mostra um usuário específico
- ```PUT /users/{id}``` - Atualiza um usuário
- ```DELETE /users/{id}``` - Remove um usuário

### 📝 Posts (/api/posts)
- ```GET /posts``` - Lista todos os posts
- ```POST /posts``` - Cria um novo post
- ```GET /posts/{id}``` - Mostra um post específico
- ```PUT /posts/{id}``` - Atualiza um post
- ```DELETE /posts/{id}``` - Remove um post

### 🏷️ Tags (/api/tags)
- ```GET /tags``` - Lista todas as tags
- ```POST /tags``` - Cria uma nova tag
- ```GET /tags/{id}``` - Mostra uma tag específica
- ```PUT /tags/{id}``` - Atualiza uma tag
- ```DELETE /tags/{id}``` - Remove uma tag

## 📄 Licença
Este projeto é para fins de avaliação técnica.
