$(function () {
    function ReqData() {
        $("#divbody").html('<image src="./../images/loading.gif" style="width:20%; height:20%">');
        search = {
            name: $("#fdname").val(),
            erp: $("#fderp").val(),
            par: $("#fdpar").val(),
            office: $("#fdof").val(),
            ispos: $("#fdispos").val()
        }
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
        var val = false;
        if (div.val() == "") {
            dsib.text("กรุณากรอกชื่อตำแหน่ง/แผนก/กอง/ฝ่าย");
            par.addClass("has-error");
            par.removeClass("has-success");
            val = false;
        } else {
            $.ajax({
                url: "../Valid/ChkDivNameDup",
                type: 'POST',
                async: false,
                data: {divname: div.val()},
                success: function (data) {
                    if (data == 'dup') {
                        dsib.text("ตำแหน่ง/แผนก/กอง/ฝ่ายนี้มีอยู่แล้วในระบบ กรุณากรอกชื่อตำแหน่ง/แผนก/กอง/ฝ่ายใหม่");
                        par.addClass("has-error");
                        par.removeClass("has-success");
                        val = false;
                    }
                    else {
                        dsib.text("");
                        par.addClass("has-success");
                        par.removeClass("has-error");
                        val = true;
                    }
                }
            });
        }
        return val;
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
            val = false;
            $.ajax({
                url: '../Valid/ChkDivErpDup',
                type: 'POST',
                async: false,
                data: {erpid: erp.val()},
                success: function (data, textStatus, jqXHR) {
                    if (data == 'dup') {
                        sib.text("รหัส ERP นี้มีอยู่แล้วในระบบ กรุณากรอกรหัส ERP ใหม่");
                        par.addClass("has-error");
                        par.removeClass("has-success");
                        val = false;
                    }
                    else {
                        sib.text("");
                        par.addClass("has-success");
                        par.removeClass("has-error");
                        val = true;
                    }
                }
            });
            return val;
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
        return true;
    }
    $("#adderp").change(function () {
        checkerp($(this), getCheckbox($("#addhaserp")));
    });
    $("#addname").change(function () {
        checkname($(this));
    });
    $("#addoffice").change(function () {
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
    $("#addisdiv").change(function () {
        if (getCheckbox($(this)) == true) {
            $("#modaladd").find(".subo").hide();
            $("#addhaserp").prop("checked", true).change();
            $("#addhaserp").prop("disabled", true).change();
        }
        else {
            $("#modaladd").find(".subo").show();
            $("#addhaserp").prop("checked", false).change();
            $("#addhaserp").prop("disabled", false).change();
        }
    });
    $("#addispos").change(function () {
        if (getCheckbox($(this)) == true)
        {
            $("#addisdiv").prop("checked", false).change();
            $("#addisdiv").prop("disabled", true).change();
        } else {
            $("#addisdiv").prop("disabled", false).change();
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
                    isdiv: getCheckbox($("#addisdiv")),
                    ispos: getCheckbox($("#editispos"))
                },
                success: function (data) {
                    if (data == 'ok') {
                        divname.val("");
                        diverp.val("");
                        divoffice.val("");
                        divpar.val("");
                        $("#addhaserp").prop("checked", false).change();
                        ReqData();
                        alert("การเพิ่มข้อมูลสำเร็จ");
                        $("#modaladd").modal('hide');
                        $("#modaladd").find("div.form-group-sm");
                        $("#modaladd").find("div.form-group-sm").find(".feedback").text("");
                        $("#modaladd").find("div.form-group-sm").removeClass("has-error");
                        $("#modaladd").find("div.form-group-sm").removeClass("has-success");
                    } else {
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
                d = data;
                ediverp = d.erp_id;
                edivname = d.divname;
                $("#editname").val(d.divname).change();
                if (d.erp_id == '')
                {
                    $("#edithaserp").prop("checked", false).change();
                    $("#editerp").val(d.erp_id).change();
                } else {
                    $("#edithaserp").prop("checked", true).change();
                    $("#editerp").val(d.erp_id).change();
                }
                if (d.par_id == 0)
                {
                    $("#editisdiv").prop("checked", true).change();
                } else {
                    $("#editisdiv").prop("checked", false).change();
                }
                $("#editispos").prop("checked",d.ispos==1?true:false).change();
                $("#editoffice").val(d.office_id).change();
                $("#editpar").children('option[value^="' + d.divid + '"]').attr("disabled", true);
                $("#editpar").val(d.par_id);
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
            $.ajax({
                url: "../Valid/ChkDivNameDup",
                type: 'POST',
                async: false,
                data: {divname: div.val()},
                success: function (data) {
                    if (data == 'dup') {
                        dsib.text("ตำแหน่ง/แผนก/กอง/ฝ่ายนี้มีอยู่แล้วในระบบ กรุณากรอกชื่อตำแหน่ง/แผนก/กอง/ฝ่ายใหม่");
                        par.addClass("has-error");
                        par.removeClass("has-success");
                        val = false;
                    }
                    else {
                        dsib.text("");
                        par.addClass("has-success");
                        par.removeClass("has-error");
                        val = true;
                    }
                }
            });
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
            val = false;
            $.ajax({
                url: '../Valid/ChkDivErpDup',
                type: 'POST',
                async: false,
                data: {erpid: erp.val()},
                success: function (data, textStatus, jqXHR) {
                    if (data == 'dup') {
                        sib.text("รหัส ERP นี้มีอยู่แล้วในระบบ กรุณากรอกรหัส ERP ใหม่");
                        par.addClass("has-error");
                        par.removeClass("has-success");
                        val = false;
                    }
                    else {
                        sib.text("");
                        par.addClass("has-success");
                        par.removeClass("has-error");
                        val = true;
                    }
                }
            });
            return val;
        }
    }
    $("#editisdiv").change(function () {
        if (getCheckbox($(this)) == true) {
            $("#modaledit").find(".subo").hide();
            $("#edithaserp").prop("checked", true).change();
            $("#edithaserp").prop("disabled", true).change();
        }
        else {
            $("#modaledit").find(".subo").show();
            $("#edithaserp").prop("checked", false).change();
            $("#edithaserp").prop("disabled", false).change();
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
    $("#editispos").change(function () {
        if (getCheckbox($(this)) == true)
        {
            $("#editisdiv").prop("checked", false).change();
            $("#editisdiv").prop("disabled", true).change();
        } else {
            $("#editisdiv").prop("disabled", false).change();
        }
    });
    $("#editerp").change(function () {
        checkerpedit($(this), getCheckbox($("#edithaserp")));
    });
    $("#editname").change(function () {
        checknameedit($(this));
    });
    $("#editoffice").change(function () {
        checkerpoffice($(this));
    });
    $("#btnedit").click(function () {
        divname = $("#editname");
        diverp = $("#editerp");
        divoffice = $("#editoffice");
        divpar = $("#editpar");
        
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
                    isdiv: getCheckbox($("#editisdiv")),
                    ispos: getCheckbox($("#editispos"))
                },
                success: function (data) {
                    if (data == '1') {
                        divname.val("");
                        diverp.val("");
                        divoffice.val("");
                        divpar.val("");
                        $("#edithaserp").prop("checked", false).change();
                        ReqData();
                        alert("การแก้ไขข้อมูลสำเร็จ");
                        $("#modaledit").modal('hide');
                        $("#modaledit").find("div.form-group-sm");
                        $("#modaledit").find("div.form-group-sm").find(".feedback").text("");
                        $("#modaledit").find("div.form-group-sm").removeClass("has-error");
                        $("#modaledit").find("div.form-group-sm").removeClass("has-success");
                    } else {
                        alert("การแก้ไขข้อมูลล้มเหลว กรุณาลองใหม่");
                    }
                }
            });
        }
    });
});