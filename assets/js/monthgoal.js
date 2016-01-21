var uv = false;
var target = 0;
function mver()
{
    uv = uv != true;
    //add new menu
    $("#mver").slideToggle("fast");
    if (uv)
        $(window).scrollTop(0);

}

$(function () {
    function ReqEmpty()
    {
        $.ajax({
            url: '../Data/FillMonthGoalAccSelect',
            type: 'POST',
            async: false,
            data: {
                year: $("#fstep").attr("year")
            }, success: function (data, textStatus, jqXHR) {
                //alert(data);
                $(".fchkbox").html(data);
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
                    onFinish: OnFinishCallback // triggers when Finish button is clicked 
                });
            }
        });
    }
    function OnFinishCallback()
    {
        $('[href="#next"]').click();
    }
    function OnShowCallback()
    {
        var current = $(".swMain2").smartWizard("currentStep");
        if (current == 4)
            $(".swMain2").smartWizard("enableFinish", true);
        else
            $(".swMain2").smartWizard("enableFinish", false);
    }



    function chkinputasdecimal(input) {
        var val = input.val();
        var sib = input.siblings('span');
        var letter = /^\d+\.?\d{0,2}$/;
        if (val == "") {
            sib.text("   กรุณากรอกค่า หรือ กลับไปเลือกบัญชีที่จะใช้ใหม่");
            return false;
        }
        if (!letter.test(val)) {
            sib.text("  กรุณากรอกค่าเป็นตัวเลขหรือตัวเลขทศนิยม")
            return false;
        }
        sib.text("");
        return true
    }

    function serializeData() {
        if ($("input[name^='acc-'][month]").length > 0)
        {
            var allin = $("input[name^='acc-'][month]");
            var data = [];
            allin.each(function () {
                var accid = $(this).attr("name").replace("acc-", "");
                var month = $(this).attr("month");
                var value = $(this).val();
                data.push({accid: accid, month: month, value: value});
            });
            return data ? data : false;
        }
    }
    function OnFinishingCallback(event, currentIndex)
    {
        $(".loading").show();
        var e = true;
        $("input[name^='acc-'][month]").each(function () {
            if (!chkinputasdecimal($(this))) {
                alert("กรุณากรอกค่าในช่องว่างให้ครบ");
                //$(this).focus();
                gototab($(this));
                e = false;
                return false;
            }
        });
        if (!e) {
            $(".loading").hide();
            return false;
        }
        var data = serializeData();
        if (data)
        {
            $.ajax({
                url: "../Data/AddMonthGoal",
                type: 'POST',
                async: false,
                data: {
                    fdata: data,
                    year: $("#fstep").attr("year"),
                    target: target
                }, success: function (data, textStatus, jqXHR) {
                    $(".loading").hide();
                    if (data == "OK") {
                        alert("การบันทึกสำเร็จ");
                        window.location.reload();
                        return true;
                    } else {
                        alert("การบันทึกล้มเหลว กรุณาลองใหม่ ");
                        return false;
                    }
                }
            });
        } else {
            $(".loading").hide();
            alert("serializaData has fail");
        }
    }
    function ReqVersion() {
        $(".loading").show();
        $.ajax({
            url: "../Data/FillVersionSelector",
            type: 'POST',
            async: false,
            data: {
                year: $("#iyear").val(),
                div: target
            }, success: function (data, textStatus, jqXHR) {
                $("#iver").html(data);
            }
        });
        $(".loading").hide();
    }
    //js-main
    $("#fstep").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        enableFinishButton: true,
        enablePagination: true,
        enableKeyNavigation: false,
        enableAllSteps: false,
        titleTemplate: "#title#",
        labels: {
            cancel: "Cancel",
            current: "current step:",
            pagination: "Pagination",
            finish: "บันทึก",
            next: "เริ่มกรอกงบประมาณ",
            previous: "เลือกบัญชีงบประมาณ",
            loading: "Loading ..."
        },
        onStepChanging: OnStepChangingCallback,
        onFinishing: OnFinishingCallback
    });
    $(".finput").on("focusout", "input[name^='acc-']", function () {

        chkinputasdecimal($(this));
    });
    $("#iyear").change(function () {
        ReqVersion();
    });
    $("#btntarget").click(function () {
        target = $("#target").val();
        if(target != 0)
            $(".page-warp").hide();
    });
    $("#msubmit").click(function () {
        var ver = $("#iver").val();
        //alert(ver);
        if (ver == "0") {
            alert("กรุณาเลือกเวอร์ชั่นก่อนตกลง");
            return false;
        } else {//can do
            $(".loading").show();
            $.ajax({
                url: "../Data/AccVersion",
                type: 'POST',
                async: false,
                data: {
                    year: $("#fstep").attr("year"),
                    ver: ver,
                    div: target
                }, success: function (data, textStatus, jqXHR) {
                    if (typeof (data) == 'object') {
                        //back to checkbox
                        $("#fstep").steps("previous");
                        //checkbox
                        var temp = data;
                        for (entry in temp) {
                            var chk = $("input[name='" + entry.acc_id + "']");
                            if (chk != null) {
                                chk.prop("checked", true).change();
                                console.log("chk");
                            }
                        }
                        //next to input
                        //$("[href=#next]").click();
                        $("#fstep").steps("next");
                        function filltxtbox(temp)
                        {
                            for (entry in temp)
                            {
                                var txtbox = $("input[name='acc-" + entry.acc_id + "']");
                                if (txtbox != null) {
                                    txtbox.val(entry.value);
                                    //console.log("txtbox");
                                }
                            }
                        }

                        setTimeout(filltxtbox(temp), 1000);
                    } else {
                        alert("เกิดข้อผิดพลาดระหว่างการเรียกเวอร์ชั่นก่อนหน้า");
                    }
                }, dataType: 'JSON'
            });
            $(".loading").hide();
        }
    });

    ReqEmpty();
});
function OnStepChangingCallback(event, currentIndex, newIndex)
{
    if (newIndex == 1)
    {
        //error checking
        if ($(".fchkbox").find("input[type=checkbox][name!='selall']:checked").length == 0) {
            alert("กรุณาเลือกบัญชีอย่างน้อย 1 บัญชีก่อนบันทึก");
            return false;
        }
        $(".loading").show();
        //preparing data
        var fdata = [];
        $(".fchkbox").find("input[type=checkbox][name!='selall']").each(function () {
            if ($(this).prop("checked") == true || $(this).prop("indeterminate") == true)
                fdata.push($(this).attr("name"));
        });
        //sending data
        $.ajax({
            url: "../Data/FillMonthGoalEmpty",
            type: 'POST',
            async: false,
            data: {
                accarr: fdata
            }, success: function (data, textStatus, jqXHR) {
                if (data != "")
                {
                    $(".finput").html(data);
                    $(".swMain").smartWizard({
                        selected: 0, // Selected Step, 0 = first step   
                        keyNavigation: true, // Enable/Disable key navigation(left and right keys are used if enabled)
                        enableAllSteps: false,
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
                        onShowStep: OnInputShowCallback, // triggers when showing a step
                        onFinish: OnInputFinish // triggers when Finish button is clicked 
                    });
                    ulcollapse($(".sh"));
                }
            }
        });
        $("#mver").hide();
        $(".menu").hide();
        $(".loading").hide();
    }
    else if(newIndex == 0)//back
    {
        var want = confirm("ข้อมูลตัวเลขที่กรอกมาจะสูญหาย หากเลือกบัญชีใหม่\r\nยืนยันที่จะทำ ?");
        if(!want)
            return false;
        
        $(".loading").show();
        $(".menu").show();
        $(".loading").hide();
    }

    return true;
}
function OnInputShowCallback() {
    var current = $(".swMain").smartWizard("currentStep");
    //focus first element in tab
    //get month of tab
    var month = current + 9;
    if (month > 12)
        month = current - 3;
    var input = $("input[month="+month+"]")[0];
    if($("input[month="+month+"]:focus").length == 0)
        input.focus();
    
    if (current == 12)
        $(".swMain").smartWizard("enableFinish", true);
    else
        $(".swMain").smartWizard("enableFinish", false);
}
function OnInputFinish() {
    $("[href=#finish]").click();
}

function gototab(txtbox) {
    //get month
    var month = txtbox.attr("month");
    var tab = month - 9;
    if (tab < 1)
        tab = month + 3;
    var currenttab = $(".swMain").smartWizard("currentStep");
    if (tab != currenttab)
        $(".swMain").smartWizard("goToStep", tab);
    //console.log(tab);
    txtbox.focus();
}