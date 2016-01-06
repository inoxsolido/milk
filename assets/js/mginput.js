$(function () {
    var year = $("#mginput").attr("year");
    var round = $("#mginput").attr("round");
    var cid = $("#mginput").attr("cid");
    var method = $("#mginput").attr("method");

    function chkinputasdecimal(input, msg) {
        var val = input.val().replace(',', '').replace(',', '').replace(',', '');
        var sib = input.siblings('span.err');
        var letter = /^-{0,1}\d+\.?\d{0,}$/;
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
        return true;
    }
    function formatDec(input, ignornull) {
//        var strval = String(input.val().replace(',', '').replace(',', '').replace(',', ''));
//        strval = String(Number(strval));
//        var len = strval.length;
//        if (chkinputasdecimal(input, false) || ignornull) {
//            var flagfloat = false;
//            var dp = 0;
//            if (strval != null) {
//                //get resource to know it has dot then if has how many precision
//                for (var i = 0; i < len; i++) {
//                    if (strval[i] == '.') {
//                        flagfloat = true;
//                    } else if (flagfloat) {
//                        dp++;
//                    }
//                }
//                //natural formating
//                for(var i = 0,ri = (strval.length-1)-(dp)-(flagfloat?1:0); ri>=0; i++,ri--){
//                    if(i!=0 && i % 3 == 0){
//                        strval = strval.substr(0,(ri+1))+','+strval.substr((ri+1),strval.length);
//                    }
//                }
//                
//                //add float
//                if (flagfloat) {
//                    if(dp > 2){
//                        //del float
//                        strval = strval.substr(0,strval.length-(dp-2));
//                    }
//                    for (var i = 0; i < (2 - dp); i++) {
//                        strval += '0';
//                    }
//                } else {
//                    strval += '.00';
//                }
//            } else {
//                strval = '0.00';
//            }
//            input.val(String(strval));
//        }
        if(input.val()=="")return false;
        input.val(numeral(input.val()).floor().format('0,0.00'));
    }
    //get info for send data
    function serializeData() {
        if ($("input[name^='acc-'][month]").length > 0)
        {
            var allin = $("input[name^='acc-'][month]");
            var data = [];
            allin.each(function () {
                var accid = $(this).attr("name").replace("acc-", "");
                var month = $(this).attr("month");
                var value = $(this).val().replace(',', '').replace(',', '').replace(',', '');
                data.push({accid: accid, month: month, value: value});
            });
            return data;
        }
        return false;
    }
    function sumNow() {
        var data = serializeData();
        var out = [];
        if (!data)
            return false;
        for (var i in data) {
            var accid = data[i].accid;
            var temp = 0;
            for (var j in data) {
                if (data[j].accid == accid)
                    temp += Number(data[j].value);
            }
            out.push({accid: accid, value: temp});
        }
        //update
        for (var i in out) {
            $(".current[aid=" + out[i].accid + "] > input").val(out[i].value);
            formatDec($(".current[aid=" + out[i].accid + "] > input"));
        }
        return out;

    }
    function chkOver(curinput, full, goto, popup) {
        var aid = $(curinput).parent().attr("aid");
        var limit = $(curinput).parent().siblings("span.limit").children().val().replace(',', '').replace(',', '').replace(',', '');
        limit = Number(limit);
        //var current = $(curinput).val().replace(',', '').replace(',', '').replace(',', '');
        var current = numeral($(curinput).val()).value();
        //current = Number(current);
        if (full) {
            if (current == limit) {
                return true;
            } else {
                if (current > limit) {
                    if(popup)
                        alert("ยอดรวมของงบประมาณมีค่ามากกว่าเป้าหมายรายปีที่ได้กำหนดไว้ กรุณากลับไปแก้ไข");
                } else if (current < limit) {
                    if(popup)
                        alert("ยอดรวมของงบประมาณมีค่าน้อยกว่าเป้าหมายรายปีที่ได้กำหนดไว้ กรุณากลับไปแก้ไข");
                }
                if (goto == true)
                    gototab($("input[name=acc-" + aid + "][month]")[0]);
                return false;
            }
        } else {
            if (current > limit) {
                if(popup)
                    alert("ยอดรวมของงบประมาณมีค่ามากกว่าเป้าหมายรายปีที่ได้กำหนดไว้ กรุณากลับไปแก้ไข");
                if (goto == true)
                    gototab($("input[name=acc-" + aid + "][month]")[0]);
                return false;
            } else {
                return true;
            }
        }
    }
    function gototab(txtbox) {
        //get month
        console.log(txtbox);
        var month = $(txtbox).attr("month");
        var tab = month - 9;
        if (tab < 1)
            tab = month + 3;
        var currenttab = $(".swMain2").smartWizard("currentStep");
        if (tab != currenttab)
            $(".swMain2").smartWizard("goToStep", tab);
        //console.log(tab);
        //console.log(txtbox);
        txtbox.focus();
    }
    function ReqData() {
        $(".loading").show();
        $.ajax({
            url: "../../../Data/FillMonthGoalInput",
            type: 'POST',
            async: false,
            data: {
                year: year,
                round: round,
                cid: cid
            },
            success: function (data, textStatus, jqXHR) {
                $("#mginput").html(data);
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
                    onShowStep: function () {
                        var current = $(".swMain2").smartWizard("currentStep");
                        //focus first element in tab
                        //get month of tab
                        var month = current + 9;
                        if (month > 12)
                            month = current - 3;
                        var input = $("input[month=" + month + "]")[0];
                        if ($("input[month=" + month + "]:focus").length == 0)
                            input.focus();
                    }, // triggers when showing a step
                    onFinish: onFinish // triggers when Finish button is clicked 
                });
                ulcollapse($(".sh"));
            }
        });
        //get info if method is 'edit'
        if (method == 'edit') {
            $.ajax({
                url: "../../../Data/MgInfo",
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
                        for (var i in data) {
                            var accid = data[i].accid,
                                    month = data[i].month,
                                    value = data[i].value
                                    ;
                            //assign value
                            $("input[type=text][name=acc-" + accid + "][month=" + month + "]").val(value).change();
                        }
                        $("input[type=text][name=acc-" + data[0].accid + "][month=10]").focus();
                    }
                }, dataType: 'JSON'

            });
        }
        $(".loading").hide();
    }
    function onFinish() {
        //console.log(serializeData());
        $(".loading").show();
        var correct = true;
        $("input[name^='acc-'][month]").each(function () {
            if (!chkinputasdecimal($(this))) {
                alert("กรุณากรอกข้อมูลให้ครบถ้วนสมบูรณ์");
                //$(this).focus();
                gototab($(this));
                correct = false;
                return false;
            }
        });
        if (!correct) {
            $(".loading").hide();
            return false;
        }
        correct = true;
        var out = sumNow();
        for (var i in out) {
            var accid = out[i].accid;
            var curinput = $("span[aid=" + accid + "].current").children("input");
            if (!chkOver(curinput, true, true, true)) {
                correct = false;
                $(".loading").hide();
                return false;
            }
        }
        if (!correct) {
            $(".loading").hide();
            return false;
        }
        var predata = serializeData();
        //console.log(predata);return;
        if (!predata) {
            alert("serializaData has fail");
            $(".loading").hide();
            return false;
        } else {
            $.ajax({
                url: "../../../Data/MgSave",
                async: false,
                type: 'POST',
                data: {
                    year: year,
                    round: round,
                    cid: cid,
                    detail: predata,
                    method: method
                }, success: function (data, textStatus, jqXHR) {
                    if (data == 1) {
                        alert("บันทึกสำเร็จ");
                        window.location = $("#mginput").attr("url");
                    } else {
                        alert("บันทึกล้มเหลว");
                        console.log(data);
                    }
                    $(".loading").hide();
                }
            });
        }

    }
    //version
    function ReqVersion() {
        $(".loading").show();
        $.ajax({
            url: "../../../Data/MgFillVersionSelector",
            type: 'POST',
            async: false,
            data: {
                year: $("#iyear").val() - 543,
                div: cid
            }, success: function (data, textStatus, jqXHR) {
                $("#iver").html(data);
            }
        });
        $(".loading").hide();
    }
    function input_change_not_alert(input){
        formatDec($(input), true);
        if (chkinputasdecimal($(input), false)) {
            sumNow();
            //checkoverflow 
            var currents = $(input).siblings("span.current").children("input");
        }
    }
    //event
    $("#mginput").on("change", "input[name^='acc-'][month]", function () {
        formatDec($(this), false);
        if (chkinputasdecimal($(this), false)) {
            sumNow();
            //checkoverflow 
            var currents = $(this).siblings("span.current").children("input");
            
            if(!chkOver(currents, false, false, false))
                $(this).siblings("span.err").text(" ยอดรวมของงบประมาณมีค่ามากกว่าเป้าหมายรายปีที่ได้กำหนดไว้");
            else{
                $(this).siblings("span.err").text("");
            }
        }
    });
    $("#mginput").on("focusout", "input[name^='acc-'][month]", function () {
        chkinputasdecimal($(this), true);
    });
    $("#msubmit").click(function () {
        var version = $("#iver").val();
        $(".loading").show();
        if (version == 0) {
            alert("กรุณาเลือกเวอร์ชั่นก่อนตกลง");
        } else {
            $.ajax({
                url: "../../../Data/MgFillValueFromVersion",
                type: 'POST',
                async: false,
                data: {
                    year: $("#iyear").val() - 543,
                    cid: cid,
                    ver: version
                }, success: function (data, textStatus, jqXHR) {
                    if (data == "error") {
                        alert("ไม่สามารถเรียกข้อมูลเดิมมาได้");
                        return false;
                    } else {
                        data = JSON.parse(data);
                        for (var i in data) {
                            var accid = data[i].accid,
                                    month = data[i].month,
                                    value = data[i].value;
                            //assign value
                            $("input[type=text][name=acc-" + accid + "][month=" + month + "]").val(value);
                        }
                        for (var i in data) {
                            var accid = data[i].accid,
                                    month = data[i].month,
                                    value = data[i].value;
                            //assign value
                            $("input[type=text][name=acc-" + accid + "][month=" + month + "]").change();
                        }
                        $("input[type=text][name=acc-" + data[0].accid + "][month=10]").focus();
                    }
                }
            });
        }
        $(".loading").hide();
    });
    $("#iyear").change(function () {
        ReqVersion();
    });
    //load data from other month
    $("#mginput").on("change", ".chkother", function () {
        var state = $(this).is(":checked");
        var thismonth = $(this).attr("m");
        var selmonth = $(this).parent().next(".fmonth");
        if (state)
            selmonth.show();
        else
            selmonth.hide();

    });
    $("#mginput").on("change", ".fmonth", function () {
        var targetmonth = $(this).prev("label").children(".chkother").attr("m");
        var selmonth = $(this).val();
        if (selmonth != 0) {
            var data = serializeData();
            for (var i in data) {
                var accid = data[i].accid,
                        month = data[i].month,
                        value = data[i].value
                        ;
                if (month == selmonth) {
                    ($("input[type=text][name=acc-" + accid + "][month=" + targetmonth + "]").val(value).change());
                }
            }
        }
    });
    //main
    ReqData();

});