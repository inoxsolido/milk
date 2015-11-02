$(function(){
    //var
    var year = $("#monthdiv").attr("year");
    var round = $("#monthdiv").attr("round");
    //function
    function ReqData() {
        $(".loading").fadeIn();
        $.ajax({
            url: "../Data/FillMonthGoalDiv",
            async: false,
            type: 'POST',
            data: {
                year: year,
                round: round
            },
            success: function (data, textStatus, jqXHR) {
                $("#monthdiv").html(data);
            }
        });
        $(".loading").fadeOut();
    }
    
    //event
    $("#monthdiv").on("click", ".view", function(){
        $(".loading").fadeIn();
            //export to excel
        $(".loading").fadeOut();
    });
    //main
    ReqData();
});

