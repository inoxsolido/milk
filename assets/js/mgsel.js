$(function(){
    //var
    var year = $("#monthdiv").attr("year");
    var round = $("#monthdiv").attr("round");
    //function
    function ReqYearList(){
        $(".loading").show();
        $.post("../MonthGoal/FillYear",function(data){
            error = data.error;
            if(error == 'none'){
                
                var years = data.data;
                var defoption = $("<option/>", {selected:"selected", disabled:"disabled"}).text("เลือกปี");
                $("#selbudyear").html("").append(defoption);
                for (var i in years){
                    var option = $("<option/>", {year: years[i].year, round: years[i].round, value: years[i].year}).text((Number(years[i].year)+543)+" รอบที่ "+years[i].round );
                    $("#selbudyear").append(option);
                }
                
                $("#selbudyear").val(years[0].year).change();
            }else if(error == 'empty'){
                var span = $("<span/>", {class: 'text-danger'}).text("ไม่มีปีงบประมาณที่สามารถกรอกข้อมูลงบประมาณได้");
                $("#budyearselect").html(span);
                $(".loading").hide();
            }
        },'json');
    }
    
    //event
    $("#selbudyear").change(function(){
        var year = $(this).children(":selected").attr("year");
        var round = $(this).children(":selected").attr("round");
        $(".loading").show();
        setTimeout(function(){
        $.ajax({
            url: "../MonthGoal/FillJobList",
            type: 'POST',
            async: false,
            data:{
                year: year,
                round: round
            },success: function (data, textStatus, jqXHR) {
                $("#joblist").html(data);
            },complete: function (jqXHR, textStatus) {
                    $(".loading").hide();
                }
        });},500);
        
    });
    $("#joblist").on("click", ".assign, .edit", function(){
        $(".loading").show();
        var c = $(this).attr("class");
        var method = c.search("assign")>0?"assign":c.search("edit")>0?"edit":"";
        delete(c);
        var cid = $(this).parents("tr").attr("destination");
        var cname = $(this).parents("td").prev("td").text();
        var year = $("#selbudyear").children(":selected").attr("year");
        var round = $("#selbudyear").children(":selected").attr("round");
        //error check
        if(true){
            setTimeout(function(){
            $.ajax({
                url: "../MonthGoal/MonthGoalForm",
                type: 'POST',
                async: false,
                data:{cid:cid, cname:cname, year:year, round:round, method: method},
                beforeSend: function (xhr) {
                    
                },
                success: function (data, textStatus, jqXHR) {
                    $("#monthgoalform").html(data).show();
                    $("#selector").hide();
                    $("#saveopt").show();
                    
                },
                complete: function (jqXHR, textStatus) {
                    $(".loading").hide();
                }
            });}, 1000);
        }
        
    });
    $("#btnback").click(function () {
        if (confirm("ข้อมูลที่คุณกรอกค้างไว้จะหายไป\r\nคุณต้องการย้อนกลับ ?"))
        {
            $("#monthgoalform").html("").hide();
            $("#selector").show();
            $("#saveopt").hide();
            $("#btn_recover_close").click();
        }
    });
    
    //main
    ReqYearList();
    //ReqData();
});

