$(function() {
    let optionShared = {
        autoOpen: false,
        height: "auto",
        width: 700,
        maxHeight: 350,
        modal: true,
        resizable: false,
    };

    $("#mini-cart").dialog(Object.assign(optionShared, {
        open: function () {
            //console.log('----------------open mini-cart');
        },
        close: function () {
            //console.log('----------------close mini-cart');
        }
    }));
});