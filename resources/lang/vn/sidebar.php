<?php

return [
    'module' => [
        [
            'title' => 'Quản Lý Thành Viên',
            'icon' => 'fas fa-tachometer-alt mr-3',
            'name' => ['user', 'user-catalogue'],
            'sub_module' => [
                [
                    'title' => 'Quản Lý Nhóm Thành Viên',
                    'route' => '/user-catalogue',
                ],
                [
                    'title' => 'Quản Lý Thành Viên',
                    'route' => '/user',
                ],
                [
                    'title' => 'Quản Lý Quyền Hạn',
                    'route' => '/permission',
                ]
            ],
        ],
        [
            'title' => 'Quản Lý Bài Viết',
            'icon' => 'fas fa-sticky-note mr-3',
            'name' => ['post', 'post-catalogue'],
            'sub_module' => [
                [
                    'title' => 'Quản Lý Nhóm Bài Viết',
                    'route' => '/post-catalogue',
                ],
                [
                    'title' => 'Quản Lý Bài Viết',
                    'route' => '/post',
                ]
            ],
        ],
        [
            'title' => 'Cấu hình chung',
            'icon' => 'fas fa-sticky-note mr-3',
            'name' => ['language', 'generator'],
            'sub_module' => [
                [
                    'title' => 'Quản Lý Ngôn Ngữ',
                    'route' => '/language',
                ],
                [
                    'title' => 'Quản Lý Module',
                    'route' => '/generator',
                ],
            ],
        ],
        [
            'title' => 'Quản Lý Sản Phẩm',
            'icon' => 'fas fa-cube mr-3',
            'name' => ['product', 'product-catalogue', 'attribute', 'attribute-catalogue'],
            'sub_module' => [
                [
                    'title' => 'Quản Lý Nhóm Sản Phẩm',
                    'route' => '/product-catalogue',
                ],
                [
                    'title' => 'Quản Lý Sản Phẩm',
                    'route' => '/product',
                ],
                [
                    'title' => 'Quản Loại Thuộc Tính',
                    'route' => '/attribute-catalogue',
                ],
                [
                    'title' => 'Quản Thuộc Tính',
                    'route' => '/attribute',
                ],
            ],
        ],
        //@@new-sidebar@@
    ],
];
