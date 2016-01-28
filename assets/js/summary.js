$(function(){
    $("#exyear").on("click change", function(){
        var year = $(this).val();
        var wh = $("#exwh").val();
        var type = $("#extype :checked").val();
        if(wh == "month_goal")
        $.ajax({
            url: "../Data/FillDivInYear",
            type: 'POST',
            async: false,
            data:{
                year:year,
                wh:wh,
                tartype:type
            },success: function (data, textStatus, jqXHR) {
                $("#extar").html(data);
                $("#exgtar").show();
            }
        });
    });
    $("#btnExport").on("click change", function(){
        var wh = $("#exwh").val();
        var year = $("#exyear").val();
        var type = $("#extype :checked").val();
        var tar = $("#extar").val();
    });
});

