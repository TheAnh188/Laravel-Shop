(function ($) {
    "use strict";
    var App = {};

    App.setupProductVariants = () => {
        if ($(".setup-variant").length) {
            $(document).on("click", ".setup-variant", function () {
                let _this = $(this);
                let price = $('input[name=price]').val();
                let code = $('input[name=code]').val();
                if(price == '' || code == ''){
                    alert('Bạn chưa nhập giá bán và mã sản phẩm');
                    return false;
                }
                let checkItem = _this.prop("checked");
                if (!checkItem) {
                    $(".variant-info").addClass("hidden");
                } else {
                    $(".variant-info").removeClass("hidden");
                }
            });
        }
    };

    App.addVariant = () => {
        if ($(".add-variant").length) {
            $(document).on("click", ".add-variant", function () {
                let html = App.renderVariantItem(attribute_catalogues);
                $(".variant-info-body").append(html);
                $('.variantTable thead').html('');
                $('.variantTable tbody').html('');
                // Khởi tạo lại select2 cho các select mới được thêm vào
                $(".variant-info-body .select2").select2();
                App.checkQuantiyOfAttributeCatalogues(attribute_catalogues);
                App.filterAttributeCatalogue();
            });
        }
    };

    App.renderVariantItem = (attribute_catalogues) => {
        let html = `
        <div class="variant-item">
            <div class="relative z-0 w-full mb-2 group mt-5 ">
                <select name="attributeCatalogue[]"
                    class="select2 choose-attribute-catalogue bg-white text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white border-r-8 border-solid border-white">
                    <option class="" value="0">${choose_attribute}</option>`;

        attribute_catalogues.forEach((item, i) => {
            html += `<option class="" value="${item.id}">${item.name}</option>`;
        });

        html += `
                </select>
            </div>
            <div class="relative z-0 w-full group grid grid-cols-[90%,1fr] gap-6 mb-8">
                <div class="w-full">
                    <label for="attribute" class="text-sm text-[#1791f2]">${choose_attribute_value}</label>
                    <div class="disabled-attribute-input">
                        <input id="attribute" type="text" name="" disabled class="mt-2 w-full h-[36px] cursor-not-allowed bg-gray-200 rounded-lg">
                    </div>
                </div>
                <div class="w-full text-center flex items-end">
                    <button type="button" class="bg-red-500 text-white px-3 py-2.5 rounded-md mr-2 remove-attribute-catalogue">
                        <i class="fa-regular fa-trash-can"></i>
                    </button>
                </div>
            </div>
        </div>
        `;
        return html;
    };

    App.filterAttributeCatalogue = () => {
        let id = [];
        $(".choose-attribute-catalogue").each(function () {
            let _this = $(this);
            let selected = _this.find("option:selected").val();
            if (selected != 0) {
                id.push(selected); //them cac value vao mang cua the option neu value khac 0
            }
        });
        $(".choose-attribute-catalogue").find("option").removeAttr("disabled"); //xoa thuoc tinh disabled cac the option
        for (let i = 0; i < id.length; i++) {
            $(".choose-attribute-catalogue")
                .find("option[value=" + id[i] + "]")
                .prop("disabled", true); //them disabled vao nhung the option co value ton tai trong mang id
        }
        $(".choose-attribute-catalogue")
            .find("option:selected")
            .removeAttr("disabled"); //xoa thuoc tinh disabled cua the option selected
    };

    App.chooseAttributeCatalogue = () => {
        $(document).on("change", ".choose-attribute-catalogue", function () {
            let _this = $(this);
            let attributeCatalogueId = _this.val();

            // Tìm phần tử cha gần nhất là .variant-item để giới hạn phạm vi thay đổi
            let $variantItem = _this.closest(".variant-item");

            if (attributeCatalogueId != 0) {
                // Chỉ thay đổi phần tử .disabled-attribute-input trong phạm vi của $variantItem
                $variantItem
                    .find(".disabled-attribute-input")
                    .html(
                        App.setSelect2ToAttributeSelectTag(attributeCatalogueId)
                    );

                // Áp dụng select2 và gọi hàm getAttributes cho từng .selectVarinant trong phạm vi $variantItem
                $variantItem
                    .find(".selectVarinant")
                    .each(function (key, index) {
                        $(this).select2();
                        App.getAttributes($(this));
                    });
            } else {
                // Chỉ thay đổi nội dung của .disabled-attribute-input trong phạm vi của $variantItem
                $variantItem
                    .find(".disabled-attribute-input")
                    .html(
                        `<input id="attribute" type="text" name="attribute[${attributeCatalogueId}][]" disabled class="mt-2 w-full h-[36px] cursor-not-allowed bg-gray-200 rounded-lg">`
                    );
            }

            // Gọi filterAttributeCatalogue sau khi đã thay đổi xong phần tử cụ thể
            App.filterAttributeCatalogue();
        });
    };

    //gui ajax de lay attribute
    App.getAttributes = (object) => {
        let option = {
            attributeCatalogueId: object.attr("data-catid"),
        };

        $(object).select2({
            miniumInputLength: 0,
            placeholder: "Nhập tối thiểu 2 ký tự để tìm kiếm",
            ajax: {
                url: "/ajax/attribute/getAttribute",
                type: "GET",
                dataType: "json",
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term ?? "null",
                        option: option,
                    };
                },
                processResults: function (data) {
                    //cau truc tra ve cua ajax trong select2 la mot mang co id va text(da dc chuyen doi trong controller)
                    return {
                        results: $.map(data, function (obj, i) {
                            return obj;
                        }),
                    };
                },
                cache: false,
            },
        });
    };

    App.removeAttributeCatalogue = () => {
        $(document).on("click", ".remove-attribute-catalogue", function () {
            let _this = $(this);
            _this.parents(".variant-item").remove(); //xoa mot variant sau do hien thi lai nut them variant
            App.checkQuantiyOfAttributeCatalogues(attribute_catalogues);
            App.createVariant();
        });
    };

    App.checkQuantiyOfAttributeCatalogues = (attribute_catalogues) => {
        let quantity = $(".variant-item").length;
        if (quantity >= attribute_catalogues.length) {
            //xoa nut them variant khi da tao du so luong attribute catalogue
            $(".add-variant").remove();
        } else {
            $(".variant-button--wrapper").html(`<button type="button"
                                    class="add-variant border-2 px-5 py-2 rounded-md border-dashed border-[#1791f2] text-[#1791f2] transition-all hover:bg-gray-100">${add_new_variant}</button>`);
        }
    };

    App.setSelect2ToAttributeSelectTag = (attribute_catalogue_id) => {
        let html = `<select multiple data-catid="${attribute_catalogue_id}" name="attribute['${attribute_catalogue_id}'][]"
                                class="select2 selectVarinant variant-${attribute_catalogue_id} bg-white text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white border-r-8 border-solid border-white">
                            </select>`;
        return html;
    };

    App.createProductVariant = () => {
        $(document).on("change", ".selectVarinant", function () {
            let _this = $(this);
            App.createVariant();
        });
    };

    App.createVariant = () => {
        let attributes = []; //mang chua ten thuoc tinh
        let attributeIds = []; //mang chua id thuoc tinh
        let attributeTitles = [];
        $(".variant-item").each(function () {
            let _this = $(this);
            let tempAttr = [];
            let tempAttrId = [];
            let attributeCatalogueId = _this
                .find(".choose-attribute-catalogue")
                .val(); //lay value cua the option attribute catalogue
            let optionText = _this
                .find(".choose-attribute-catalogue option:selected")
                .text(); //lay text cua the option attribute catalogue
            let attrListPerAttrCatalogue = $(
                ".variant-" + attributeCatalogueId
            ).select2("data"); //lay tat ca cac attr cua moi attr catalogue
            if (attrListPerAttrCatalogue != null) {
                for (let i = 0; i < attrListPerAttrCatalogue.length; i++) {
                    let item = {};
                    let item2 = {};
                    item[optionText] = attrListPerAttrCatalogue[i].text; //gan Attr Catalogue = Attr(Mau sac = mau vang)
                    item2[attributeCatalogueId] =
                        attrListPerAttrCatalogue[i].id; //gan Attr Catalogue ID = Attr ID(Mau sac co id la 1(trong bang catalogue) mau vang co id la 1(trong bang attr)) object =  {1:1}
                    tempAttr.push(item);
                    tempAttrId.push(item2);
                }
                if (tempAttr.length > 0) {
                    // Chỉ thêm khi tempAttr không rỗng giup cho khi chuyen attributes thanh fomattedAttributes thi fomattedAttributes se khong bi rong
                    attributeTitles.push(optionText);
                    attributes.push(tempAttr);
                }
                if (tempAttrId.length > 0) {
                    attributeIds.push(tempAttrId);
                }
            }
        });
        let fomattedAttributes = [];
        if (attributes.length > 0) {
            //ket hop tat ca cac attr thanh cac doi tuong khac nhau VD:do,vang,xxl => {do,xxl} va {vang,xxl}
            fomattedAttributes = attributes.reduce((a, b) =>
                a.flatMap((d) => b.map((e) => ({ ...d, ...e })))
            );
        }
        let fomattedattributeIds = [];
        if (attributeIds.length > 0) {
            //tuong tu nhu tren
            fomattedattributeIds = attributeIds.reduce((a, b) =>
                a.flatMap((d) => b.map((e) => ({ ...d, ...e })))
            );
        }
        // let html = App.renderVariantTable(
        //     fomattedAttributes,
        //     attributeTitles,
        //     fomattedattributeIds
        // );
        // $("table.variantTable").html(html);

        App.createVariantTableHeader(fomattedAttributes, attributeTitles);

        let trClass = [];

        fomattedAttributes.forEach((attr, index) => {
            //attr = {Màu sắc: 'Màu đỏ', Chất liệu: 'Vải dù'}
            //fomattedattributeIds[index] = {3: '18', 4: '19'}
            let row = App.createVariantRow(attr, fomattedattributeIds[index]);
            let classModified = 'tr-variant-' + Object.values(fomattedattributeIds[index]).join(', ').replace(/, /g, '-');
            trClass.push(classModified);
            //nếu phiên bản chưa được thêm thì mới thêm và giữ lại các phiên bản đã chọn trước đó
            if(!$('table.variantTable tbody tr').hasClass(classModified)){
                $("table.variantTable tbody").append(row);
            }
        });
        // console.log(trClass);
        //vd nếu màu sắc có màu đỏ và màu vàng thì khi xóa màu đổ thì vẫn giữ được biến thể màu vàng
        $('table.variantTable tbody tr').each(function () {
            const row = $(this);
            const rowClasses = row.attr('class');
            if(rowClasses){
                const rowClassArray = rowClasses.split(' ');
                // console.log(rowClassArray);
                let remove = false;
                rowClassArray.forEach(rowClass => {
                    if(rowClass == 'variant-row'){
                        // console.log(rowClass);
                        return;
                    } else if(!trClass.includes(rowClass)){
                        remove = true;
                        // console.log(2);
                    }
                });
                if(remove){
                    row.remove();
                }
            }
        });
    };

    App.createVariantTableHeader = (attributes, attributeTitles) => {
        let thead = $("table.variantTable thead");
        if (attributes.length != 0) {
            let tr = $("<tr>"); // tạo thẻ tr
            tr.append(
                $("<th>", {
                    scope: "col",
                    class: "w-[15%] px-4 py-3 text-center",
                }).text("Hình ảnh")
            );// thêm thẻ th

            for (let i = 0; i < attributeTitles.length; i++) {
                tr.append(
                    $("<th>", {
                        scope: "col",
                        class: "px-4 py-3 text-center",
                    }).text(attributeTitles[i])
                );
            }// thêm thẻ th ứng với từng thuộc tính đã chọn

            tr.append(
                $("<th>", {
                    scope: "col",
                    class: "px-4 py-3 text-center",
                }).text("Số lượng")
            );
            tr.append(
                $("<th>", {
                    scope: "col",
                    class: "px-4 py-3 text-center",
                }).text("Giá tiền")
            );
            tr.append(
                $("<th>", {
                    scope: "col",
                    class: "px-4 py-3 text-center",
                }).text("SKU")
            );
            thead.html(tr);
        } else {
            thead.html('');
        }
        return thead;
    };

    App.createVariantRow = (attrObj, idObj) => {
        let attributeString = Object.values(attrObj).join(", ");// attributeString = Màu đỏ, Vải dù
        let attributeIdString = Object.values(idObj).join(", ");// attributeIdString = 18, 19
        let classModified = attributeIdString.replace(/, /g, '-');
        let row = $("<tr>").addClass('variant-row tr-variant-' + classModified);
        let td = $("<td>").addClass('px-4 py-4 text-center');
        td.append($('<img>').addClass('object-cover td-image').attr('src', '/images/no_image.jpg'));
        row.append(td);

        Object.values(attrObj).forEach(value => {
            td = $('<td>').text(value).addClass('px-4 py-4 text-center');
            row.append(td);
        });

        td = $('<td>').addClass('px-4 py-4 text-center td-variant hidden')
        let price = $('input[name=price]').val();
        let code = $('input[name=code]').val();
        let hiddenInputs = [
            {'name' : 'variant[quantity][]', 'class' : 'variant-quantity'},
            {'name' : 'variant[sku][]', 'class' : 'variant-sku', 'value' : code + '-' + classModified},
            {'name' : 'variant[price][]', 'class' : 'variant-price', 'value' : price},
            {'name' : 'variant[barcode][]', 'class' : 'variant-barcode'},
            {'name' : 'variant[filename][]', 'class' : 'variant-filename'},
            {'name' : 'variant[file_url][]', 'class' : 'variant-fileurl'},
            {'name' : 'variant[album][]', 'class' : 'variant-album'},
            {'name' : 'attribute_input[name][]', 'value' : attributeString},
            {'name' : 'attribute_input[id][]', 'value' : attributeIdString},
        ];

        $.each(hiddenInputs, function (_, value) {
            let input = $('<input>').attr('type', 'text').attr('name', value['name']).addClass(value['class']);
            if(value['value']){
                input.val(value['value']);
            }
            td.append(input);
        })

        row.append($('<td>').addClass('td-quantity px-4 py-4 text-center').text('-'));
        row.append($('<td>').addClass('td-price px-4 py-4 text-center').text(price));
        row.append($('<td>').addClass('td-sku px-4 py-4 text-center').text(code + '-' + classModified));
        row.append(td);

        return row;
    }

    // App.renderVariantTable = (attributes, attributeTitles, attributeIds) => {
    //     let html = "";
    //     if (attributes.length != 0) {
    //         html += `<thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
    //                             <tr>
    //                                 <th scope="col" class="w-[15%] px-4 py-3 text-center">Hình ảnh</th>`;
    //         for (let i = 0; i < attributeTitles.length; i++) {
    //             html += `<th scope="col" class="px-4 py-3 text-center">${attributeTitles[i]}</th>`;
    //         }
    //         html += `<th scope="col" class="px-4 py-3 text-center">Số lượng</th>
    //                                 <th scope="col" class="px-4 py-3 text-center">Giá tiền</th>
    //                                 <th scope="col" class="px-4 py-3 text-center">SKU</th>
    //                             </tr>
    //                         </thead>
    //                         <tbody>`;
    //         for (let j = 0; j < attributes.length; j++) {
    //             html += `<tr class="variant-row odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
    //                                 <td class="px-4 py-4 text-center">
    //                                         <img src="/images/no_image.jpg" alt="Image"
    //                                             class="object-cover td-image">
    //                                 </td>`;
    //             let attributeArray = [];
    //             let attributeIdArray = [];
    //             $.each(attributes[j], function (index, value) {
    //                 html += `<td class="px-4 py-4 text-center">${value}</td>`;
    //                 attributeArray.push(value);
    //             });
    //             $.each(attributeIds[j], function (index, value) {
    //                 attributeIdArray.push(value);
    //             });
    //             let attributeString = attributeArray.join(", ");
    //             let attributeIdString = attributeIdArray.join(", ");
    //             html += `<td class="td-quantity px-4 py-4 text-center">-</td>
    //                                 <td class="td-price px-4 py-4 text-center">-</td>
    //                                 <td class="td-sku px-4 py-4 text-center">-</td>
    //                                 <td hidden class="px-4 py-4 text-center td-variant">
    //                                     <input type="text" name="variant[quantity][]" class="variant-quantity">
    //                                     <input type="text" name="variant[sku][]" class="variant-sku">
    //                                     <input type="text" name="variant[price][]" class="variant-price">
    //                                     <input type="text" name="variant[barcode][]" class="variant-barcode">
    //                                     <input type="text" name="variant[filename][]" class="variant-filename">
    //                                     <input type="text" name="variant[file_url][]" class="variant-fileurl">
    //                                     <input type="text" name="variant[album][]" class="variant-album">
    //                                     <input type="text" name="attribute[name][]" value="${attributeString}">
    //                                     <input type="text" name="attribute[id][]" value="${attributeIdString}">
    //                                 </td>
    //                             </tr>`;
    //         }
    //         html += `</tbody>`;
    //     }
    //     return html;
    // };

    App.setupSelect2Multiple = (callback) => {//set lại select2 cho thẻ select chọn nhiều thuộc tính
        if($('.selectVarinant').length){
            let count = $('.selectVarinant').length;
            $('.selectVarinant').each(function () {
                let _this = $(this);
                let attributeCatalogueId = _this.attr('data-catid');
                if(attribute != ''){//attribute được tạo ở view create product
                    $.get('/ajax/attribute/loadAttribute', {
                        attribute: attribute,
                        attributeCatalogueId: attributeCatalogueId,
                    }, function(json){
                        if(json.data != 'undefined' && json.data.length){
                            for(let i = 0; i < json.data.length; i++){
                                var option = new Option(json.data[i].text, json.data[i].id, true, true);
                                _this.append(option).trigger('change');//tự động sinh các biến thể ở phái dưới(variant table)
                            }
                        }
                        //thực hiện hàm App.productVariant() sau khi gọi ajax để giữ các thuộc tính khi submit form lỗi
                        if(--count === 0 && callback){
                            callback();
                        }
                    })
                }
                App.getAttributes(_this)
            })
        }
    }

    App.productVariant = () => {
        variant = JSON.parse(atob(variant));
        $('.variant-row').each(function (index, value) {
            let _this = $(this);
            let hiddenInputs = [
                {'name' : 'variant[quantity][]', 'class' : 'variant-quantity', 'value' : variant['quantity'][index]},
                {'name' : 'variant[sku][]', 'class' : 'variant-sku', 'value' : variant['sku'][index]},
                {'name' : 'variant[price][]', 'class' : 'variant-price', 'value' : variant['price'][index]},
                {'name' : 'variant[barcode][]', 'class' : 'variant-barcode', 'value' : variant['barcode'][index]},
                {'name' : 'variant[filename][]', 'class' : 'variant-filename', 'value' : variant['filename'][index]},
                {'name' : 'variant[file_url][]', 'class' : 'variant-fileurl', 'value' : variant['file_url'][index]},
                {'name' : 'variant[album][]', 'class' : 'variant-album', 'value' : variant['album'][index]},
            ];

            for(let i = 0; i < hiddenInputs.length; i++){
                _this.find('.' + hiddenInputs[i]['class']).val(hiddenInputs[i]['value']);
            }

            let album = variant['album'][index]
                .split(/(?=data:image\/)/)
                .map((item) => item.replace(/,$/, ""));
            let image = (album[0].length) ? album[0] : '/images/no_image-removebg.png'
            _this.find('.td-quantity').html(variant['quantity'][index]);
            _this.find('.td-price').html(variant['price'][index]);
            _this.find('.td-sku').html(variant['sku'][index]);
            _this.find('.td-image').attr('src', image);
        })
    }

    App.switcher = () => {
        $(document).on("change", ".switcher", function () {
            let _this = $(this);
            let isChecked = _this.prop("checked");

            if (isChecked == true) {
                let target = _this.data("target");
                // Kiểm tra nếu là checkbox toggle1 thì xóa thuộc tính disabled cho input#quantity
                if (target === "variantQuantity") {
                    _this
                        .parents(".col-span-2")
                        .next(".col-span-5")
                        .find(".variant_quantity")
                        .removeAttr("disabled");
                }
                // Kiểm tra nếu là checkbox toggle2 thì xóa thuộc tính disabled cho input#file_name và input#file_url
                if (target === "disabled") {
                    _this
                        .parents(".col-span-2")
                        .next(".col-span-5")
                        .find(".variant_file_name")
                        .removeAttr("disabled");
                    _this
                        .parents(".col-span-2")
                        .next(".col-span-5")
                        .next(".col-span-5")
                        .find(".variant_file_url")
                        .removeAttr("disabled");
                }
            } else {
                let target = _this.data("target");
                // Nếu checkbox toggle1 bị tắt, thêm lại thuộc tính disabled cho input#quantity
                if (target === "variantQuantity") {
                    _this
                        .parents(".col-span-2")
                        .next(".col-span-5")
                        .find(".variant_quantity")
                        .attr("disabled", true);
                }
                // Nếu checkbox toggle2 bị tắt, thêm lại thuộc tính disabled cho input#file_name và input#file_url
                if (target === "disabled") {
                    _this
                        .parents(".col-span-2")
                        .next(".col-span-5")
                        .find(".variant_file_name")
                        .attr("disabled", true);
                    _this
                        .parents(".col-span-2")
                        .next(".col-span-5")
                        .next(".col-span-5")
                        .find(".variant_file_url")
                        .attr("disabled", true);
                }
            }
        });
    };

    App.uploadVariantAlbum = () => {
        $(document).on("click", ".variant-album-target", function () {
            let _this = $(this);
            if (_this.prev(".variant-album-file").length > 0) {
                _this.prev(".variant-album-file").click();
            } else {
                _this
                    .parents(".no-variant-album")
                    .first()
                    .prev(".flex")
                    .find(".variant-album-file")
                    .click();
            }
        });

        $(document).on("change", ".variant-album-file", function () {
            let _this = $(this);
            const file = this.files;
            if (this.files.length > 0) {
                _this
                    .parents(".w-full")
                    .first()
                    .find(".no-variant-album")
                    .addClass("hidden");
                _this
                    .parents(".w-full")
                    .first()
                    .find(".selectedVariantAlbum")
                    .removeClass("hidden");
                // $(".no-variant-album").addClass("hidden");
                // $(".selectedVariantAlbum").removeClass("hidden");

                for (let i = 0; i < file.length; i++) {
                    const file = this.files[i];
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const result = e.target.result;
                        let html = "";
                        html += `<div class="variant-image-in-album cursor-pointer w-[162px] h-[162px] bg-cover bg-center mx-0.5 my-0.5 text-right" style="background-image: url(${result})">`;
                        html +=
                            '<span class="delete-variant-image-in-album inline-block bg-red-500 text-white px-1 py-0.5 rounded-md"><i class="fa-solid fa-xmark"></i></span>';
                        html +=
                            '<input hidden type="text" name="variantAlbum[]" value="' +
                            e.target.result +
                            '">';
                        html += "</div>";
                        _this
                            .parents(".w-full")
                            .first()
                            .find(".selectedVariantAlbum")
                            .append(html);
                    };
                    reader.readAsDataURL(file); // Đọc file dưới dạng URL
                }
            }
            // $(".variant-album-file").val("");
            _this.val("");
            _this
                .parents(".w-full")
                .first()
                .find(".selectedVariantAlbum")
                .sortable();
        });
    };

    App.deleteVariantImage = () => {
        $(document).on("click", ".delete-variant-image-in-album", function () {
            let _this = $(this);
            console.log(
                _this
                    .parents(".variant-image-in-album")
                    .first()
                    .parents(".selectedVariantAlbum")
                    .first()
                    .children(".variant-image-in-album").length
            );
            if (
                _this
                    .parents(".variant-image-in-album")
                    .first()
                    .parents(".selectedVariantAlbum")
                    .first()
                    .children(".variant-image-in-album").length == 1
            ) {
                _this
                    .parents(".variant-image-in-album")
                    .first()
                    .parents(".selectedVariantAlbum")
                    .first()
                    .addClass("hidden");
                _this
                    .parents(".variant-image-in-album")
                    .first()
                    .parents(".selectedVariantAlbum")
                    .first()
                    .prev(".no-variant-album")
                    .removeClass("hidden");
            }
            $(this).parents(".variant-image-in-album").first().remove();
        });
    };

    App.updateVariant = () => {
        //lấy giá trị từ các hiddenInput để truyền vào phía sau .update-variant-tr bằng hàm App.renderVariant
        $(document).on("click", ".variant-row", function () {
            let _this = $(this);
            let variantData = {};
            _this
                .find(".td-variant input[type=text][class^='variant-']")
                .each(function () {
                    let className = $(this).attr("class");
                    variantData[className] = $(this).val();
                }); // ^ co nghia la bat dau bang vd variant-1 variant-2

            let html = App.renderVariant(variantData);
            if (_this.next(".update-variant-tr").length == 0) {
                _this.after(html);
            } else {
                _this.next(".update-variant-tr").remove();
            }
        });
    };

    let renderCount = 0;

    App.renderVariant = (variantData) => {
        renderCount++; // Tăng giá trị mỗi lần render
        let toggleClass1 = `toggle1-${renderCount}`;
        let toggleClass2 = `toggle2-${renderCount}`;

        // /(?=data:image\/)/ tách dựa trên chuỗi data:image/ nhưng không xóa nó do btcq (?=)
        // /,$/ $ đại diện cho kí tự cuối cùng
        let variantAlbum = variantData["variant-album"]; //lấy ra tất cả hình ảnh để nếu tất bảng cập nhật phiên bạn thì có thể lưu hình lại và hiển thị nếu mở lại bảng cập nhật
        if (variantAlbum.length > 0) {
            variantAlbum = variantAlbum
                .split(/(?=data:image\/)/)
                .map((item) => item.replace(/,$/, ""));
        }

        let html = `<tr class="update-variant-tr">
                            <td colspan="6">
                                <div class="updateVariant w-full bg-white rounded-sm p-3"
                                    style="box-shadow: 0px 0px 2px rgba(211, 211, 211, 1)">
                                    <div class="w-full flex items-center justify-between">
                                        <h5 class="uppercase text-gray-500">
                                            ${update_variant_info}</h5>
                                        <div class="button-group">
                                            <button type="button"
                                                class="bg-red-500 px-3 py-1 text-white cancel-btn">${cancel}</button>
                                            <button type="button"
                                                class="bg-cyan-600 px-3 py-1 text-white save-btn">${save}</button>
                                        </div>
                                    </div>
                                    <div class="mt-5">
                                        <div class="relative z-0 w-full mb-5 group">
                                            <div class="flex justify-between">
                                                <label for="variant-album-file"
                                                    class="inline-block mb-2 text-sm font-medium text-gray-900 dark:text-white">${album}</label>
                                                <div>
                                                    <input type="file" multiple name="variant-album-file"
                                                        id="variant-album-file"
                                                        class="variant-album-file hidden inline-block text-blue-700 mb-2 text-sm font-medium dark:text-white">
                                                    <span
                                                        class="variant-album-target cursor-pointer inline-block text-blue-700 mb-2 text-sm font-medium dark:text-white">${choose_album}</span>
                                                </div>
                                            </div>
                                            <div
                                                class="no-variant-album border-2 border-dashed border-blue-200 flex justify-center ${
                                                    variantAlbum.length > 0
                                                        ? "hidden"
                                                        : ""
                                                }"}>
                                                <img class="variant-album-target cursor-pointer w-[157px] h-[148px] object-cover"
                                                    src="/images/no_image-removebg.png" alt="">
                                            </div>
                                            <div class="selectedVariantAlbum sortable-variant ${
                                                variantAlbum.length > 0
                                                    ? ""
                                                    : "hidden"
                                            } w-full flex flex-wrap border-2 border-dashed border-blue-200"
                                                id="">`;
        for (let i = 0; i < variantAlbum.length; i++) {
            html += `<div class="variant-image-in-album cursor-pointer w-[162px] h-[162px] bg-cover bg-center mx-0.5 my-0.5 text-right" style="background-image: url(${variantAlbum[i]})">`;
            html +=
                '<span class="delete-variant-image-in-album inline-block bg-red-500 text-white px-1 py-0.5 rounded-md"><i class="fa-solid fa-xmark"></i></span>';
            html +=
                '<input hidden type="text" name="variantAlbum[]" value="' +
                variantAlbum[i] +
                '">';
            html += "</div>";
        }
        html += `</div>
                                        </div>
                                    </div>
                                    <div class="w-full grid grid-cols-12 gap-6">
                                        <div class="col-span-2">
                                            <label for="abc" class="text-[#1a0dab] font-semibold">Tồn kho</label>
                                            <label for="${toggleClass1}"
                                                class="flex items-center cursor-pointer select-none text-dark dark:text-white">
                                                <div class="relative">
                                                    <input data-target="variantQuantity" type="checkbox" id="${toggleClass1}"
                                                        class="switcher peer sr-only" ${
                                                            variantData[
                                                                "variant-quantity"
                                                            ] !== ""
                                                                ? "checked"
                                                                : ""
                                                        } />
                                                    <div
                                                        class="block h-8 rounded-full box bg-gray-200 dark:bg-cyan-500 w-14 peer-checked:bg-blue-500">
                                                    </div>
                                                    <div
                                                        class="absolute flex items-center justify-center w-6 h-6 transition bg-white rounded-full dot left-1 top-1 dark:bg-purple-400 peer-checked:translate-x-full peer-checked:dark:bg-green-400">
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                        <div class="col-span-5">
                                            <div class="grid grid-cols-2 gap-3">
                                                <div class="flex flex-col">
                                                    <label for="variant_quantity" class="text-[#1a0dab] font-semibold">Số
                                                        lượng</label>
                                                    <input id="variant_quantity" type="text"
                                                        class="int disabled variant_quantity px-2 py-1 border-2 rounded-[4px] border-gray-300 disabled:bg-gray-200"
                                                        name="variant_quantity" value="${
                                                            variantData[
                                                                "variant-quantity"
                                                            ]
                                                        }" ${
            variantData["variant-quantity"] !== "" ? "" : "disabled"
        }>
                                                </div>
                                                <div class="flex flex-col">
                                                    <label for="variant_sku" class="text-[#1a0dab] font-semibold">SKU</label>
                                                    <input id="variant_sku" type="text"
                                                        class="variant_sku px-2 py-1 border-2 rounded-[4px] border-gray-300"
                                                        name="variant_sku" value="${
                                                            variantData[
                                                                "variant-sku"
                                                            ]
                                                        }">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-span-5">
                                            <div class="grid grid-cols-2 gap-3">
                                                <div class="flex flex-col">
                                                    <label for="variant_price" class="text-[#1a0dab] font-semibold">Giá</label>
                                                    <input id="variant_price" type="text"
                                                        class="int px-2 py-1 variant_price border-2 rounded-[4px] border-gray-300"
                                                        name="variant_price" value="${
                                                            variantData[
                                                                "variant-price"
                                                            ]
                                                        }">
                                                </div>
                                                <div class="flex flex-col">
                                                    <label for="variant_barcode" class="text-[#1a0dab] font-semibold">Barcode</label>
                                                    <input id="variant_barcode" type="text"
                                                        class="px-2 py-1 variant_barcode border-2 rounded-[4px] border-gray-300"
                                                        name="variant_barcode" value="${
                                                            variantData[
                                                                "variant-barcode"
                                                            ]
                                                        }">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-full grid grid-cols-12 gap-6">
                                        <div class="flex flex-col col-span-2">
                                            <label for="aa" class="text-[#1a0dab] font-semibold">Quản Lý File</label>
                                            <label for="${toggleClass2}"
                                                class="flex items-center cursor-pointer select-none text-dark dark:text-white">
                                                <div class="relative">
                                                    <input type="checkbox" id="${toggleClass2}" class="switcher peer sr-only"
                                                        data-target="disabled" ${
                                                            variantData[
                                                                "variant-filename"
                                                            ] !== ""
                                                                ? "checked"
                                                                : ""
                                                        } />
                                                    <div
                                                        class="block h-8 rounded-full box bg-gray-200 dark:bg-cyan-500 w-14 peer-checked:bg-blue-500">
                                                    </div>
                                                    <div
                                                        class="absolute flex items-center justify-center w-6 h-6 transition bg-white rounded-full dot left-1 top-1 dark:bg-purple-400 peer-checked:translate-x-full peer-checked:dark:bg-green-400">
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                        <div class="flex flex-col w-full col-span-5">
                                            <label for="variant_file_name" class="text-[#1a0dab] font-semibold">Tên File</label>
                                            <input id="variant_file_name" type="text"
                                                class="disabled variant_file_name px-2 py-1 border-2 rounded-[4px] border-gray-300 disabled:bg-gray-200"
                                                name="variant_file_name" value="${
                                                    variantData[
                                                        "variant-filename"
                                                    ]
                                                }" ${
            variantData["variant-filename"] !== "" ? "" : "disabled"
        }>
                                        </div>
                                        <div class="flex flex-col w-full col-span-5">
                                            <label for="variant_file_url" class="text-[#1a0dab] font-semibold">Đường dẫn</label>
                                            <input id="variant_file_url" type="text"
                                                class="disabled variant_file_url px-2 py-1 border-2 rounded-[4px] border-gray-300 disabled:bg-gray-200"
                                                name="variant_file_url" value="${
                                                    variantData[
                                                        "variant-fileurl"
                                                    ]
                                                }" ${
            variantData["variant-filename"] !== "" ? "" : "disabled"
        }>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>`;
        return html;
    };

    App.cancelVariantUpdate = () => {
        $(document).on("click", ".cancel-btn", function () {
            let _this = $(this);
            _this.parents(".update-variant-tr").remove();
        });
    };

    App.saveVariantUpdate = () => {
        $(document).on("click", ".save-btn", function () {
            let _this = $(this);
            let variant = {
                quantity: _this
                    .parents(".updateVariant")
                    .find(".variant_quantity")
                    .val(),
                sku: _this.parents(".updateVariant").find(".variant_sku").val(),
                price: _this
                    .parents(".updateVariant")
                    .find(".variant_price")
                    .val(),
                barcode: _this
                    .parents(".updateVariant")
                    .find(".variant_barcode")
                    .val(),
                fileName: _this
                    .parents(".updateVariant")
                    .find(".variant_file_name")
                    .val(),
                fileURL: _this
                    .parents(".updateVariant")
                    .find(".variant_file_url")
                    .val(),
                album: _this
                    .parents(".w-full")
                    .first()
                    .next(".mt-5")
                    .find("input[name='variantAlbum[]']")
                    .map(function () {
                        return $(this).val();
                    })
                    .get(),
            };
            console.log(variant["album"]);
            $.each(variant, function (index, value) {
                //truyền giá trị vào ô input ẩn ở thẻ tr
                _this
                    .parents(".update-variant-tr")
                    .first()
                    .prev(".variant-row")
                    .find(".variant-" + index)
                    .val(value);
            });

            let option = {
                quantity: variant["quantity"],
                price: variant["price"],
                sku: variant["sku"],
            };
            //truyền giá trí vào thẻ tr
            $.each(option, function (index, value) {
                _this
                    .parents(".update-variant-tr")
                    .first()
                    .prev(".variant-row")
                    .find(".td-" + index)
                    .html(value);
            });
            //truyền ảnh đại diện vào thẻ tr
            _this
                .parents(".update-variant-tr")
                .first()
                .prev(".variant-row")
                .find(".td-image")
                .attr("src", variant["album"][0]);

            _this.parents(".update-variant-tr").remove();
        });
    };


    $(function () {
        App.setupProductVariants();
        App.addVariant();
        App.filterAttributeCatalogue();
        App.chooseAttributeCatalogue();
        App.removeAttributeCatalogue();
        App.createProductVariant();
        App.uploadVariantAlbum();
        App.deleteVariantImage();
        App.switcher();
        App.updateVariant();
        App.cancelVariantUpdate();
        App.saveVariantUpdate();
        App.setupSelect2Multiple(
            () => {
                App.productVariant();
            }
        );
    });
})($);
