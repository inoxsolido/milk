$(function () {
    function ReqData() {
        $(".loading").fadeIn();
        $.ajax({
            url: "../Data/FillApproveAdmin",
            async: false,
            type: 'POST',
            data:{
                year: $("#approveadmin").attr("year")
            },
            success: function (data, textStatus, jqXHR) {
                $("#approveadmin").html(data);
                if($("#mfinalapprove").length){
                    $("#mfinalapprove").modal("show");
                    $("#mfinalapprove").on("click", "#btnfinal", function(){
                        if($("#chkfinal").prop("checked")){
                            if(confirm("ยืนยันการสิ้นสุดการแก้ไข ?"))
                            {
                                $(".loading").show();      
                                $.ajax({
                                    url: "../Data/FillApproveAdminConfirm",
                                    async: false,
                                    type: 'POST',
                                    data:{
                                        divid: $(this).attr("cid"),
                                        round: $("#approveadmin").attr("round"),
                                        year: $("#approveadmin").attr("year")
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
                            }
                        }
                    });
                }
            }
        });
        $(".loading").fadeOut();
    }
    
    $("#approveadmin").on("click", ".confirm", function(){
        $(".loading").show();      
        $.ajax({
            url: "../Data/FillApproveAdminConfirm",
            async: false,
            type: 'POST',
            data:{
                divid: $(this).attr("cid"),
                round: $("#approveadmin").attr("round"),
                year: $("#approveadmin").attr("year")
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
    
    $("#approveadmin").on("click", ".unconfirm", function(){
        $(".loading").show();      
        $.ajax({
            url: "../Data/FillApproveAdminUnconfirm",
            async: false,
            type: 'POST',
            data:{
                divid: $(this).attr("cid"),
                round: $("#approveadmin").attr("round"),
                year: $("#approveadmin").attr("year")
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