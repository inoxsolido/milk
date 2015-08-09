$(function(){
    function ReqData()
    {
        $(".loading").fadeIn();
        $.ajax({
            url: "../Data/FillApprove",
            async: false,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
                $("#approve").html(data);
            }
        });
        $(".loading").fadeOut();
    }
});