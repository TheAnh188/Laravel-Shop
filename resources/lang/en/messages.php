<?php

return [
    'post_catalogue' => [
        'index' => [
            'title' => 'Post Catalogue Management',
            'table' => 'Post Catalogue List',
            'name' => 'Name',
        ],
        'create' => [
            'title' => 'Add New Post Catalogue',
        ],
        'edit' => [
            'title' => 'Update Post Catalogue',
        ],
        'delete' => [
            'title' => 'Delete Post Catalogue',
        ],
    ],
    'post' => [
        'index' => [
            'title' => 'Post Management',
            'table' => 'Post List',
            'name' => 'Name',
        ],
        'create' => [
            'title' => 'Add New Post',
        ],
        'edit' => [
            'title' => 'Update Post',
        ],
        'delete' => [
            'title' => 'Delete Post',
        ],
    ],
    'user' => [
        'index' => [
            'title' => 'User Management',
            'table' => 'User List',
            'name' => 'Name',
            'email' => 'Email',
            'avatar' => 'Avatar',
            'phone_number' => 'Phone number',
            'address' => 'Address',
            'user_group' => 'User Group',
        ],
        'create' => [
            'title' => 'Add New User',
        ],
        'edit' => [
            'title' => 'Update User',
        ],
        'delete' => [
            'title' => 'Delete User',
        ],
        'user_catalogue' => ['Select User Group', 'Administrator', 'Collaborator'],
        'store' => [
            'password' => 'Password',
            'retyped_password' => 'Retyped password',
            'province' => 'Select Province',
            'district' => 'Select District',
            'ward' => 'Select Ward',
            'description' => 'Description',
            'dob' => 'Date of birth',
        ]
    ],
    'user_catalogue' => [
        'index' => [
            'title' => 'User Catalogue Management',
            'table' => 'User Catalogue List',
            'name' => 'Name',
            'member_quantity' => 'Members',
            'description' => 'Description',
        ],
        'create' => [
            'title' => 'Add New User Catalogue',
        ],
        'edit' => [
            'title' => 'Update User Catalogue',
        ],
        'delete' => [
            'title' => 'Delete User Catalogue',
        ],
        'permission' => [
            'title' => 'Grant Permission',
        ],
    ],
    'language' => [
        'index' => [
            'title' => 'Language Management',
            'table' => 'Language List',
            'name' => 'Name',
            'locale' => 'Locale',
            'image' => 'Image',
        ],
        'create' => [
            'title' => 'Add New Language',
        ],
        'edit' => [
            'title' => 'Update Language',
        ],
        'delete' => [
            'title' => 'Delete Language',
        ],
    ],
    'permission' => [
        'index' => [
            'title' => 'Permission Management',
            'table' => 'Permission List',
            'name' => 'Name',
            'canonical' => 'Canonical',
        ],
        'create' => [
            'title' => 'Add New Permission',
        ],
        'edit' => [
            'title' => 'Update Permission',
        ],
        'delete' => [
            'title' => 'Delete Permission',
        ],
    ],
    'generator' => [
        'index' => [
            'title' => 'Module Management',
            'table' => 'Module List',
            'name' => 'Model Name',
            'schema' => 'Schema',
            'display_name' => 'Display Name',
        ],
        'create' => [
            'title' => 'Add New Module',
        ],
        'edit' => [
            'title' => 'Update Module',
        ],
        'delete' => [
            'title' => 'Delete Module',
        ],
    ],
    'product_catalogue' => [
        'index' => [
            'title' => 'Product Catalogue Management',
            'table' => 'Product Catalogue List',
            'name' => 'Name',
        ],
        'create' => [
            'title' => 'Add New Product Catalogue',
        ],
        'edit' => [
            'title' => 'Update Product Catalogue',
        ],
        'delete' => [
            'title' => 'Delete Product Catalogue',
        ],
    ],
    'product' => [
        'index' => [
            'title' => 'Product Management',
            'table' => 'Product List',
            'name' => 'Name',
            'code' => 'Product Code',
            'made_in' => 'Made In',
            'price' => 'Price',
        ],
        'create' => [
            'title' => 'Add New Product',
            'variant' => [
                "product_with_multiple_versions" => "Products with multiple variants",
                "variant_description" => "Allows you to sell different variations of the product. ",
                "example" => "e.g. ",
                "clothing" => "clothing might have ",
                "color" => "different colors",
                "or" => " or ",
                "size" => "different sizes. ",
                "variant_list_description" => "Each variant will be a line in the variant list below.",
                "allow_variants" => "Allow this product to have multiple variants?",
                "choose_attribute" => "Choose an attribute",
                "material" => "Material",
                "choose_attribute_value" => "Choose the value of the attribute",
                "add_new_variant" => "Add new variant",
                'variants' => "Variants",
                'update_variant_info' => 'Update variant info',
                'cancel' => 'Cancel',
                'save' => 'Save',
            ],
        ],
        'edit' => [
            'title' => 'Update Product',
        ],
        'delete' => [
            'title' => 'Delete Product',
        ],
    ],
    'attribute_catalogue' => [
        'index' => [
            'title' => 'Attribute Catalogue Management',
            'table' => 'Attribute Catalogue List',
            'name' => 'Name',
        ],
        'create' => [
            'title' => 'Add New Attribute Catalogue',
        ],
        'edit' => [
            'title' => 'Update Attribute Catalogue',
        ],
        'delete' => [
            'title' => 'Delete Attribute Catalogue',
        ],
    ],
    'attribute' => [
        'index' => [
            'title' => 'Attribute Management',
            'table' => 'Attribute List',
            'name' => 'Name',
        ],
        'create' => [
            'title' => 'Add New Attribute',
        ],
        'edit' => [
            'title' => 'Update Attribute',
        ],
        'delete' => [
            'title' => 'Delete Attribute',
        ],
    ],
    'notice' => [
        'store' => 'Enter all member group information',
        'sub_store' => 'Note: Fields marked with (*) are required',
        'delete' => 'Do you want to delete the post catalogue: ',
        'sub_delete' => 'Note: Members cannot be restored after deletion!',
    ],
    'order' => 'Order',
    'perpage' => 'records',
    'search' => 'Enter information...',
    'status' => 'Status',
    'action' => 'Actions',
    'description' => 'Description',
    'content' => 'Content',
    'canonical' => 'Canonical',
    'seo' => 'SEO Configuration',
    'meta_title-preview' => 'SEO Title',
    'canonical-preview' => 'http://127.0.0.1:8000/',
    'meta_description-preview' => 'Website Description...',
    'meta_title' => 'SEO Title',
    'meta_keyword' => 'SEO Keywords',
    'meta_description' => 'SEO Description',
    'image' => 'Image',
    'album' => 'Album',
    'choose_album' => 'Choose Image',
    'create' => 'Create',
    'update' => 'Update',
    'delete' => 'Delete',
    'character' => 'Characters',
    'status_input' => [
        '0' => 'Select Status',
        '1' => 'Activated',
        '2' => 'Disabled',
    ],
    'follow_input' => [
        '0' => 'Select Navigation',
        '1' => 'Follow',
        '2' => 'No Follow',
    ],
    'unauthorized_action' => 'This action is unauthorized.'
];
