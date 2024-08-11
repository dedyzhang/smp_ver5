$(document).ready(function () {
    //untuk icon tanda panah kanan animasi di menu
    $(".menu-title").click(function () {
        if ($(this).attr("aria-expanded") == "true") {
            $(this).find(".indicator-icon").addClass("fa-rotate-90");
        } else {
            $(this).find(".indicator-icon").removeClass("fa-rotate-90");
        }
    });
    $(".sidebar").mCustomScrollbar({
        theme: "minimal-dark",
        scrollbarPosition: "inside",
        mouseWheel: { scrollAmount: 3000 },
    });

    //Untuk Dropdown Navbar
    $(".has-dropdown").click(function (e) {
        e.preventDefault();
        $(".c-contain").css({
            visibility: "hidden",
            opacity: 0,
        });
        var dropTarget = $(this).data("target");
        var dropWidth = $(this).data("width");
        $(dropTarget).css("width", dropWidth + "px");
        if ($(dropTarget).hasClass("show")) {
            $(dropTarget)
                .removeClass("show")
                .css("visibility", "hidden")
                .animate(
                    {
                        bottom: "-20%",
                        opacity: "0",
                    },
                    150
                );
        } else {
            $(dropTarget).addClass("show").css("visibility", "visible").animate(
                {
                    opacity: "1",
                    bottom: "-50%",
                },
                150
            );
        }
    });

    //Untuk Sidebar
    $(".open-sidebar").click(function (event) {
        event.preventDefault();
        var sidebar = $(".sidebar");
        if ($(sidebar).hasClass("show")) {
        } else {
            $(sidebar).addClass("show").css("visibility", "visible").animate(
                {
                    opacity: "1",
                    left: 0,
                },
                250
            );
            $(".blackdrop").css("visibility", "visible").animate(
                {
                    opacity: "0.4",
                },
                250
            );
        }

        $(".blackdrop").click(function () {
            $(sidebar).removeClass("show").css("visibility", "hidden").animate(
                {
                    opacity: "0",
                    left: "-70%",
                },
                250
            );
            $(".blackdrop").css("visibility", "hidden").animate(
                {
                    opacity: "0",
                },
                250
            );
        });
    });

    //Untuk Tooltip Bootstrap
    const tooltipTriggerList = document.querySelectorAll(
        '[data-bs-toggle="tooltip"]'
    );
    const tooltipList = [...tooltipTriggerList].map(
        (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
    );

    //Moment Formater locale time
    moment.locale("id");

    //Untuk Mask Handphone
    $(".no-handphone-formatter").mask("0000 0000 00000");

    //Contenteditable Select All
    jQuery.fn.selectText = function () {
        var doc = document;
        var element = this[0];
        if (doc.body.createTextRange) {
            var range = document.body.createTextRange();
            range.moveToElementText(element);
            range.select();
        } else if (window.getSelection) {
            var selection = window.getSelection();
            var range = document.createRange();
            range.selectNodeContents(element);
            selection.removeAllRanges();
            selection.addRange(range);
        }
    };
    //Install select2
    $('select[data-toggle="select"]').select2({
        theme: "bootstrap-5",
        selectionCssClass: "select2--small",
        dropdownCssClass: "select2--small",
    });
    //File Input Plugins installation
    $(".file-input").fileinput({
        showUpload: false,
        maxFileSize: 20000,
        allowedFileExtensions: ["pdf"],
    });
    //Tinymce Installation
    tinymce.init({
        selector: ".tinymce-select",
        external_plugins: {
            tiny_mce_wiris:
                "https://cdn.jsdelivr.net/npm/@wiris/mathtype-tinymce6@8.10.0/plugin.min.js",
        },
        plugins:
            "anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount",
        toolbar1:
            "undo redo | blocks fontfamily fontsize | bold italic underline strikethrough  tiny_mce_wiris_formulaEditor tiny_mce_wiris_formulaEditorChemistry removeformat",
        toolbar2:
            "link image media table mergetags | hecklist numlist bullist indent outdent | align lineheight",
    });
    //Get Key by value
});
