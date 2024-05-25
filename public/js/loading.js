var count = 0;
function loading() {
    count = count + 1;
    var html =
        '<div class="loader"><div class="loading-box"><i class="fa-solid fa-gear fa-spin"></i></div></div>';

    //Tambahkan dalam HTML Body
    if (count < 2) {
        $("body").prepend(html);
    }
    $(".loader").fadeIn();
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
