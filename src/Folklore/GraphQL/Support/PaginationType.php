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
                    'resolve' => function (LengthAwarePaginator $data) {
                        return $data->getCollection();
                    },
                ],
                'total' => [
                    'type' => GraphQLType::int(),
                    'description' => 'Number of total items selected by the query',
                    'resolve' => function (LengthAwarePaginator $data) {
                        return $data->total();
                    }
                ],
                'per_page' => [
                    'type' => GraphQLType::int(),
                    'description' => 'Number of items returned per page',
                    'resolve' => function (LengthAwarePaginator $data) {
                        return $data->perPage();
                    }
                ],
                'last_page' => [
                    'type' => GraphQLType::int(),
                    'description' => 'Current page of the cursor',
                    'resolve' => function ($data) {
                        return $data->lastPage();
                    }
                ],
                'current_page' => [
                    'type' => GraphQLType::int(),
                    'description' => 'Current page of the cursor',
                    'resolve' => function (LengthAwarePaginator $data) {
                        return $data->currentPage();
                    }
                ],
                'from' => [
                    'type' => GraphQLType::int(),
                    'description' => 'Current page of the cursor',
                    'resolve' => function (LengthAwarePaginator $data) {
                        return $data->firstItem();
                    }
                ],
                'to' => [
                    'type' => GraphQLType::int(),
                    'description' => 'Current page of the cursor',
                    'resolve' => function (LengthAwarePaginator $data) {
                        return $data->lastItem();
                    }
                ],
                'next_page_url' => [
                    'type' => GraphQLType::int(),
                    'description' => 'Current page of the cursor',
                    'resolve' => function (LengthAwarePaginator $data) {
                        return $data->nextPageUrl();
                    }
                ],
                'prev_page_url' => [
                    'type' => GraphQLType::int(),
                    'description' => 'Current page of the cursor',
                    'resolve' => function (LengthAwarePaginator $data) {
                        return $data->previousPageUrl();
                    }
                ],
            ],
        ]);
    }
}