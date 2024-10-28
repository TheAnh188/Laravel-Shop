import "./bootstrap";
import $ from "jquery";
window.$ = window.jQuery = $;

import("jquery-ui/dist/jquery-ui.js").then(() => {
    return import("jquery-ui/ui/widgets/mouse.js").then(() => {
        return import("jquery-ui/ui/widgets/sortable.js").then(() => {
            $(function () {
                $("#sortable").sortable();
            });
        });
    });
});

import select2 from "select2";
select2($);

import Alpine from "alpinejs";
import Intersect from "@alpinejs/intersect";
Alpine.plugin(Intersect);
window.Alpine = Alpine;
Alpine.start();

import {
    ClassicEditor,
    Essentials,
    Paragraph,
    Bold,
    Italic,
    Font,
    Image,
    ImageToolbar,
    ImageCaption,
    ImageStyle,
    ImageResize,
    ImageUpload,
    SourceEditing,
    Base64UploadAdapter,
} from "ckeditor5";

document.querySelectorAll(".editor").forEach((editorElement) => {
    ClassicEditor.create(editorElement, {
        plugins: [
            Essentials,
            Paragraph,
            Bold,
            Italic,
            Font,
            Image,
            ImageToolbar,
            ImageCaption,
            ImageStyle,
            ImageResize,
            ImageUpload,
            SourceEditing,
            Base64UploadAdapter,
        ],
        toolbar: [
            "undo",
            "redo",
            "|",
            "bold",
            "italic",
            "|",
            "fontSize",
            "fontFamily",
            "fontColor",
            "fontBackgroundColor",
            "|",
            "imageUpload",
            "|",
            "sourceEditing",
        ],
        image: {
            toolbar: [
                //     'imageStyle:full', 'imageStyle:side', '|',
                //     'imageTextAlternative', '|',
                //     'imageResize:50', 'imageResize:75', 'imageResize:original'
            ],
        },
        ckfinder: {
            // uploadUrl: 'public/images',
        },
    })
        .then((editor) => {
            window.editor = editor;
        })
        .catch((error) => {
            console.error(error);
        });
});

document.querySelectorAll(".editor--disabled").forEach((editorElement) => {
    ClassicEditor.create(editorElement, {
        plugins: [
            Essentials,
            Paragraph,
            Bold,
            Italic,
            Font,
            Image,
            ImageToolbar,
            ImageCaption,
            ImageStyle,
            ImageResize,
            ImageUpload,
            SourceEditing,
            Base64UploadAdapter,
        ],
        toolbar: [
            "undo",
            "redo",
            "|",
            "bold",
            "italic",
            "|",
            "fontSize",
            "fontFamily",
            "fontColor",
            "fontBackgroundColor",
            "|",
            "imageUpload",
            "|",
            "sourceEditing",
        ],
        image: {
            toolbar: [
                //     'imageStyle:full', 'imageStyle:side', '|',
                //     'imageTextAlternative', '|',
                //     'imageResize:50', 'imageResize:75', 'imageResize:original'
            ],
        },
        ckfinder: {
            // uploadUrl: 'public/images',
        },
    })
        .then((editor) => {
            editor.enableReadOnlyMode('.editor--disabled');
        })
        .catch((error) => {
            console.error(error);
        });
});

