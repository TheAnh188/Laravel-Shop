<?php

return [
    'module' => [
        [
            'title' => 'Members',
            'icon' => 'fas fa-tachometer-alt mr-3',
            'name' => ['user', 'user-catalogue'],
            'sub_module' => [
                [
                    'title' => 'User Catalogue',
                    'route' => '/user-catalogue',
                ],
                [
                    'title' => 'Users',
                    'route' => '/user',
                ],
                [
                    'title' => 'Permission',
                    'route' => '/permission',
                ]
            ],
        ],
        [
            'title' => 'Posts',
            'icon' => 'fas fa-sticky-note mr-3',
            'name' => ['post', 'post-catalogue'],
            'sub_module' => [
                [
                    'title' => 'Post Catalogue',
                    'route' => '/post-catalogue',
                ],
                [
                    'title' => 'Posts',
                    'route' => '/post',
                ]
            ],
        ],
        [
            'title' => 'General Settings',
            'icon' => 'fas fa-sticky-note mr-3',
            'name' => ['language', 'generator'],
            'sub_module' => [
                [
                    'title' => 'Languages',
                    'route' => '/language',
                ],
                [
                    'title' => 'Modules',
                    'route' => '/generator',
                ],
            ],
        ],
        [
            'title' => 'Products',
            'icon' => 'fas fa-cube mr-3',
            'name' => ['product', 'product-catalogue', 'attribute', 'attribute-catalogue'],
            'sub_module' => [
                [
                    'title' => 'Product Catalogue',
                    'route' => '/product-catalogue',
                ],
                [
                    'title' => 'Products',
                    'route' => '/product',
                ],
                [
                    'title' => 'Attribute Catalogue',
                    'route' => '/attribute-catalogue',
                ],
                [
                    'title' => 'Attribute',
                    'route' => '/attribute',
                ],
            ],
        ],
    ],
];

