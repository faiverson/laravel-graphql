<?php

namespace Folklore\GraphQL\Support;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type as GraphQLType;
use Illuminate\Pagination\LengthAwarePaginator;
use GraphQL;

class PaginationType extends ObjectType
{
    public function __construct($typeName)
    {
        parent::__construct([
            'name'  => $typeName . 'Pagination',
            'fields' => [
                'items' => [
                    'type' => GraphQLType::listOf(GraphQL::type($typeName)),
                    'resolve' => function ($data) {
                        if($data instanceof LengthAwarePaginator) {
                            return $data->getCollection();
                        }
                        return $data;
                    },
                ],
                'total' => [
                    'type' => GraphQLType::nonNull(GraphQLType::int()),
                    'description' => 'Number of total items selected by the query',
                    'resolve' => function ($data) {
                        if($data instanceof LengthAwarePaginator) {
                            return $data->total();
                        }
                        return NULL;
                    },
                    'selectable' => false,
                ],
                'per_page' => [
                    'type' => GraphQLType::nonNull(GraphQLType::int()),
                    'description' => 'Number of items returned per page',
                    'resolve' => function ($data) {
                        if($data instanceof LengthAwarePaginator) {
                            return $data->perPage();
                        }
                        return NULL;
                    },
                    'selectable' => false,
                ],
                'last_page' => [
                    'type' => GraphQLType::nonNull(GraphQLType::int()),
                    'description' => 'Current page of the cursor',
                    'resolve' => function ($data) {
                        if($data instanceof LengthAwarePaginator) {
                            return $data->lastPage();
                        }
                        return NULL;
                    },
                    'selectable' => false,
                ],
                'current_page' => [
                    'type' => GraphQLType::nonNull(GraphQLType::int()),
                    'description' => 'Current page of the cursor',
                    'resolve' => function ($data) {
                        if($data instanceof LengthAwarePaginator) {
                            return $data->currentPage();
                        }
                        return NULL;
                    },
                    'selectable' => false,
                ],
            ],
        ]);
    }
}