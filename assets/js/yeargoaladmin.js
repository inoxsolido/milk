$(function(){
    var year = $("#yearadmin").attr("year");
   function ReqData(){
       $(".loading").fadeIn();
        $.ajax({
            url: "../Data/FillYearGoalAdmin",
            async: false,
            type: 'POST',
            data:{
                year: year
            },
            success: function (data, textStatus, jqXHR) {
                $("#yearadmin").html(data);
            }
        });
        $(".loading").fadeOut();
   }
   function chkinputasdecimal(input) {
        var val = input.val();
        var sib = input.siblings('span');
        var letter = /^\d+\.?\d{0,2}$/;
        if (val == "") {
            sib.text("  กรุณากรอกค่า");
            return false;
        }
        if (!letter.test(val)) {
            sib.text("  กรุณากรอกค่าเป็นจำนวนเต็มหรือทศนิยม");
            return false;
        }
        sib.text("");
        return true;
    }
   //event
   $("#yearadmin").on("click", ".assign", function(){
       var tr = $(this).parent().parent().parent();
       tr.find(".sum").hide();
       tr.find(".tassign").show(400);
       
       tr.find(".assign").hide(300);
       tr.find(".save").show(400);
       tr.find(".cancel").show(400);
       
       tr.find(".incomet").focus();
   });
   $("#yearadmin").on("click", ".save", function(){
       var tr = $(this).parent().parent().parent();
       var did = $(this).parent().attr("did");
       var incomeval = tr.find(".incomet").val();
       var expendval = tr.find(".expendt").val();
       
       var chkincome = chkinputasdecimal(tr.find(".incomet"));
       var chkexpend = chkinputasdecimal(tr.find(".expendt"));
       if(!(chkincome && chkexpend)){
           return false;
       }
       
       if(Number(incomeval) == 0 && Number(expendval) == 0){
           if(!confirm("ต้องการยกเลิกกรอบงบประมาณเดิม ใช่หรือไม่")){
               return false;
           }
       }
       
       $(".loading").slideDown();
       $.ajax({
            url: "../Data/YearGoalSave",
            type: 'POST',
            async: false,
            data:{
                year:year,
                did:did,
                income:incomeval,
                expend:expendval
            },success: function (data, textStatus, jqXHR) {
                if(data == 1){
                    alert("บันทึกสำเร็จ");
                    ReqData();
                }else{
                    alert("บันทึกล้มเหลว\r\n"+data);
                }
            }
       });
       $(".loading").slideUp();
   });
   $("#yearadmin").on("click", ".cancel", function(){
       var tr = $(this).parent().parent().parent();
       var income = tr.find(".incomet");
       var expend = tr.find(".expendt");
       var incomeold = income.attr("old");
       var expendold = income.attr("old");
       income.val(incomeold);
       expend.val(expendold);
       
       tr.find(".sum").show(400);
       tr.find(".tassign").hide(100);
       
       tr.find(".save").hide(300);
       tr.find(".cancel").hide(300);
       tr.find(".assign").show(300);
       
       income.siblings("span").text("");
       expend.siblings("span").text("");
       
   });
   $(".decimal").change(function(){
        chkinputasdecimal($(this));
   });
   //main
   ReqData();
});


