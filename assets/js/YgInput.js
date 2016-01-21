$(function () {
    //var declaration
    var year = $("#YgdInput").attr("year");
    var round = $("#YgdInput").attr("round");
    var cid = $("#YgdInput").attr("cid");
    var method = $("#YgdInput").attr("method");
    //function declaration
    function goto(input){
        var group = Number(input.attr('g'));
        $(".swMain2").smartWizard("goToStep", group);
        input.focus();
    }
    function ReqData() {
        $(".loading").show();
        //get limit
        $.ajax({
            url: "../../../Data/FillYgCan",
            type: "POST",
            async: true,
            data: {
                year: year
            }, success: function (data, textStatus, jqXHR) {
                if (data.error == 1) {
                    alert(data.msg);
                } else {
                    $("#canincome").val((data.canincome));
                    $("#canexpend").val((data.canexpend));
                }
            }, dataType: 'JSON'
        });
        //get form
        $.ajax({
            url: "../../../Data/FillYgInput",
            type: 'POST',
            async: false,
            data: {
                year: year
            },
            success: function (data, textStatus, jqXHR) {
                $("#YgdInput").html(data);
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
                    onShowStep: null, // triggers when showing a step
                    onFinish: onFinish // triggers when Finish button is clicked 
                });
                ulcollapse($(".sh"));
                $("input[type=checkbox]").change(function () {
                    var div = $(this).parent().next("div");
                    if ($(this).is(":checked")) {
                        div.show();
                        div.addClass("txtshow");
                    } else {
                        div.hide();
                        div.removeClass("txtshow");
                    }
                });
            }
        });
        //get info if method is 'edit'
        if (method == 'edit') {
            $.ajax({
                url: "../../../Data/YgInfo",
                async: false,
                type: 'POST',
                data: {
                    year: year,
                    round: round,
                    cid: cid,
                    method: method
                }, success: function (data, textStatus, jqXHR) {
                    if (data == "error") {
                        alert("ไม่สามารถเรียกข้อมูลเดิมมาได้ กรุณาลบ แล้วทำการกำหนดค่าใหม่");
                        return false;
                    } else {
                        for(var i in data){
                            var accid = data[i].accid,
                                    value = data[i].value
                            ;
                            //check checkbox
                            $("input[type=checkbox][name="+accid+"]").prop("checked", true).change();
                            //assign value
                            $("input[type=text][name=acc-"+accid+"]").val(value).change();
                        }
                        $("input[type=text][name=acc-"+data[0].accid+"]").focus();
                    }
                },dataType: 'JSON'

            });
        }
        $(".loading").hide();
    }
    function serializeData() {
        if ($(".txtshow > input[type=text]").length > 0)
        {
            var allin = $(".txtshow > input[type=text]");
            var data = [];
            allin.each(function () {
                var accid = $(this).attr("name").replace("acc-", "");
                var value = $(this).val().replace(',', '').replace(',', '').replace(',', '');
                data.push({accid: accid, value: value});
            });
            return data;
        }
        return false;
    }
    function onFinish() {
        //check some acc is checked
        $(".loading").show();
        if ($("#YgdInput").find("input[type=checkbox][name!='selall']:checked").length == 0) {
            alert("กรุณาเลือกบัญชีอย่างน้อย 1 บัญชี");
            $(".loading").hide();
            return false;
        }
        //check all checked acc not empty
        var correct = true;
        $(".txtshow > input[type=text]").each(function () {
            if (!chkinputasdecimal($(this), true)) {
                alert("กรุณากรอกค่าในช่องว่างให้ครบ");
                goto($(this));
                correct = false;
                return false;
            }
        });
        if (!correct) {
            $(".loading").hide();
            return false;
        }

        var predata = serializeData();
        if (!predata) {
            alert("serializaData has fail");
            $(".loading").hide();
        } else {
            $.ajax({
                url: "../../../Data/YgSave",
                async: false,
                type: 'POST',
                data: {
                    year: year,
                    round: round,
                    cid: cid,
                    detail: predata,
                    method: method
                }, success: function (data, textStatus, jqXHR) {
                    if(data == 1){
                        alert("บันทึกสำเร็จ");
                        window.location = $("#YgdInput").attr("url");
                    }else{
                        alert("บันทึกล้มเหลว");
                        console.log(data);
                    }
                    $(".loading").hide();
                    
                }
            });
        }

    }
    function sumNow() {
        var Total = [];
        Total = {
            income: Number(0),
            expend: Number(0)
        };
        $(".txtshow > input[type=text]").each(function (index, entry) {
            if (Number($(entry).attr('g')) == 1) {
                Total.income += Number(entry.value.replace(',', '').replace(',', '').replace(',', ''));
            }
            else {
                Total.expend += Number(entry.value.replace(',', '').replace(',', '').replace(',', ''));
            }
        });
        return Total;
    }
    function chkinputasdecimal(input, msg) {
        var val = input.val().replace(',', '').replace(',', '').replace(',', '');
        var sib = input.siblings('span');
        var letter = /^\d+\.?\d{0,2}$/;
        if (!msg && letter.test(val)) {
            return true;
        }
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
    function formatDec(input, ignornull) {
        var strval = String(input.val().replace(',', '').replace(',', '').replace(',', ''));
        strval = String(Number(strval));
        var len = strval.length;
        if (chkinputasdecimal(input, false) || ignornull) {
            var flagfloat = false;
            var dp = 0;
            if (strval != null) {
                //get resource to know it has dot then if has how many precision
                for (var i = 0; i < len; i++) {
                    if (strval[i] == '.') {
                        flagfloat = true;
                    } else if (flagfloat) {
                        dp++;
                    }
                }
                //natural formating
                for(var i = 0,ri = (strval.length-1)-(dp)-(flagfloat?1:0); ri>=0; i++,ri--){
                    if(i!=0 && i % 3 == 0){
                        strval = strval.substr(0,(ri+1))+','+strval.substr((ri+1),strval.length);
                    }
                }
                
                //add float
                if (flagfloat) {
                    if(dp > 2){
                        //del float
                        strval = strval.substr(0,strval.length-(dp-2));
                    }
                    for (var i = 0; i < (2 - dp); i++) {
                        strval += '0';
                    }
                } else {
                    strval += '.00';
                }
            } else {
                strval = '0.00';
            }
            input.val(String(strval));
        }
    }
    //event
    $(".disabled > span > input[readonly]").change(function () {
        formatDec($(this), true);
        //change color of nowincome
        if ($("#canincome").length > 0) {
            if (Number($("#canincome").val()) < Number($("#nowincome").val())) {
                $("#nowincome").addClass("overlimit");
                $("#nowincome").removeClass("equlimit");
                $("#nowincome").removeClass("inlimit");
            } else if (Number($("#canincome").val()) == Number($("#nowincome").val())) {
                $("#nowincome").removeClass("overlimit");
                $("#nowincome").addClass("equlimit");
                $("#nowincome").removeClass("inlimit");
            } else if (Number($("#canincome").val()) > Number($("#nowincome").val())) {
                $("#nowincome").removeClass("overlimit");
                $("#nowincome").removeClass("equlimit");
                $("#nowincome").addClass("inlimit");
            }
        } else {
            $("#nowincome").removeClass("overlimit");
            $("#nowincome").removeClass("equlimit");
            $("#nowincome").addClass("inlimit");
        }
        //change color of nowexpend
        if ($("#canexpend").length > 0) {
            if (Number($("#canexpend").val()) < Number($("#nowexpend").val())) {
                $("#nowexpend").addClass("overlimit");
                $("#nowexpend").removeClass("equlimit");
                $("#nowexpend").removeClass("inlimit");
            } else if (Number($("#canexpend").val()) == Number($("#nowexpend").val())) {
                $("#nowexpend").removeClass("overlimit");
                $("#nowexpend").addClass("equlimit");
                $("#nowexpend").removeClass("inlimit");
            } else if (Number($("#canexpend").val()) > Number($("#nowexpend").val())) {
                $("#nowexpend").removeClass("overlimit");
                $("#nowexpend").removeClass("equlimit");
                $("#nowexpend").addClass("inlimit");
            }
        } else {
            $("#nowexpend").removeClass("overlimit");
            $("#nowexpend").removeClass("equlimit");
            $("#nowexpend").addClass("inlimit");
        }
    });
    $("#YgdInput").on("change", ".txtshow > input[type=text]", function () {
        formatDec($(this), false);
        if (chkinputasdecimal($(this), true)) {
            var sum = sumNow();
            $("#nowincome").val(sum.income).change();
            $("#nowexpend").val(sum.expend).change();
        }
    });
    $("#YgdInput").on("focusout", ".txtshow > input[type=text]", function () {
        chkinputasdecimal($(this), false);
    });
    //main
    ReqData();
    $("#nowincome").val(sumNow().income).change();
    $("#nowexpend").val(sumNow().expend).change();
    $("#canincome").change();
    $("#canexpend").change();
});
