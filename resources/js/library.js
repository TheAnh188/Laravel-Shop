(function ($) {
    "use strict";
    var App = {};
    var _token = $('meta[name="csrf-token"]').attr("content");

    App.setStatus = () => {
        $(document).on("change", ".status", function () {
            let option = {
                value: $(this).val(),
                modelId: $(this).attr("data-modelId"),
                model: $(this).attr("data-model"),
                field: $(this).attr("data-field"),
                _token: _token,
            };

            $.ajax({
                url: "/ajax/dashboard/setStatus",
                type: "POST",
                data: option,
                dataType: "json",
                success: function (res) {
                    console.log(option);
                    $('.status[data-modelId="' + option.modelId + '"]').val(
                        option.value == 1 ? 0 : 1
                    );
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log("Lỗi " + textStatus + " " + errorThrown);
                },
            });
        });
    };

    App.checkAll = () => {
        if ($("#check-all").length) {
            $(document).on("click", "#check-all", function () {
                let checkAllState = $(this).prop("checked");
                $(".checkbox-item").prop("checked", checkAllState);
                $(".checkbox-item").each(function () {
                    App.changeBackground($(this));
                });
            });
        }
    };

    App.checkItem = () => {
        if ($(".checkbox-item").length) {
            $(document).on("click", ".checkbox-item", function () {
                App.changeBackground($(this));
                App.allChecked();
            });
        }
    };

    App.checkPermission = () => {
        if ($(".checkbox-permission").length) {
            $(document).on("click", ".checkbox-permission", function () {
                App.changeBackground($(this));
            });
        }
    };

    App.changeBackground = (object) => {
        let checkItem = object.prop("checked");
        if (checkItem) {
            object.closest("tr").addClass("active-bg");
        } else {
            object.closest("tr").removeClass("active-bg");
        }
    };

    App.allChecked = () => {
        let allChecked =
            $(".checkbox-item:checked").length === $(".checkbox-item").length;
        $("#check-all").prop("checked", allChecked);
    };

    App.setStatusAll = () => {
        if ($(".setStatusAll").length) {
            $(document).on("click", ".setStatusAll", function () {
                let id = [];
                $(".checkbox-item").each(function () {
                    let checkBox = $(this);
                    if (checkBox.prop("checked")) {
                        id.push(checkBox.val());
                    }
                });

                let option = {
                    value: $(this).attr("data-value"),
                    model: $(this).attr("data-model"),
                    field: $(this).attr("data-field"),
                    id: id,
                    _token: _token,
                };

                $.ajax({
                    url: "/ajax/dashboard/setStatusAll",
                    type: "POST",
                    data: option,
                    dataType: "json",
                    success: function (res) {
                        console.log(res);
                        if (res.flag == true) {
                            $(".status").each(function () {
                                let modelId = $(this).attr("data-modelId"); // Lấy giá trị của thuộc tính data-modelId
                                if (id.includes(modelId)) {
                                    // Kiểm tra xem modelId có nằm trong mảng id không
                                    if (option.value == 1) {
                                        $(this).prop("checked", true); // Nếu có thì set checked thành true
                                        $(".status").val(1);
                                    } else if (option.value == 2) {
                                        $(this).prop("checked", false); // Nếu có thì set checked thành false                                            $('.status').val(0);
                                        $(".status").val(2);
                                    }
                                }
                            });
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log("Lỗi " + textStatus + " " + errorThrown);
                    },
                });
            });
        }
    };

    App.uploadImage = (object, type) => {
        // Khi nhấn vào image-target, kích hoạt click trên input file
        $(".image-target").click(function () {
            $("#image-file").click();
        });
        // Khi người dùng chọn file, cập nhật src của thẻ img
        $("#image-file").change(function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    $(".image-target").attr("src", e.target.result); // Cập nhật src của img
                    $("input[name=image]").each(function () {
                        $(this).val(e.target.result);
                    });
                };
                reader.readAsDataURL(file); // Đọc file dưới dạng URL
            }
        });

        if (localStorage.getItem("selectedImage")) {
            $(".image-target").attr(
                "src",
                localStorage.getItem("selectedImage")
            );
        }
    };

    App.uploadAlbum = () => {
        $(".album-target").click(function () {
            $("#album-file").click();
        });

        $("#album-file").change(function () {
            const file = this.files;
            if (this.files.length > 0) {
                $(".no-album").addClass("hidden");
                $(".selectedAlbum").removeClass("hidden");

                for (let i = 0; i < file.length; i++) {
                    const file = this.files[i];
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const result = e.target.result;
                        let html = "";
                        html += `<div class="image-in-album cursor-pointer w-[162px] h-[162px] bg-cover bg-center mx-0.5 my-0.5 text-right" style="background-image: url(${result})">`;
                        html +=
                            '<span class="delete-image-in-album inline-block bg-red-500 text-white px-1 py-0.5 rounded-md"><i class="fa-solid fa-xmark"></i></span>';
                        html += '<input hidden type="text" name="album[]" value="'+e.target.result+'">';
                        html += "</div>";
                        console.log(html);
                        $("#sortable").append(html);
                    };
                    reader.readAsDataURL(file); // Đọc file dưới dạng URL
                }
            }
            $('#album-file').val('');
        });
    };

    // App.removeBaseURL = () => {
    //     $("form").on("submit", function (event) {
    //         $("input[name=canonical]").val(
    //             $("input[name=canonical]").val().replace(base_url, "")
    //         );$("input[name=translated_canonical]").val(
    //             $("input[name=translated_canonical]").val().replace(base_url, "")
    //         );
    //     });
    // };

    App.deleteImage = () => {
        $(document).on('click', '.delete-image-in-album', function() {
            $(this).parents('.image-in-album').remove();
            if($('.image-in-album').length == 0) {
                $(".no-album").removeClass("hidden");
                $(".selectedAlbum").addClass("hidden");
            }
        });
    }

    App.format_number = () => {
        $(document).on('change keyup blur', '.int', function(){
            let _this = $(this)
            let value = _this.val()
            if(value === ''){
                $(this).val('0')
            }
            value = value.replace(/\./gi, "") //gi global ignore case
            _this.val(App.addPeriod(value))
            //kiem tra input co phai la so khong neu khong phai thi set value = 0
            if(isNaN(value)){
                _this.val('0')
            }
        })

        //Xoa so 0 o dau so vd 012 = 12
        $(document).on('keydown', '.int', function(e){
            let _this = $(this)
            let data = _this.val()
            if(data == 0){
                let unicode = e.keyCode || e.which;
                if(unicode != 190 || unicode != 110){
                    _this.val('')
                }
            }
        })
    }

    //them dau cham vao so
    App.addPeriod = (nStr) => {
        nStr = String(nStr);
        nStr = nStr.replace(/\./gi, "");
        let str ='';
        for (let i = nStr.length; i > 0; i -= 3){
            let a = ( (i-3) < 0 ) ? 0 : (i-3);
            str= nStr.slice(a,i) + '.' + str;
        }
        str= str.slice(0,str.length-1);
        return str;
    }

    $(function () {
        App.setStatus();
        App.checkAll();
        App.checkItem();
        App.allChecked();
        App.setStatusAll();
        App.uploadImage();
        // App.removeBaseURL();
        App.uploadAlbum();
        App.deleteImage();
        App.checkPermission();
        App.format_number();

    });
})($);
