$(function () {
    function ReqData()
    {
        $(".loading").fadeIn();
        $.ajax({
            url: "../Data/FillApprove",
            async: false,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
                $("#approve").html(data);
                alert(data);
            }
        });
        $(".loading").fadeOut();
    }
    $("#approvesteps").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        stepsOrientation: "vertical",
        enableAllSteps: true,
        enableKeyNavigation: false
    });


});
