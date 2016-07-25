function trig_mg_ulcollapse(target){
    target.on("click",function(e){
        e.preventDefault();
        var twins = $(this).parent().children("label").children(".chkacc");
        twins = $(twins).attr("name");
        $(".chkacc[name="+twins+"]").parent().parent().children("a.sh").trigger("uicollapse");
    });
}
function mg_ulcollapse(target){
    target.bind("uicollapse",function(e){
        var sib = $(this).siblings('ul');
        var icon = $(this).children('i');
        var state = icon.hasClass("glyphicon-plus");//if plus mean next to show (minus)
        if(state)
        {
            sib.slideDown();
            icon.removeClass("glyphicon-plus");
            icon.addClass("glyphicon-minus");
        }else{
            sib.slideUp();
            icon.removeClass("glyphicon-minus");
            icon.addClass("glyphicon-plus");
        }
    });
}
function mg_checktree(target){
    
}
function trig_mg_checktree(target){
    target.on("click", function(e){
        var twins = $(this).attr("name");
        $(".chkacc[name="+twins+"]").prop({checked: $(this).is(":checked")}).change();
    });
}

$(function () {
    var year = $("#mginput").attr("year");
    var round = $("#mginput").attr("round");
    var cid = $("#mginput").attr("cid");
    var method = $("#mginput").attr("method");

    function chkinputasdecimal(input, msg) {
        var val = $(input).val().replace(',', '').replace(',', '').replace(',', '');
        var sib = $(input).siblings('span.err');
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
        sib.text("");
        return true;
    }
    function formatDec(input, ignornull) {
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
        console.log(method);
        $(".loading").show();
        $.ajax({
            url: "../MonthGoal/FillMonthGoalInputNewNew",
            type: 'POST',
            async: false,
            data: {
                year: year,
                round: round,
                cid: cid
            },
            success: function (data, textStatus, jqXHR) {
                $("#mginput").html(data);
                $(".checkbox-tree").checktree();
                trig_mg_checktree($(".chkacc"));
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
                        if ($("input[month=" + month + "]:visible:focus").length == 0)
                            $("input[month=" + month + "]:visible:first").focus();
                    }, // triggers when showing a step
                    onFinish: onFinish // triggers when Finish button is clicked 
                });
                mg_ulcollapse($(".sh"));
                trig_mg_ulcollapse($(".sh"));
                
            }
        });
        //get info if method is 'edit'
        if (method == 'edit') {
            $.ajax({
                url: "../MonthGoal/MgInfo",
                async: false,
                type: 'POST',
                data: {
                    year: year,
                    round: round,
                    cid: cid
                }, success: function (data, textStatus, jqXHR) {
                    if (data == "error") {
                        alert("ไม่สามารถเรียกข้อมูลเดิมมาได้ กรุณาลบ แล้วทำการกำหนดค่าใหม่");
                        return false;
                    } else {
                        //collapse all
                        $("#step-1").find(".sh").click();
                        for (var i in data) {
                            var accid = data[i].accid,
                                    month = data[i].month,
                                    quantity = data[i].quantity,
                                    quantity_net = data[i].quantity_net,
                                    value = data[i].value
                                    ;
                            //change state of checkbox (checktree)
                            if(month==10){$("#step-1").find("input[name="+accid+"]").prop({checked: false}).click();}
                            //assign value
                            $("input[type=text][name=acc-" + accid + "][month=" + month + "][cat=v]").val(value).change();
                            if(quantity != null) $("input[type=text][name=acc-" + accid + "][month=" + month + "][cat=q]").val(quantity).change();
                            if(quantity_net != null) $("input[type=text][name=acc-" + accid + "][month=" + month + "][cat=n]").val(quantity_net).change();
                        }
                        $("input[type=text][name=acc-" + data[0].accid + "][month=10]").focus();
                    }
                }, dataType: 'JSON'

            });
        }
        $(".loading").hide();
    }
    function onFinish() {
        
        //collect acc_id selected
        var accs = [];
        var selected_acc = $("#step-1").find("input[type=checkbox].chkacc:checked");
        if(!selected_acc.length){
            alert("กรุณาเลือกและกรอกข้อมูลงบประมาณอย่างน้อย 1 บัญชีก่อนทำการบันทึก");
            $(".swMain2").smartWizard("goToStep", 1);
            return false;
        }
        $(selected_acc).each(function(){
            accs.push($(this).attr("name"));
        });
        delete(selected_acc);
        var package_passed = true;
        //get value and quanlity from txtbox follow acc_id by month into package
        var package = [];
        for (var i in accs){
            //check empty value
            var empty_input = $("input[name=acc-"+accs[i]+"][month]").filter(function(){return !$(this).val();});
            if(empty_input.length) {chkinputasdecimal(empty_input[0]);gototab(empty_input[0]); package_passed = false; return false;}
            delete(empty_input);
            $("input[name=acc-"+accs[i]+"][month][cat]").each(function(){
                var acc_id  = $(this).attr("name").replace("acc-", '');
                var month = $(this).attr("month");
                var value,quantity, quantity_net;
                if($(this).attr('cat')=='v')
                    value = numeral($(this).val()).value();
                else if($(this).attr('cat')=='q')
                    quantity = numeral($(this).val()).value();
                else 
                    quantity_net = numeral($(this).val()).value();
                
                package.push({acc_id: acc_id, 
                    month: month, 
                    value: value, 
                    quantity: quantity, 
                    quantity_net: quantity_net});
            });
        }
        if(!package_passed) return false;
        //send package data to php
        $.ajax({
            url: "../MonthGoal/MgSave",
            type: 'POST',
            async: false,
            data:{
                year: year,
                round: round,
                cid: cid,
                method: method,
                detail: package
            },
            beforeSend: function (xhr) {
                $('.loading').show();
            },
            success: function (data, textStatus, jqXHR) {
                if(data == 'ok'){
                    alert("การบันทึกข้อมูลสำเร็จ");
                    $("#monthgoalform").html("").hide();
                    $("#selbudyear").change();
                    $("#selector").show();
                    $("#saveopt").hide();
                }else{
                    alert("การบันทึกข้อมูลล้มเหลว กรุณาลองอีกครั้ง");
                    console.log(data);
                }
            },complete: function (jqXHR, textStatus) {
                $(".loading").hide();
            }
        });
    }
  
    //version
    function ReqVersion() {
        $(".loading").show();
        $.ajax({
            url: "../MonthGoal/MgFillVersionSelector",
            type: 'POST',
            async: false,
            data: {
                cid: cid
            }, success: function (data, textStatus, jqXHR) {
                if(data.error == 'empty'){
                    $("#btn_recover").hide();
                }else{
                    $("#btn_recover").show();
                    $("#select_r_year").html("");
                    $("#select_r_round").html("");
                    $("#select_r_version").html("");
                    $("#select_r_subversion").html("");
                    
                    var vs = data.info;
                    console.log(vs);
                    
                    for (var year in vs){
                        var y = $("<option/>", {value: year}).text(Number(year)+543);
                        $("#select_r_year").append(y);
                        delete(y);
                        
                        for(var round in vs[year]){
                            var r = $("<option/>", {value: round, year: year}).text(round).hide();
                            $("#select_r_round").append(r);
                            delete(r);
                            
                            for(var version in vs[year][round]){
                                var ver = $("<option/>", {value: version, year: year, round: round}).text(version).hide();
                                $("#select_r_version").append(ver);
                                delete(ver);
                                
                                for(var i in vs[year][round][version]){
                                    var subversion = vs[year][round][version][i];
                                    var sub = $("<option/>", {value: subversion, year: year, round: round, version: version}).text(subversion).hide();
                                    $("#select_r_subversion").append(sub);
                                    delete(sub);
                                    
                                }
                            }
                        }
                    }
//                    for (var i in vs){
//                        var v = vs[i];
//                        var y = $("<option/>", {value: v.year}).text(Number(v.year)+543);
//                        var r = $("<option/>", {value: v.round, year: v.year}).text(v.round).hide();
//                        var ver = $("<option/>", {value: v.version, year: v.year, round: v.round}).text(v.version).hide();
//                        var sub = $("<option/>", {value: v.subversion, year: v.year, round: v.round, version: v.version}).text(v.subversion).hide();
//                        
//                        $("#select_r_year").append(y);
//                        $("#select_r_round").append(r);
//                        $("#select_r_version").append(ver);
//                        $("#select_r_subversion").append(sub);
//                    }
                    $("#select_r_year").val("");
                    $("#select_r_round").val("");
                    $("#select_r_version").val("");
                    $("#select_r_subversion").val("");
                    
                }
            },dataType: 'json'
        });
        $(".loading").hide();
    }

    //event
    $("#select_r_year").unbind();
    $("#select_r_round").unbind();
    $("#select_r_version").unbind();
    $("#select_r_subversion").unbind();
    $("#select_r_year").on("click, change", function(){
        var year = $(this).val();
        //hide all option of round
        $("#select_r_round option").hide();
        $("#select_r_version option").hide();
        $("#select_r_subversion option").hide();
        $("#select_r_round").val("");
        $("#select_r_version").val("");
        $("#select_r_subversion").val("");
        $("#select_r_round option[year="+year+"]").show();
    });
    $("#select_r_round").on("click, change", function(){
        var year = $("#select_r_year").val();
        var round = $(this).val();
        $("#select_r_version option").hide();
        $("#select_r_subversion option").hide();
        $("#select_r_version").val("");
        $("#select_r_subversion").val("");
        $("#select_r_version option[year="+year+"][round="+round+"]").show();
        console.log("#select_r_version option[year="+year+"][round="+round+"]");
        
    });
    $("#select_r_version").on("click, change", function(){
        var year = $("#select_r_year").val();
        var round = $("#select_r_round").val();
        var version = $(this).val();
        $("#select_r_subversion option").hide();
        $("#select_r_subversion").val("");
        $("#select_r_subversion option[year="+year+"][round="+round+"][version="+version+"]").show();
        console.log("#select_r_subversion option[year="+year+"][round="+round+"][version="+version+"]");
    });
    $("#btn_recover_ok").click(function(){
        var subversion = $("#select_r_subversion").val();
        
        if(subversion === null){
            alert("กรุณาเลือกเวอร์ชันการเรียกคืนข้อมูลก่อนตกลง");
            return false;
        }else{
            //request month_goal info for subversion
            var year = $("#select_r_year").val();
            var round = $("#select_r_round").val();
            var version = $("#select_r_version").val();
            $.ajax({
                url: "../MonthGoal/MgVersionInfo",
                type: 'POST',
                async: false,
                beforeSend: function (xhr) {
                    $(".loading").show();
                },
                complete: function (jqXHR, textStatus) {
                    $(".loading").hide();
                },
                dataType: 'json',
                data:{
                    year: year,
                    round: round,
                    version: version,
                    subversion: subversion,
                    cid: cid
                },
                success: function (data, textStatus, jqXHR) {
                    if(data.error == 'empty'){
                        alert("ไม่พบข้อมูลการเรียกคืน");
                        
                    }else{
                        $("#step-1").find("input[type=checkbox]").prop({checked: false}).click();
                        $("#step-1").find(".sh").removeClass("glyphicon-plus").removeClass("glyphicon-minus").addClass("glyphicon-minus").click();
                        //fill info to input 
                        var info = data.info;
                        for(var i in info){
                            var accid = info[i].acc_id;
                            var month = info[i].month_id;
                            var quantity = info[i].quantity;
                            var quantity_net = info[i].quantity_net;
                            var value = info[i].value;
                            
                            if(month==10){$("#step-1").find("input[name="+accid+"]").prop({checked: false}).click();}
                            //assign value
                            $("input[type=text][name=acc-" + accid + "][month=" + month + "][cat=v]").val(value).change();
                            if(quantity != null) $("input[type=text][name=acc-" + accid + "][month=" + month + "][cat=q]").val(quantity).change();
                            if(quantity_net != null) $("input[type=text][name=acc-" + accid + "][month=" + month + "][cat=n]").val(quantity_net).change();
                        }
                    }
                }
            });
        }
    });
    
    $("#mginput").on("change", "input[name^='acc-'][month]", function () {
        formatDec($(this), false);
        if (chkinputasdecimal($(this), false)) {
            //sumNow();
            //checkoverflow 
        }
    });
    $("#mginput").on("focusout change", "input[name^='acc-'][month]", function () {
        chkinputasdecimal($(this), true);
    });
    $("#msubmit").click(function () {
        var version = $("#iver").val();
        $(".loading").show();
        if (version == 0) {
            alert("กรุณาเลือกเวอร์ชั่นก่อนตกลง");
        } else {
            $.ajax({
                url: "../Data/MgFillValueFromVersion",
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
    ReqVersion();
    
    $("#btnsave").unbind();
    $("#btnsave").click(function(){
        if(confirm("ยืนยันการบันทึก")){
            $("#btn_recover_close").click();
            onFinish();
        }
    });

});