$(function(){
    function ReqData(){
        $(".loading").show();
        $.ajax({
            url: "../BudgetManager/FillTable",
            type: 'POST',
            async: false,
            success: function (data, textStatus, jqXHR) {
                $("#tbbudyear").children("tbody").html(data);
            }
        });
        $(".loading").fadeOut();
    }
    ReqData();
    //event
    $("#btnaddbudyear").click(function(){
        $("#dpbudyear").val("").change().next(".output").removeClass("valid-false")
                    .removeClass("valid-true")
                    .text("");
        
    });
    function dpbudyearcheck(){
        if($("#dpbudyear").val() != ""){
            $("#dpbudyear").siblings(".output")
                    .text("");
            return true;
        }else{
            $("#dpbudyear").siblings(".output")
                    .text("กรุณาระบุปีก่อนบันทึก");
            return false;
        }
    }
    $("#dpbudyear").change(dpbudyearcheck);
    $("#btnsavebudyear").click(function(){
        $(".loader").show();
        if(dpbudyearcheck()){
            //save
            $.ajax({
                url: "../BudgetManager/AddYear",
                type: 'POST',
                async: false,
                data:{
                    year: $("#dpbudyear").val()
                },success: function (data, textStatus, jqXHR) {
                    if(data == "ok"){
                        alert("เพิ่มปีงบประมาณสำเร็จ");
                        $("#modal_add_budyear").modal("hide");
                        ReqData();
                    }else if(data == "dup"){
                        alert("ปีงบประมาณนี้มีอยู่แล้ว กรุณาเลือกปีงบประมาณใหม่");
                    }else{
                        alert("เพิ่มปีงบประมาณล้มเหลว กรุณาลองอีกครั้ง");
                        console.log(data);
                    }
                }
            });
        }else{
            //void
            $("#dpbudyear").next(".output").text("ปีงบประมาณนี้มีอยู่แล้ว");
        }
        $(".loader").hide();
    });
    $("#tbbudyear tbody").on("click", ".budgetyear-cancel", function(){
        if(!confirm("ต้องการลบปีงบประมาณ !?")){
            return false;
        }
        $(".loader").show();
        var parent = $(this).parents("tr");
        var year, round, version;
        year = $(parent).attr("year");
        round = $(parent).attr("round");
        version = $(parent).attr("version");
        $.ajax({
            url: "../BudgetManager/DeleteYear",
            type: 'POST',
            async: false,
            data:{
                year: year,
                round: round,
                version: version
            },success: function (data, textStatus, jqXHR) {
                if(data == "ok"){
                    alert("การลบปีงบประมาณสำเร็จ");
                    ReqData();
                }else{
                    alert("การลบปีงบประมาณล้มเหลว");
                    console.log(data);
                }
            }
        });
        $(".loader").hide();
    });
    
    $("#tbbudyear tbody").on("click", ".adjust-add", function(){
        if(!confirm("ต้องการเพิ่มรอบการจัดสรรงบประมาณใหม่ !?")){
            return false;
        }
        $(".loader").show();
        var parent = $(this).parents("tr");
        var year, round, version;
        year = $(parent).attr("year");
        round = $(parent).attr("round");
        version = $(parent).attr("version");
        $.ajax({
            url: "../BudgetManager/AddAdjust",
            type: 'POST',
            async: false,
            data:{
                year: year,
                round: round,
                version: version
            },success: function (data, textStatus, jqXHR) {
                if(data == 'ok'){
                    alert("การเพิ่มรอบการจัดสรรงบประมาณใหม่สำเร็จ");
                    ReqData();
                }else{
                    alert("การเพิ่มรอบการจัดสรรงบประมาณล้มเหลว");
                    console.log(data);
                }
            }
        });
        $(".loader").slideUp();
    });
    $("#tbbudyear tbody").on("click", ".adjust-cancel", function(){
        if(!confirm("ต้องการลบรอบการจัดสรรงบประมาณล่าสุด !?")){
            return false;
        }
        $(".loader").show();
        var parent = $(this).parents("tr");
        var year, round, version;
        year = $(parent).attr("year");
        round = $(parent).attr("round");
        version = $(parent).attr("version");
        $.ajax({
            url: "../BudgetManager/DeleteAdjust",
            type: 'POST',
            async: false,
            data:{
                year: year,
                round: round,
                version: version
            },success: function (data, textStatus, jqXHR) {
                if(data == 'ok'){
                    alert("การลบรอบการจัดสรรงบประมาณใหม่สำเร็จ");
                    ReqData();
                }else{
                    alert("การลบรอบการจัดสรรงบประมาณล้มเหลว");
                    console.log(data);
                }
            }
        });
        $(".loader").slideUp();
    });
    
    $("#tbbudyear tbody").on("click", ".version-add", function(){
        $(".loader").show();
        var parent = $(this).parents("tr");
        var year, round, version;
        year = $(parent).attr("year");
        round = $(parent).attr("round");
        version = $(parent).attr("version");
        $.ajax({
            url: "../BudgetManager/AddVersion",
            type: 'POST',
            async: false,
            data:{
                year: year,
                round: round,
                version: version
            },success: function (data, textStatus, jqXHR) {
                if(data == 'ok'){
                    alert("การเพิ่มเวอร์ชันสำเร็จ");
                    ReqData();
                }else{
                    alert("การเพิ่มเวอร์ชันล้มเหลว");
                    console.log(data);
                }
            }
        });
        $(".loader").slideUp();
    });
    $("#tbbudyear tbody").on("click", ".version-cancel", function(){
        if(!confirm("ต้องการลบเวอร์ชันงบประมาณล่าสุด !?")){
            return false;
        }
        $(".loader").show();
        var parent = $(this).parents("tr");
        var year, round, version;
        year = $(parent).attr("year");
        round = $(parent).attr("round");
        version = $(parent).attr("version");
        $.ajax({
            url: "../BudgetManager/DeleteVersion",
            type: 'POST',
            async: false,
            data:{
                year: year,
                round: round,
                version: version
            },success: function (data, textStatus, jqXHR) {
                if(data == 'ok'){
                    alert("การลบเวอร์ชันสำเร็จ");
                    ReqData();
                }else{
                    alert("การลบเวอร์ชันล้มเหลว");
                    console.log(data);
                }
            }
        });
        $(".loader").slideUp();
    });
    
    $("#tbbudyear tbody").on("click", ".accyear-add ,.accyear-edit, .accyear-cancel", function(e){
        e.preventDefault();
        var parent = $(this).parents("tr");
        var year, round, version;
        year = $(parent).attr("year");
        round = $(parent).attr("round");
        version = $(parent).attr("version");
        var c = $(this).attr("class");
        if(c.search("add") > -1){
            //add
            window.open($(this).attr("href"));
            
            
        }else if(c.search("edit") > -1){
           //edit
           if(confirm("การแก้ไขบัญชีที่ใช้ในปีงบประมาณจะทำให้บัญชีในการกรอกงบประมาณและการออกรายงานเปลี่ยนแปลง\r\nต้องการแก้ไข ?")){
                window.open($(this).attr("href"));
                
            }
        }else if(c.search("cancel") > -1){
            if(confirm("การลบบัญชีที่ใช้ในปีงบประมาณออกจะทำให้บัญชีนั้นหายไปจากการออกรายงานงบประมาณของปีงบประมาณ "+(Number(year)+543)+"\r\nคุณต้องการที่จะลบ ?")){
                $(".loading").show();
                $.ajax({
                    url: "../Data/DeleteAccYear",
                    type: 'POST',
                    async: false,
                    data:{
                        year: year
                    },success: function (data, textStatus, jqXHR) {
                        if(data == 'ok'){
                            alert("การลบเสร็จสมบูรณ์");
                            ReqData();
                        }else{
                            alert("การลบบัญชีล้มเหลว "+data);
                            $(".loading").fadeOut();
                        }
                    }
                });
            }
        }
    });
    $("#tbbudyear tbody").on("click", ".org-add ,.org-edit, .org-cancel", function(e){
        e.preventDefault();
        var parent = $(this).parents("tr");
        var year, round, version;
        year = $(parent).attr("year");
        round = $(parent).attr("round");
        version = $(parent).attr("version");
        var c = $(this).attr("class");
        if(c.search("add") > -1){
            //add
            window.open($(this).attr("href"));
            
            
        }else if(c.search("edit") > -1){
           //edit
           if(confirm("การแก้ไขบัญชีที่ใช้ในปีงบประมาณจะทำให้บัญชีในการกรอกงบประมาณและการออกรายงานเปลี่ยนแปลง\r\nต้องการแก้ไข ?")){
                window.open($(this).attr("href"));
                
            }
        }else if(c.search("cancel") > -1){
            if(confirm("การลบโครงสร้างองค์กรที่ใช้ในปีงบประมาณออกจะทำให้ไม่สามารถออกรายงานงบประมาณในปี "+(Number(year)+543)+" ได้ \r\nคุณต้องการที่จะลบ ?")){
                $(".loading").show();
                $.ajax({
                    url: "../OrgChart/DeleteOrgChart",
                    type: 'POST',
                    async: false,
                    data:{
                        year: $(this).parent().attr("year")
                    },success: function (data, textStatus, jqXHR) {
                        if(data == 'ok'){
                            alert("การลบเสร็จสมบูรณ์");
                            ReqData();
                        }else{
                            alert("การลบโครงสร้างองค์กรล้มเหลว "+data);
                            $(".loading").fadeOut();
                        }
                    }
                });
            }
        }
    });
});

