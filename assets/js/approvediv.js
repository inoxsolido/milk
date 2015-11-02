$(function () {
    function ReqData() {
        $(".loading").fadeIn();
        $.ajax({
            url: "../Data/FillApproveDiv",
            async: false,
            type: 'POST',
            data:{
                year: $("#approvediv").attr("year")
            },
            success: function (data, textStatus, jqXHR) {
                $("#approvediv").html(data);
            }
        });
        $(".loading").fadeOut();
    }
    
    $("#approvediv").on("click", ".confirm", function(){
        $(".loading").show();      
        $.ajax({
            url: "../Data/FillApproveDivConfirm",
            async: false,
            type: 'POST',
            data:{
                divid: $(this).attr("cid"),
                round: $("#approvediv").attr("round"),
                year: $("#approvediv").attr("year")
            },
            success: function (data, textStatus, jqXHR) {
                if(data != 'ok'){
                    alert("การยืนยันล้มเหลว");
                }else{
                    ReqData();
                }
            }
        });
        $(".loading").fadeOut();
    });
    
    $("#approvediv").on("click", ".unconfirm", function(){
        $(".loading").show();      
        $.ajax({
            url: "../Data/FillApproveDivUnconfirm",
            async: false,
            type: 'POST',
            data:{
                divid: $(this).attr("cid"),
                round: $("#approvediv").attr("round"),
                year: $("#approvediv").attr("year")
            },
            success: function (data, textStatus, jqXHR) {
                if(data != 'ok'){
                    alert("การยกเลิกการยืนยันล้มเหลว");
                }else{
                    ReqData();
                }
            }
        });
        $(".loading").fadeOut();
    });
    
    ReqData();
    
    
});