<?php

return [
    'post_catalogue' => [
        'index' => [
            'title' => 'Quản lý nhóm bài viết',
            'table' => 'Danh sách nhóm bài viết',
            'name' => 'Tên nhóm bài viết',
        ],
        'create' => [
            'title' => 'Thêm mới nhóm bài viết',
        ],
        'edit' => [
            'title' => 'Cập nhật nhóm bài viết',
        ],
        'delete' => [
            'title' => 'Xóa nhóm bài viết',
        ],

    ],
    'post' => [
        'index' => [
            'title' => 'Quản lý bài viết',
            'table' => 'Danh sách bài viết',
            'name' => 'Tên bài viết',
        ],
        'create' => [
            'title' => 'Thêm mới bài viết',
        ],
        'edit' => [
            'title' => 'Cập nhật bài viết',
        ],
        'delete' => [
            'title' => 'Xóa bài viết',
        ],
    ],
    'user' => [
        'index' => [
            'title' => 'Quản lý thành viên',
            'table' => 'Danh sách thành viên',
            'name' => 'Họ tên',
            'email' => 'Email',
            'avatar' => 'Ảnh đại diện',
            'phone_number' => 'Số điện thoại',
            'address' => 'Địa chỉ',
            'user_group' => 'Nhóm thành viên',
        ],
        'create' => [
            'title' => 'Thêm mới thành viên',
        ],
        'edit' => [
            'title' => 'Cập nhật thành viên',
        ],
        'delete' => [
            'title' => 'Xóa thành viên',
        ],
        'user_catalogue' => ['Chọn Nhóm Thành Viên', 'Quản trị viên', 'Cộng tác viên'],
        'store' => [
            'password' => 'Mật khẩu',
            'retyped_password' => 'Nhập lại mật khẩu',
            'province' => 'Chọn Thành Phố',
            'district' => 'Chọn Quận/Huyện',
            'ward' => 'Chọn Phường/Xã',
            'description' => 'Mô tả',
            'dob' => 'Ngày sinh'
        ],
    ],
    'user_catalogue' => [
        'index' => [
            'title' => 'Quản lý nhóm thành viên',
            'table' => 'Danh sách nhóm thành viên',
            'name' => 'Tên nhóm thành viên',
            'member_quantity' => 'Số thành viên',
            'description' => 'Mô tả'
        ],
        'create' => [
            'title' => 'Thêm mới nhóm thành viên',
        ],
        'edit' => [
            'title' => 'Cập nhật nhóm thành viên',
        ],
        'delete' => [
            'title' => 'Xóa nhóm thành viên',
        ],
        'permission' => [
            'title' => 'Cấp quyền',
        ],
    ],
    'language' => [
        'index' => [
            'title' => 'Quản lý ngôn ngữ',
            'table' => 'Danh sách ngôn ngữ',
            'name' => 'Tên ngôn ngữ',
            'locale' => 'Từ khóa',
            'image' => 'Hình ảnh',
        ],
        'create' => [
            'title' => 'Thêm mới ngôn ngữ',
        ],
        'edit' => [
            'title' => 'Cập nhật ngôn ngữ',
        ],
        'delete' => [
            'title' => 'Xóa ngôn ngữ',
        ],
    ],
    'permission' => [
        'index' => [
            'title' => 'Quản lý quyền',
            'table' => 'Danh sách quyền',
            'name' => 'Tên quyền',
            'canonical' => 'Đường dẫn',
        ],
        'create' => [
            'title' => 'Thêm mới quyền',
        ],
        'edit' => [
            'title' => 'Cập nhật quyền',
        ],
        'delete' => [
            'title' => 'Xóa quyền',
        ],
    ],
    'generator' => [
        'index' => [
            'title' => 'Quản lý module',
            'table' => 'Danh sách module',
            'name' => 'Tên Model',
            'schema' => 'Schema',
            'display_name' => 'Tên hiển thị',
        ],
        'create' => [
            'title' => 'Thêm mới module',
        ],
        'edit' => [
            'title' => 'Cập nhật module',
        ],
        'delete' => [
            'title' => 'Xóa module',
        ],
    ],
    'product_catalogue' => [
        'index' => [
            'title' => 'Quản lý nhóm sản phẩm',
            'table' => 'Danh sách nhóm sản phẩm',
            'name' => 'Tên nhóm sản phẩm',
        ],
        'create' => [
            'title' => 'Thêm mới nhóm sản phẩm',
        ],
        'edit' => [
            'title' => 'Cập nhật nhóm sản phẩm',
        ],
        'delete' => [
            'title' => 'Xóa nhóm sản phẩm',
        ],

    ],
    'product' => [
        'index' => [
            'title' => 'Quản lý sản phẩm',
            'table' => 'Danh sách sản phẩm',
            'name' => 'Tên sản phẩm',
            'code' => 'Mã sản phẩm',
            'made_in' => 'Xuất xứ',
            'price' => 'Giá bán',
        ],
        'create' => [
            'title' => 'Thêm mới sản phẩm',
            'variant' => [
                "product_with_multiple_versions" => "Sản phẩm có nhiều biến thể",
                "variant_description" => "Cho phép bạn bán các biến thể khác nhau của sản phẩm. ",
                "example" => "VD: ",
                "clothing" => "quần áo có các loại ",
                "color" => "màu sắc",
                "or" => " hoăc ",
                "size" => "kích cỡ. ",
                "variant_list_description" => "Mỗi biến thể sẽ là 1 dòng trong mục danh sách biến thể phía dưới",
                "allow_variants" => "Cho phép sản phẩm này có nhiều biến thể ?",
                "choose_attribute" => "Chọn thuộc tính",
                "choose_attribute_value" => "Chọn giá trị của thuộc tính",
                "add_new_variant" => "Thêm biến thể mới",
                'variants' => "Các biến thể",
                'update_variant_info' => 'Cập nhật thông tin biến thể',
                'cancel' => 'Hủy bỏ',
                'save' => 'Lưu',
            ],
        ],
        'edit' => [
            'title' => 'Cập nhật sản phẩm',
        ],
        'delete' => [
            'title' => 'Xóa sản phẩm',
        ],
    ],
    'attribute_catalogue' => [
        'index' => [
            'title' => 'Quản lý nhóm thuộc tính',
            'table' => 'Danh sách nhóm thuộc tính',
            'name' => 'Tên nhóm thuộc tính',
        ],
        'create' => [
            'title' => 'Thêm mới nhóm thuộc tính',
        ],
        'edit' => [
            'title' => 'Cập nhật nhóm thuộc tính',
        ],
        'delete' => [
            'title' => 'Xóa nhóm thuộc tính',
        ],

    ],
    'attribute' => [
        'index' => [
            'title' => 'Quản lý thuộc tính',
            'table' => 'Danh sách thuộc tính',
            'name' => 'Tên thuộc tính',
        ],
        'create' => [
            'title' => 'Thêm mới thuộc tính',
        ],
        'edit' => [
            'title' => 'Cập nhật thuộc tính',
        ],
        'delete' => [
            'title' => 'Xóa thuộc tính',
        ],
    ],
    'notice' => [
        'store' => 'Nhập đầy đủ thông tin',
        'sub_store' => 'Lưu ý: Các trường đánh dấu (*) là bắt buộc',
        'delete' => 'Bạn có muốn xóa : ',
        'sub_delete' => 'Lưu ý: Không thể khôi phục sau khi xóa!',
    ],
    'order' => 'Vị trí',
    'perpage' => 'bản ghi',
    'search' => 'Nhập thông tin...',
    'status' => 'Tình trạng',
    'action' => 'Thao tác',
    'description' => 'Mô tả',
    'content' => 'Nội dung',
    'canonical' => 'Đường dẫn',
    'seo' => 'Cấu hình SEO',
    'meta_title-preview' => 'Tiêu đề SEO',
    'canonical-preview' => 'http://127.0.0.1:8000/',
    'meta_description-preview' => 'Mô tả trang web ...',
    'meta_title' => 'Tiêu đề SEO',
    'meta_keyword' => 'Từ khóa SEO',
    'meta_description' => 'Mô tả SEO',
    'image' => 'Hình ảnh',
    'album' => 'Bộ sưu tập',
    'choose_album' => 'Chọn hình ảnh',
    'create' => 'Tạo mới',
    'update' => 'Cập nhật',
    'delete' => 'Xóa',
    'character' => 'Kí tự',
    'status_input' => [
        '0' => 'Chọn Tình Trạng',
        '1' => 'Đã kích hoạt',
        '2' => 'Vô hiệu hóa',
    ],
    'follow_input' => [
        '0' => 'Chọn Điều Hướng',
        '1' => 'Follow',
        '2' => 'No Follow',
    ],
    'unauthorized_action' => 'Tài khoản không có quyền truy cập.'
];
