function cAlert(type, title, content, reload, redirect) {
    if (type == "green") {
        var icon = "fa-solid fa-check";
    } else if (type == "orange" || type == "red" || type == "blue") {
        var icon = "fa-solid fa-triangle-exclamation";
    } else {
        var icon = "fa-solid fa-check";
    }
    if (reload === true) {
        $.alert({
            type: type,
            icon: icon,
            title: title,
            content: content,
            onDestroy: function () {
                location.reload();
            },
        });
    } else {
        $.alert({
            type: type,
            icon: icon,
            title: title,
            content: content,
            onDestroy: function () {
                window.location.href = redirect;
            },
        });
    }
}
function oAlert(type, title, content) {
    if (type == "green") {
        var icon = "fa-solid fa-check";
    } else if (type == "orange" || type == "red" || type == "blue") {
        var icon = "fa-solid fa-triangle-exclamation";
    } else {
        var icon = "fa-solid fa-check";
    }
    $.alert({
        type: type,
        icon: icon,
        title: title,
        content: content,
    });
}
function cConfirm(title, content, cFunction) {
    var icon = "fa-solid fa-triangle-exclamation";
    $.confirm({
        type: "orange",
        icon: icon,
        title: title,
        content: content,
        autoClose: "cancel|8000",
        buttons: {
            ok: {
                text: "Confirm",
                action: function () {
                    cFunction();
                },
            },
            cancel: {
                text: "Cancel",
            },
        },
    });
}
