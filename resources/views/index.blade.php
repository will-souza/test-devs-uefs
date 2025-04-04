<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste Técnico UEFS - Avansys/ACP Group - Posts Recentes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .post-card {
            transition: 0.2s;
        }
        .post-card:hover {
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .tag-badge {
            margin-right: 5px;
            margin-bottom: 5px;
        }
        .loading-spinner {
            display: none;
            text-align: center;
            padding: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="/">Teste Técnico</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/api/documentation">Swagger</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row mb-4">
            <div class="col-md-10 mx-auto text-center">
                <h1 class="display-4">Últimos posts</h1>
                <p class="lead mb-1">Listagem dos posts recentes consumidos através de requisição AJAX proveniente da rota <code>api/posts</code>.</p>
                <p>
                    Este é apenas um preview, sem a necessidade de página interna para cada post pois desviaria o propósito da aplicação, que é ser uma API.
                    Para o frontend e consumo dessas informações, seria mais interessante uma aplicação desacoplada com apenas o intuido de consumir e manipular os dados.
                </p>
            </div>
        </div>

        <div id="loading-spinner" class="loading-spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Carregando...</span>
            </div>
            <p class="mt-2">Carregando posts...</p>
        </div>

        <div id="posts-container" class="row"></div>

        <div id="no-posts" class="text-center py-5" style="display: none;">
            <i class="fas fa-newspaper fa-3x mb-3 text-muted"></i>
            <h3>Nenhum post encontrado</h3>
            <p class="text-muted">Ainda não há posts publicados.</p>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#loading-spinner').show();

            $.ajax({
                url: '/api/posts',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#loading-spinner').hide();

                    if (response.length > 0) {
                        response.forEach(function(post) {
                            const createdAt = new Date(post.created_at);
                            const formattedDate = createdAt.toLocaleDateString('pt-BR', {
                                day: '2-digit',
                                month: 'long',
                                year: 'numeric'
                            });
                            console.log(formattedDate, post.created_at);

                            let tagsHtml = '';
                            if (post.tags && post.tags.length > 0) {
                                post.tags.forEach(function(tag) {
                                    tagsHtml += `<span class="badge bg-dark tag-badge">${tag.name}</span>`;
                                });
                            }

                            const postHtml = `
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card post-card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">${post.title}</h5>
                                            <p class="card-text text-muted">
                                                <small>Por <a href="#">${post.user.name}</a> em ${formattedDate}</small>
                                            </p>
                                            <p class="card-text">${post.content.substring(0, 150)}...</p>
                                            ${tagsHtml}
                                        </div>
                                    </div>
                                </div>
                            `;

                            $('#posts-container').append(postHtml);
                        });
                    } else {
                        $('#no-posts').show();
                    }
                },
                error: function(xhr, status, error) {
                    $('#loading-spinner').hide();
                    $('#posts-container').html(`
                        <div class="col-12">
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Ocorreu um erro ao carregar os posts. Por favor, tente novamente mais tarde.
                            </div>
                        </div>
                    `);
                    console.error('Erro ao carregar posts:', error);
                }
            });
        });
    </script>
</body>
</html>
