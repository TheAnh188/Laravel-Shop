<?php

namespace App\Classes;

class System
{
    public function config()
    {
        $data['homepage'] = [
            'label' => 'Thông tin chung',
            'description' => 'Cài đặt đầy đủ thông tin chung của website. Tên thương hiệu website, Logo, Favicon, vv...',
            'value' => [
                'company' => ['type' => 'text', 'label' => 'Tên công ty'],
                'brand' => ['type' => 'text', 'label' => 'Tên thương hiệu'],
                'slogan' => ['type' => 'text', 'label' => 'Slogan'],
                'logo' => ['type' => 'images', 'label' => 'Logo Website', 'title' => 'Click vào ô phía dưới để tải ảnh logo'],
                'favicon' => ['type' => 'favicon', 'label' => 'Favicon', 'title' => 'Click vào ô phía dưới để tải ảnh logo'],
                'copyright' => ['type' => 'text', 'label' => 'Copyright'],
                'website' => [
                    'type' => 'select',
                    'label' => 'Tình trạng website',
                    'option' => [
                        'open' => 'Mở cửa website',
                        'close' => 'Website đang bảo trì',
                    ]
                ],
                'short_intro' => ['type' => 'editor', 'label' => 'Giới thiệu ngắn'],

            ],
        ];

        $data['contact'] = [
            'label' => 'Thông tin liên hệ',
            'description' => 'Cài đặt thông tin liên hệ của website',
            'value' => [
                'office' => ['type' => 'text', 'label' => 'Văn phòng giao dịch'],
                'address' => ['type' => 'text', 'label' => 'Địa chỉ'],
                'hotline' => ['type' => 'text', 'label' => 'Hotline'],
                'technical_hotline' => ['type' => 'text', 'label' => 'Hotline kỹ thuật'],
                'business_hotline' => ['type' => 'text', 'label' => 'Hotline kinh doanh'],
                'phone_number' => ['type' => 'text', 'label' => 'Số điện thoại'],
                'fax' => ['type' => 'text', 'label' => 'Fax'],
                'email' => ['type' => 'text', 'label' => 'Email'],
                'tax' => ['type' => 'text', 'label' => 'Mã số thuế'],
                'website' => ['type' => 'text', 'label' => 'Website'],
                'map' => [
                    'type' => 'textarea',
                    'label' => 'Bản đồ',
                    'link' => [
                        'text' => 'Hướng dẫn thiết lập bản đồ',
                        'href' => '#'
                    ]
                ],
            ],
        ];

        $data['seo'] = [
            'label' => 'Cấu hình SEO danh cho trang chủ',
            'description' => 'Cài đặt đầy đủ thông tin SEO của website',
            'value' => [
                'meta_title' => ['type' => 'text', 'label' => 'Tiêu đề SEO'],
                'meta_keyword' => ['type' => 'text', 'label' => 'Từ khóa SEO'],
                'meta_description' => ['type' => 'text', 'label' => 'Mô tả SEO'],
                'meta_image' => ['type' => 'images', 'label' => 'Ảnh SEO'],
            ],
        ];

        return $data;
    }
}
