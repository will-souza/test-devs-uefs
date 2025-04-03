<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'User',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'name', type: 'string'),
        new OA\Property(property: 'email', type: 'string', format: 'email'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
        new OA\Property(
            property: 'posts',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/Post')
        )
    ]
)]
#[OA\Schema(
    schema: 'UserCreate',
    required: ['name', 'email', 'password'],
    properties: [
        new OA\Property(property: 'name', type: 'string', maxLength: 255),
        new OA\Property(property: 'email', type: 'string', format: 'email', maxLength: 255),
        new OA\Property(property: 'password', type: 'string', minLength: 8),
    ]
)]
#[OA\Schema(
    schema: 'UserUpdate',
    properties: [
        new OA\Property(property: 'name', type: 'string', maxLength: 255),
        new OA\Property(property: 'email', type: 'string', format: 'email', maxLength: 255),
        new OA\Property(property: 'password', type: 'string', minLength: 8),
    ]
)]
#[OA\Schema(
    schema: 'Post',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'title', type: 'string'),
        new OA\Property(property: 'content', type: 'string'),
        new OA\Property(property: 'user_id', type: 'integer'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
        new OA\Property(
            property: 'user',
            ref: '#/components/schemas/User'
        ),
        new OA\Property(
            property: 'tags',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/Tag')
        )
    ]
)]
#[OA\Schema(
    schema: 'PostCreate',
    required: ['title', 'content', 'user_id'],
    properties: [
        new OA\Property(property: 'title', type: 'string', maxLength: 255),
        new OA\Property(property: 'content', type: 'string'),
        new OA\Property(property: 'user_id', type: 'integer'),
        new OA\Property(
            property: 'tags',
            type: 'array',
            items: new OA\Items(type: 'integer'),
            description: 'IDs das tags associadas'
        )
    ]
)]
#[OA\Schema(
    schema: 'PostUpdate',
    properties: [
        new OA\Property(property: 'title', type: 'string', maxLength: 255),
        new OA\Property(property: 'content', type: 'string'),
        new OA\Property(property: 'user_id', type: 'integer'),
        new OA\Property(
            property: 'tags',
            type: 'array',
            items: new OA\Items(type: 'integer'),
            description: 'IDs das tags associadas'
        )
    ]
)]
#[OA\Schema(
    schema: 'Tag',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'name', type: 'string'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
        new OA\Property(
            property: 'posts',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/Post')
        )
    ]
)]
#[OA\Schema(
    schema: 'TagCreate',
    required: ['name'],
    properties: [
        new OA\Property(property: 'name', type: 'string', maxLength: 255)
    ]
)]
#[OA\Schema(
    schema: 'TagUpdate',
    properties: [
        new OA\Property(property: 'name', type: 'string', maxLength: 255)
    ]
)]
#[OA\Schema(
    schema: 'ValidationError',
    properties: [
        new OA\Property(property: 'message', type: 'string'),
        new OA\Property(
            property: 'errors',
            type: 'object',
        )
    ]
)]
class Models {}
