var who, selYear;

$(function(){
    function ReqData(){
        $(".loading").fadeIn();
        $.ajax({
            url: "../Data/FillAccYear",
            type: 'POST',
            async: false,
            success: function (data, textStatus, jqXHR) {
                $("#yearlist").children("tbody").html(data);
                $("#yearlist").show();
                $("#accyear").hide();
                $("#btnback").hide();
                $(".loading").fadeOut();
            }
            
        });
    }
    function ReqNewData(method, year) {
        if(!method) method = who;
        else who = method;
        if(!year) year = selYear;
        else selYear = year;
        
        $("#yearlist").hide();
        $("#accyear").show();
        $("#btnback").show();
        $.ajax({
            url: '../Data/FillAccYearEmpty',
            type: 'POST',
            async: false,
            success: function (data, textStatus, jqXHR) {
                
                $("#accyear").html(data);
                $(".checkbox-tree").checktree();
                $(".swMain2").smartWizard({
                    selected: 0, // Selected Step, 0 = first step   
                    keyNavigation: false, // Enable/Disable key navigation(left and right keys are used if enabled)
                    enableAllSteps: true,
                    updateHeight: true,
                    transitionEffect: 'slideleft', // Effect on navigation, none/fade/slide/slideleft
                    contentURL: null, // content url, Enables Ajax content loading
                    contentCache: true, // cache step contents, if false content is fetched always from ajax url
                    cycleSteps: false, // cycle step navigation
                    includeFinishButton: true, // whether to show a Finish button
                    enableFinishButton: false, // make finish button enabled always
                    hideButtonsOnDisabled: true,
                    errorSteps: [], // Array Steps with errors
                    labelNext: 'ถัดไป',
                    labelPrevious: 'ย้อนกลับ',
                    labelFinish: 'บันทึก',
                    onLeaveStep: null, // triggers when leaving a step
                    onShowStep: OnShowCallback, // triggers when showing a step
                    onFinish: OnFinnishCallback // triggers when Finish button is clicked 
                });
            }
        });
        if (method == "edit") {
            $.ajax({
                url: "../Data/AccYear_AccinYear",
                type: 'POST',
                async: false,
                dataType: 'JSON',
                data: {
                    year: year
                }, success: function (data, textStatus, jqXHR) {
                    if (typeof (data) == 'object') {
                        var temp = [];
                        temp = data;
                        for (entry in temp)
                        {
                            var chk = $("input[name='" + temp[entry].acc_id + "']");
                            chk.prop("checked", true);
                        }
                    }
                    else
                        alert(data);

                }
            });
            $("#accyear").attr("action", "EditAccYear");
        } else
            $("#accyear").attr("action", "AddAccYear");
    }
    function OnShowCallback()
    {
        var current = $(".swMain2").smartWizard("currentStep");
        if (current == 4)
            $(".swMain2").smartWizard("enableFinish", true);
        else
            $(".swMain2").smartWizard("enableFinish", false);
    }
    function OnFinnishCallback()//add
    {
        var result = "";
        if ($("#accyear").find("input[type=checkbox][name!='selall']:checked").length == 0) {
            alert("กรุณาเลือกบัญชีอย่างน้อย 1 บัญชีก่อนบันทึก");
            return false;
        }
        if(!result)
            result = confirm(who == "assign" ? "ยืนยันการบันทึก ?" : "ยืนยันการแก้ไข !?");
        //prepare data
        var fdata = [];
        $("#accyear").find("input[type=checkbox][name!='selall']").each(function () {
            if ($(this).prop("checked") == true || $(this).prop("indeterminate") == true)
                fdata.push($(this).attr("name"));
        });
        if (result == true)
        {
            $(".loading").show();
            $.ajax({
                url: "../Data/" + $("#accyear").attr("action"),
                type: 'POST',
                async: false,
                data: {
                    'fdata': fdata,
                    'year': selYear

                }, success: function (data, textStatus, jqXHR) {
                    if (data == "ok")
                    {                        
                        alert(who == "assign" ? "บันทึกข้อมูลสำเร็จ" : "แก้ไขข้อมูลสำเร็จ");
                        $("#accyear").html("").hide();
                        ReqData();
                        
                    } else
                    {
                        alert(data);
                        $(".loading").fadeOut();
                    }
                }
            });
        } else
            $(".swMain").smartWizard("goToStep", 1);
    }
    
    function checkParams(){
        var params = $.getQueryParameters();
        if(!params.method || !params.year) 
            ReqData();
        else 
            ReqNewData(params.method, params.year);
    }
    
    //bind event
    $("#yearlist").on("click", ".assign, .edit, .cancel", function(){
        var method;
        if($(this).hasClass("assign")) method = "assign";
        else if($(this).hasClass("edit")) {
            method = "edit";
            if(!confirm("การแก้ไขบัญชีที่ใช้ในปีงบประมาณจะทำให้บัญชีในการกรอกงบประมาณและการออกรายงานเปลี่ยนแปลง\r\nต้องการแก้ไข ?")) return false;
        }
        else if($(this).hasClass("cancel")) {
            if(confirm("การลบบัญชีที่ใช้ในปีงบประมาณออกจะทำให้บัญชีนั้นหายไปจากการออกงบประมาณของปีงบประมาณ "+(Number($(this).parent().attr("year"))+543)+"\r\nคุณต้องการที่จะลบ ?")){
                $(".loading").show();
                $.ajax({
                    url: "../Data/DeleteAccYear",
                    type: 'POST',
                    async: false,
                    data:{
                        year: $(this).parent().attr("year")
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
            return true;
        };
        if(method === undefined) return false;
        $(".loading").show();
        selYear = $(this).parent().attr("year");
        who = method;
        ReqNewData();
        $(".loading").fadeOut();
        
    });
    $("#btnback").click(function () {
        if (confirm("ข้อมูลที่คุณกรอกค้างไว้จะหายไป\r\nคุณต้องการย้อนกลับ ?"))
        {
            window.location.search = "";
            $("#accyear").html("").hide();
            $("#yearlist").show();
            $(this).hide();
        }
    });
    
    //main
    checkParams();
});

