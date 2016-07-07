function dropdown_checklist() {
    $(".dropdown-check-list").children(".anchor").off("click");
    
    $(".dropdown-check-list").children(".anchor")
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
    if(!$(".dropdown-check-list anchor").hasClass("glyphicon-minus"))
        $(".dropdown-check-list anchor").addClass("glyphicon-edit");
    
}