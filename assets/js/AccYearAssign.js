var selYear = 0;
var who = 'add';
$(function () {
    FillEdit();

    function ReqNewData(IsNew) {
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
        if (!IsNew) {
            $.ajax({
                url: "../Data/AccYear_AccinYear",
                type: 'POST',
                async: false,
                dataType: 'JSON',
                data: {
                    year: selYear
                }, success: function (data, textStatus, jqXHR) {
                    if (typeof (data) == 'object') {
                        var temp = [];
                        temp = data;
                        temp.forEach(function (entry) {
                            var chk = $("input[name='" + entry.acc_id + "']");
                            chk.prop("checked", true);
                        });
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
        if ($("#accyear").find("input[type=checkbox][name!='selall']:checked").length == 0) {
            if (who == "add") {
                alert("กรุณาเลือกบัญชีอย่างน้อย 1 บัญชีก่อนบันทึก");
                return false;
            }
        }
        var result = confirm(who == "add" ? "ยืนยันการบันทึก ?" : "ยืนยันการลบ !?");
        //prepare data
        var fdata = [];
        $("#accyear").find("input[type=checkbox][name!='selall']").each(function () {
            if ($(this).prop("checked") == true || $(this).prop("indeterminate") == true)
                fdata.push($(this).attr("name"));
        });
        if (result == true)
        {
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
                        if (who == "add")
                            $("#addm").click();
                        else
                            $("#editm").click();
                        
                        alert(who == "add" ? "บันทึกสำเร็จ" : "ลบข้อมูลสำเร็จ");
                        $("#accyear").html("");
                        FillEdit();
                    } else
                    {
                        alert(data);
                    }
                }
            });
        } else
            $(".swMain").smartWizard("goToStep", 1);
    }
    function FillEdit()
    {
        $.ajax({
            url: '../Data/FillAccYear_Year',
            type: 'POST',
            async: false,
            success: function (data) {
                $("#meyear").html(data);
                if ($("#meyear").find("option[value!=0]").length)//found
                    $("#meyear").find("option[value=0]").prop("disabled", true);
                else
                    $("#meyear").find("option[value=0]").prop("disabled", false);
            }
        });

    }
    $("#mbtnselyear").click(function () {
        var dup = true;
        var temp = $("#mayear").val();
        if (temp == "") {
            alert("กรุณาเลือกปีก่อนตกลง");
            return;
        }
        //ask year duplicate ?
        $.ajax({
            url: "../Valid/AYA_YearDup",
            type: 'POST',
            async: false,
            data: {
                year: temp
            },
            success: function (data, textStatus, jqXHR) {
                if (data == "Yes")
                    dup = true;
                else
                    dup = false;
            }
        });
        if (dup)
            alert("ปีที่ระบุได้เคยกำหนดบัญชีที่ใช้ไว้แล้ว กรุณาระบุปีใหม่");
        else {
            selYear = temp;
            who = 'add';
            ReqNewData(1);//newdata
        }
    });

    $("#medit").find(".btn").click(function () {
        var sel = $("#meyear").val();
        if (sel == 0 || sel == "" || sel == null) {
            alert("กรุณาเลือกปีที่จะแก้ไขก่อนตกลง");
            return false;
        }
        selYear = sel;
        who = 'edit';
        ReqNewData(0);//editdata

    });

    //main


});
var ua = false;
function showmenuadd()
{
    ua = ua != true;
    //clear old menu
    $("#medit").slideUp("fast");
    //add new menu
    $("#madd").slideToggle("fast");
    if (ua)
        $(window).scrollTop(0);

}
var ue = false;
function showmenuedit()
{
    ue = ue != true;
    //clear old menu
    $("#madd").slideUp("fast");
    //add new menu
    $("#medit").slideToggle("fast");
    if (ue)
        $(window).scrollTop();
}