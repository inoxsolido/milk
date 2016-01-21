$(function () {
    function ReqData() {
        $("#divbody").html('<image src="./../images/loading.gif" style="width:20%; height:20%">');
        search = {
            name: $("#fdname").val(),
            erp: $("#fderp").val(),
            par: $("#fdpar").val(),
            office: $("#fdof").val(),
            status: $("#fdstatus").val()
        };
        $.post('./../data/FillDiv',
                {
                    ajax: 0,
                    searchtxt: search
                },
        function (data) {
            $("#divbody").html(data);
        });
        $.post('./../Data/FillDivParentAdd',
                {
                    ajax: 0
                }, function (data) {
            $("#addpar").html(data);
            $("#editpar").html(data);
        });
        $.post('./../Data/FillDivSubParentAdd',
                {
                    ajax: 0
                }, function (data) {
            $("#addsub").html(data);
            $("#editsub").html(data);
        });
    }
    $(".option").hide();
    function getCheckbox(checkbox)
    {
        return checkbox.is(":checked");
    }
    ReqData();
    $("#find").hide();
    $("#findshow").click(function () {
        $("#find").fadeToggle();
        $("#txtfind").focus();
    });
    $("#txtfind").keyup(function (ev) {
        if (ev.keyCode == 13) {
            $("#btnfind").click();
        }
    });
    $("#btnfind").click(function () {
        ReqData();
        $("#find").fadeToggle();
    });
    $("#divbody").on('click', '.deactive', function () {
        $.post('./../data/DivStateChange', {
            divid: $(this).attr('data-id'),
            state: 0
        }, function (data) {
            if (data == 'ok') {
                ReqData();
            }
        });
    });
    $("#divbody").on('click', '.active', function () {
        $.post('./../data/DivStateChange', {
            divid: $(this).attr('data-id'),
            state: 1
        }, function (data) {
            if (data == 'ok') {
                ReqData();
            }
        });
    });
    $("#addm").click(function () {
        $("#modaladd").modal('show');
    });
    function checkname(div) {
        dsib = div.siblings('.feedback');
        par = div.parent();
        if (div.val() == "") {
            dsib.text("กรุณากรอกชื่อตำแหน่ง/แผนก/กอง/ฝ่าย");
            par.addClass("has-error");
            par.removeClass("has-success");
            return false;
        } else {
            dsib.text("");
            par.addClass("has-success");
            par.removeClass("has-error");
            return true;
        }
    }
    function checkerp(erp, erpstate) {
        sib = erp.siblings('.feedback');
        par = erp.parent();
        rule = /^[0-9A-Za-z]{5}/;
        if (erpstate == false)
        {
            erp.val() == "";
            sib.text("");
            par.removeClass("has-success");
            par.removeClass("has-error");
            return true;
        }

        if (erp.val() == "") {
            sib.text("กรุณากรอกรหัส ERP");
            par.addClass("has-error");
            par.removeClass("has-success");
            return false;
        } else if (!rule.test(erp.val())) {
            sib.text("รหัส ERP ต้องเป็นตัวเลขหรือตัวอักษรภาษาอังกฤษ จำนวน 5 หลัก");
            par.addClass("has-error");
            par.removeClass("has-success");
            return false;
        } else {
            sib.text("");
            par.addClass("has-success");
            par.removeClass("has-error");
            return true;
        }


    }
    function checkerpoffice(office)
    {
        sib = office.siblings('.feedback');
        par = office.parent();
        rule = /[0-9]{2}/;
        if (office.val() == "")
        {
            sib.text("กรุณากรอกรหัส ERP ของสำนักงานที่ แผนก หรือ กอง หรือ ฝ่าย สังกัด");
            par.addClass("has-error");
            par.removeClass("has-success");
            return false;
        } else if (!rule.test(office.val()))
        {
            sib.text("กรุณากรอกรหัส ERP ของสำนักงานเป็นตัวเลข จำนวน 2 หลัก");
            par.addClass("has-error");
            par.removeClass("has-success");
            return false;
        } else {
            sib.text("");
            par.removeClass("has-error");
            par.addClass("has-success");
            return true;
        }

    }
    $("#adderp").focusout(function () {
        checkerp($(this), getCheckbox($("#addhaserp")));
    });
    $("#addname").focusout(function () {
        checkname($(this));
    });
    $("#addoffice").focusout(function () {
        checkerpoffice($(this));
    });
    $("#addhaserp").change(function () {

        if (getCheckbox($(this)) == true)
        {
            $("#modaladd").find(".option").show();
        } else {
            $("#modaladd").find(".option").hide();
        }
    });
    $("#addhassub").change(function (){
        if (getCheckbox($(this)) == true)
        {
            $("#modaladd").find(".subp").show();
            $("#modaladd").find(".subo").hide();
        } else {
            $("#modaladd").find(".subp").hide();
            $("#modaladd").find(".subo").show();
        }
    });
    $("input[name=addstatus]").change(function(){
        var val = $(this).val();
        if(val == 3){
            $("#modaladd").find(".subo").hide();
            $("#modaladd").find(".section").show();
            $("#addhaserp").prop("checked", true).change();
            $("#addhaserp").prop("disabled", true).change();
            $("#addhassub").prop("checked", false).change();
            $("#addhassub").prop("disabled", true);
        }else if(val == 4){
            $("#modaladd").find(".subo").show();
            $("#modaladd").find(".section").hide();
            $("#addhaserp").prop("checked", true).change();
            $("#addhaserp").prop("disabled", true).change();
            $("#addhassub").prop("checked", false).change();
            $("#addhassub").prop("disabled", true);
        }else if(val == 1){
            $("#modaladd").find(".subo").show();
            $("#modaladd").find(".section").hide();
            $("#addhaserp").prop("checked", false).change();
            $("#addhaserp").prop("disabled", false).change();
            $("#addhassub").prop("checked", true).change();
            $("#addhassub").prop("disabled", false);
        }else{
            $("#modaladd").find(".subo").show();
            $("#modaladd").find(".section").hide();
            $("#addhaserp").prop("checked", false).change();
            $("#addhaserp").prop("disabled", false).change();
            $("#addhassub").prop("checked", false).change();
            $("#addhassub").prop("disabled", true);
        }
    });
    $("#adderp").keyup(function (event) {
        if (event.keyCode == 13) {
            $("#btnadd").click();
        }
    });
    $("#addname").keyup(function (event) {
        if (event.keyCode == 13) {
            $("#btnadd").click();
        }
    });
    $("#addoffice").keyup(function (event) {
        if (event.keyCode == 13) {
            $("#btnadd").click();
        }
    });
    $("#btnadd").click(function () {
        divname = $("#addname");
        diverp = $("#adderp");
        divoffice = $("#addoffice");
        divpar = $("#addpar");
        if (checkname(divname) && checkerp(diverp, getCheckbox($("#addhaserp"))) && checkerpoffice(divoffice)) {

            $.ajax({
                url: '../Data/AddDiv',
                type: 'post',
                async: false,
                data: {
                    divname: divname.val(),
                    erp: diverp.val(),
                    erpoffice: divoffice.val(),
                    par: divpar.val(),
                    haserp: getCheckbox($("#addhaserp")),
                    dlevel: $("input[name=addstatus]:checked").val(),
                    section: $("#addsection").val(),
                    hassub: getCheckbox($("#addhassub")),
                    subparent: $("#addsub").val()
                },
                success: function (data) {
                    if (data == 'ok') {
                        divname.val("");
                        diverp.val("");
                        divoffice.val("");
                        divpar.val("");
                        $("#addhaserp").prop("checked", false).change();
                        $("input[name=addstatus]").val(1).change();
                        ReqData();
                        alert("การเพิ่มข้อมูลสำเร็จ");
                        $("#modaladd").modal('hide');
                        $("#modaladd").find("div.form-group-sm").find(".feedback").text("");
                        $("#modaladd").find("div.form-group-sm").removeClass("has-error");
                        $("#modaladd").find("div.form-group-sm").removeClass("has-success");
                    } else if (data == 'dup') {
                        alert("ข้อมูลนี้มีอยู่แล้วในระบบ");
                    }
                    else {
                        alert("การเพิ่มข้อมูลล้มเหลว กรุณาลองใหม่");
                    }
                }
            });
        }
    });
    var edivid;
    var ediverp;
    var edivname;
    var ediverp;
    var edivoffice;
    $("#divbody").on("click", ".edit", function () {
        edivid = $(this).attr('data-id');
        $("#editpar").children("option").prop("disabled", false);
        $.ajax({
            url: "../Data/AskDivInfo",
            type: "POST",
            async: false,
            data: {did: edivid},
            success: function (data, textStatus, jqXHR) {
                var d = data;
                ediverp = d.erp_id;
                edivname = d.divname;
                $("#editname").val(d.divname).change();
                $("input[name=editstatus]").prop("checked", false).change();
                $("input[name=editstatus][value="+d.dlevel+"]").prop("checked", true).change();
                
                $("#edithaserp").prop("checked", d.erp_id != '');
                $("#editerp").val(d.erp_id).change();
                //$("#editispos").prop("checked", d.ispos == 1 ? true : false).change();
                $("#editoffice").val(d.office_id).change();
                //$("#editpar").children('option[value^="' + d.divid + '"]').attr("disabled", true);
                $("#editpar").val(d.par_id);
                $("#editsection").val(d.section);
                $("#edithassub").prop("checked" , d.sub!=0).change();
                $("#editsub").val(d.sub);
                
            },
            dataType: 'json'
        });
        $("#modaledit").modal('show');
    });
    function checknameedit(div) {
        dsib = div.siblings('.feedback');
        par = div.parent();
        var val = false;
        if (div.val() == "") {
            dsib.text("กรุณากรอกชื่อตำแหน่ง/แผนก/กอง/ฝ่าย");
            par.addClass("has-error");
            par.removeClass("has-success");
            val = false;
        } else if (div.val() == edivname) {
            dsib.text("");
            par.removeClass("has-error");
            par.addClass("has-success");
            return true;
        } else {
            dsib.text("");
            par.addClass("has-success");
            par.removeClass("has-error");
            return true;
        }
        return val;
    }
    function checkerpedit(erp, erpstate) {
        sib = erp.siblings('.feedback');
        par = erp.parent();
        rule = /^[0-9A-Za-z]{5}/;
        if (erpstate == false)
        {
            erp.val() == "";
            sib.text("");
            par.removeClass("has-success");
            par.removeClass("has-error");
            return true;
        }

        if (erp.val() == "") {
            sib.text("กรุณากรอกรหัส ERP");
            par.addClass("has-error");
            par.removeClass("has-success");
            return false;
        } else if (!rule.test(erp.val())) {
            sib.text("รหัส ERP ต้องเป็นตัวเลขหรือตัวอักษรภาษาอังกฤษ จำนวน 5 หลัก");
            par.addClass("has-error");
            par.removeClass("has-success");
            return false;
        } else if (erp.val() == ediverp) {
            sib.text("");
            par.removeClass("has-error");
            par.addClass("has-success");
            return true;
        } else {
            sib.text("");
            par.addClass("has-success");
            par.removeClass("has-error");
            return true;
        }
    }
    $("#edithassub").change(function (){
        if (getCheckbox($(this)) == true)
        {
            $("#modaledit").find(".subp").show();
            $("#modaledit").find(".subo").hide();
        } else {
            $("#modaledit").find(".subp").hide();
            $("#modaledit").find(".subo").show();
        }
    });
    $("input[name=editstatus]").change(function(){
        var val = $(this).val();
        if(val == 3){
            $("#modaledit").find(".subo").hide();
            $("#modaledit").find(".section").show();
            $("#edithaserp").prop("checked", true).change();
            $("#edithaserp").prop("disabled", true).change();
            $("#edithassub").prop("checked", false).change();
            $("#edithassub").prop("disabled", true);
        }else if(val == 4){
            $("#modaledit").find(".subo").show();
            $("#modaledit").find(".section").hide();
            $("#edithaserp").prop("checked", true).change();
            $("#edithaserp").prop("disabled", true).change();
            $("#edithassub").prop("checked", false).change();
            $("#edithassub").prop("disabled", true);
        }else if(val == 1){
            $("#modaledit").find(".subo").show();
            $("#modaledit").find(".section").hide();
            $("#edithaserp").prop("checked", false).change();
            $("#edithaserp").prop("disabled", false).change();
            $("#edithassub").prop("checked", true).change();
            $("#edithassub").prop("disabled", false);
        }else{
            $("#modaledit").find(".subo").show();
            $("#modaledit").find(".section").hide();
            $("#edithaserp").prop("checked", false).change();
            $("#edithaserp").prop("disabled", false).change();
            $("#edithassub").prop("checked", false).change();
            $("#edithassub").prop("disabled", true);
        }
    });
    $("#edithaserp").change(function () {

        if (getCheckbox($(this)) == true)
        {
            $("#modaledit").find(".option").show();
        } else {
            $("#modaledit").find(".option").hide();
        }
    });
    $("#editerp").focusout(function () {
        checkerpedit($(this), getCheckbox($("#edithaserp")));
    });
    $("#editname").focusout(function () {
        checknameedit($(this));
    });
    $("#editoffice").focusout(function () {
        checkerpoffice($(this));
    });
    
    $("#editerp").keyup(function (event) {
        if (event.keyCode == 13) {
            $("#btnedit").click();
        }
    });
    $("#editname").keyup(function (event) {
        if (event.keyCode == 13) {
            $("#btnedit").click();
        }
    });
    $("#editoffice").keyup(function (event) {
        if (event.keyCode == 13) {
            $("#btnedit").click();
        }
    });
    $("#btnedit").click(function () {
        var divname = $("#editname");
        var diverp = $("#editerp");
        var divoffice = $("#editoffice");
        var divpar = $("#editpar");
        //alert( divpar.val());
        if (checknameedit(divname) && checkerpedit(diverp, getCheckbox($("#edithaserp"))) && checkerpoffice(divoffice)) {

            $.ajax({
                url: '../Data/DivEdit',
                type: 'post',
                async: false,
                data: {
                    divid: edivid,
                    divname: divname.val(),
                    erp: diverp.val(),
                    erpoffice: divoffice.val(),
                    par: divpar.val(),
                    haserp: getCheckbox($("#edithaserp")),
                    dlevel: $("input[name=editstatus]:checked").val(),
                    section: $("#editsection").val(),
                    hassub: getCheckbox($("#edithassub")),
                    subparent: $("#editsub").val()
                },
                success: function (data) {
                    if (data == '1') {
                        divname.val("");
                        diverp.val("");
                        divoffice.val("");
                        divpar.val("");
                        $("#edithaserp").prop("checked", false).change();
                        $("#edithaserp").prop("checked", false).change();
                        $("input[name=editstatus]").val(1).change();
                        ReqData();
                        alert("การแก้ไขข้อมูลสำเร็จ");
                        $("#modaledit").modal('hide');
                        $("#modaledit").find("div.form-group-sm");
                        $("#modaledit").find("div.form-group-sm").find(".feedback").text("");
                        $("#modaledit").find("div.form-group-sm").removeClass("has-error");
                        $("#modaledit").find("div.form-group-sm").removeClass("has-success");
                    } else if (data == 'dup') {
                        alert("การแก้ไขข้อมูลล้มเหลว ข้อมูลนี้มีอยู่แล้วในระบบ");
                    } else {
                        alert("การแก้ไขข้อมูลล้มเหลว กรุณาลองใหม่");
                    }
                }
            });
        }
    });
});