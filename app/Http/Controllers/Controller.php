<?php

namespace App\Http\Controllers;
use OpenApi\Attributes as OA;


#[OA\OpenApi(
    info: new OA\Info(
        version: '1.0.0',
        title: 'API para gerenciamento de usuários, posts e tags',
        description: 'API referente ao teste técnico para a vaga de Engenheiro de Software no projeto UEFS - Avansys/ACP Group',
        termsOfService: 'http://api.example.com/terms',
        contact: new OA\Contact(name: 'Suporte API', email: 'suporte@example.com'),
        license: new OA\License(name: 'MIT', url: 'https://opensource.org/licenses/MIT')
    ),
    servers: [
        new OA\Server(url: 'http://localhost:8000/api', description: 'Servidor Local'),
    ],
    tags: [
        new OA\Tag(name: 'Users', description: 'Operações relacionadas a usuários'),
        new OA\Tag(name: 'Posts', description: 'Operações relacionadas a posts'),
        new OA\Tag(name: 'Tags', description: 'Operações relacionadas a tags'),
    ],
    security: [
        ['bearerAuth' => []]
    ]
)]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT'
)]
#[OA\Schema(
    schema: 'Pagination',
    properties: [
        new OA\Property(property: 'current_page', type: 'integer'),
        new OA\Property(property: 'per_page', type: 'integer'),
        new OA\Property(property: 'total', type: 'integer'),
    ]
)]
abstract class Controller
{
    //
}
