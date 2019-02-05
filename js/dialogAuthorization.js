$(function() {
    let optionShared = {
        autoOpen: false,
        height: "auto",
        width: 350,
        modal: true,
        resizable: false,
        show: {
            effect: "drop",
            direction: "left",
            duration: 300
        },
        hide: {
            effect: "drop",
            direction: "right",
            duration: 300
        }
    };

    $("#authorization-form").dialog(Object.assign(optionShared, {
        open: function () {
            $("#authorization-form .validateTips").text("tips").css("visibility", "hidden");
        },
        close: function () {
            $("#authorization-form :input[type != button]").val("").removeClass("ui-state-error");
        }
    }));

    $("#registration-form").dialog(Object.assign(optionShared, {
        open: function () {
            $("#registration-form .validateTips").text("tips").css("visibility", "hidden");
        },
        close: function () {
            $("#registration-form :input[type != button]").val("");
        }
    }));
});