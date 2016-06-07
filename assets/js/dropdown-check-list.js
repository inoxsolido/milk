function dropdown_checklist() {
    $(".dropdown-check-list").children(".anchor")
            .addClass("glyphicon-edit")
            .click(function () {
                if ($(this).parent().hasClass("visible")) {
                    $(this).parent().removeClass("visible");
                    $(this).addClass("glyphicon-edit");
                    $(this).removeClass("glyphicon-minus");
                }
                else {
                    $(this).parent().addClass("visible");
                    $(this).addClass("glyphicon-minus");
                    $(this).removeClass("glyphicon-edit");
                }
            });
}