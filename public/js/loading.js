var count = 0;
function loading() {
    count = count + 1;
    var html =
        '<div class="loader"><div class="loading-box"><i class="fa-solid fa-gear fa-spin"></i></div></div>';

    //Tambahkan dalam HTML Body
    if (count < 2) {
        $("body").prepend(html);
    }
    $(".loader").fadeIn(100);
    $(".loader")
        .promise()
        .done(function () {
            $(".loading-box").addClass("active");
        });

    //Remove Loading
    removeLoading = function () {
        $(".loading-box").removeClass("active");
        $(".loading-box")
            .promise()
            .done(function () {
                $(".loader").fadeOut();
            });
    };
}
function BigLoading(text) {
    var alertLoading = $.alert({
        icon: "fas fa-gear fa-spin",
        title: "Loading",
        content: text,
        backgroundDismiss: false,
        escapeKey: false,
        buttons: {
            buttonA: {
                text: "Tunggu",
            },
        },
        onOpenBefore: function () {
            this.buttons.buttonA.hide();
        },
    });

    removeLoadingBig = function () {
        alertLoading.close();
    };
}
