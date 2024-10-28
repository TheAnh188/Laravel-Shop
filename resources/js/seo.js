(function ($) {
    "use strict";
    var App = {};

    App.seoPreview = () => {
        $("input[name=meta_title]").on("keyup", function () {
            let input = $(this);
            let value = input.val();
            $(".meta_title-preview").html(value);
        });

        $("input[name=canonical]").on("keyup", function () {
            let input = $(this);
            let value = App.removeUtf8(input.val().slice(base_url.length));
            $(".canonical-preview").html(base_url + value + suffix);
        });

        $("textarea[name=meta_description]").on("keyup", function () {
            let input = $(this);
            let value = input.val();
            $(".meta_description-preview").html(value);
        });
    };

    App.removeBaseURL = () => {
        $("form").on("submit", function (event) {
            $("input[name=canonical]").val(
                $("input[name=canonical]").val().replace(base_url, "")
            );
            $("input[name=canonical]").val(
                App.removeUtf8($("input[name=canonical]").val())
            );
            $("input[name=translated_canonical]").val(
                $("input[name=translated_canonical]").val().replace(base_url, "")
            );
            $("input[name=translated_canonical]").val(
                App.removeUtf8($("input[name=translated_canonical]").val())
            );
        });
    };

    App.translatedSeoPreview = () => {
        $("input[name=translated_meta_title]").on("keyup", function () {
            let input = $(this);
            let value = input.val();
            $(".translated_meta_title-preview").html(value);
        });

        $("input[name=translated_canonical]").on("keyup", function () {
            let input = $(this);
            let value = App.removeUtf8(input.val().slice(base_url.length));
            $(".translated_canonical-preview").html(base_url + value + suffix);
            // console.log(input.val().slice(base_url.length));
        });

        $("textarea[name=translated_meta_description]").on("keyup", function () {
            let input = $(this);
            let value = input.val();
            $(".translated_meta_description-preview").html(value);
        });
    };

    App.removeUtf8 = (str) => {
        str = str.toLowerCase(); // chuyen ve ki tu biet thuong
        str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
        str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
        str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
        str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
        str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
        str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
        str = str.replace(/đ/g, "d");
        str = str.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|,|\.|\:|\;|\'|\–| |\"|\&|\#|\[|\]|\\|\/|~|$|_/g, "-");
        str = str.replace(/-+-/g, "-");
        str = str.replace(/^\-+|\-+$/g, "");
        return str;
    }

    $(function () {
        App.seoPreview();
        App.translatedSeoPreview();
        App.removeBaseURL();
    });
})($);
