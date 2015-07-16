$(function () {

    $.ajaxSetup({
        error: function (jqXHR, exception) {
            if (jqXHR.status === 0) {
                alert('Not connect.\n Verify Network.');
            } else if (jqXHR.status == 404) {
                alert('Requested page not found. [404]');
            } else if (jqXHR.status == 500) {
                alert('Internal Server Error [500].');
            } else if (exception === 'parsererror') {
                alert('Requested JSON parse failed.');
            } else if (exception === 'timeout') {
                alert('Time out error.');
            } else if (exception === 'abort') {
                alert('Ajax request aborted.');
            } else {
                alert('Uncaught Error.\n' + jqXHR.responseText);
            }
        }
    });

    function getCheckbox(checkbox)
    {
        return checkbox.is(":checked");
    }
    function ReqData() {
        $("#accbody").html('<image src="./../images/loading.gif" style="width:20%; height:20%">');
        search = {
            erp: $("#fderp").val(),
            name: $("#fdname").val(),
            group: $("#fdgroup").val(),
            par: $("#fdpar").val(),
            haspar: getCheckbox($("#fdhaspar"))
        };
        $.post('./../data/FillAcc',
                {
                    ajax: 0,
                    search: search
                },
        function (data) {
            $("#accbody").html(data);
        });
        $.post('./../Data/FillAccPar',
                {
                    ajax: 0
                }, function (data) {
            $("#addpar").html(data);
            $("#editpar").html(data);
        });
    }

    //findzone

    $("#findshow").click(function () {
        $("#find").fadeToggle();
        $("#txtfind").focus();
    });
    $("#fdhaspar").change(function () {
        alert(getCheckbox($(this)));
    });
    $("#btnfind").click(function () {
        ReqData();
        $("#find").fadeToggle();
    });
    //------------
    //-----delete------
    $("#accbody").on("click", ".delete", function () {

        id = $(this).attr("data-id");
        if (confirm("ยืนยันการลบ!?") == true)
        {
            $.ajax({
                url: "../Data/AccountDel",
                async: false,
                type: 'POST',
                data: {
                    ajax: 0,
                    id: id
                },
                success: function (data, textStatus, jqXHR) {
                    if (data == 1)
                    {
                        alert("การลบเสร็จสมบูรณ์");
                        ReqData();
                    } else {
                        alert("การลบล้มเหลว!");
                    }
                }
            });
        }
    });
    /**
     * Check account name
     * 
     * @param {Name} acc info about this parameter
     * @param {Boolean} edit is this function use to for edit zone
     * @return {Boolean} Returns .
     */
    function checkname(acc, edit) {
        sib = acc.siblings('.feedback');
        par = acc.parent();
        if (acc.val() == "") {
            sib.text("กรุณากรอกชื่อบัญชี");
            par.addClass("has-error");
            par.removeClass("has-success");
            return false;
        } else {
            return true // name can be duplicate
            re = false;
            if (edit == false) {
                $.ajax({
                    url: "../Valid/CheckAccNameDup",
                    async: false,
                    type: 'POST',
                    data: {name: acc.val()},
                    success: function (data, textStatus, jqXHR) {
                        if (data == 'dup')
                        {
                            sib.text("ชื่อบัญชีนี้มีอยู่แล้วในระบบ");
                            par.addClass("has-error");
                            par.removeClass("has-success");
                            re = false;
                        } else {

                            sib.text("");
                            par.addClass("has-success");
                            par.removeClass("has-error");
                            re = true;
                        }
                    }
                });
            } else if (edit == true) {
                $.ajax({
                    url: "../Valid/CheckAccNameDupEdit",
                    async: false,
                    type: 'POST',
                    data: {name: acc.val(), id: accid},
                    success: function (data, textStatus, jqXHR) {
                        if (data == 'dup')
                        {
                            sib.text("ชื่อบัญชีนี้มีอยู่แล้วในระบบ");
                            par.addClass("has-error");
                            par.removeClass("has-success");
                            re = false;
                        } else {
                            sib.text("");
                            par.addClass("has-success");
                            par.removeClass("has-error");
                            re = true;
                        }
                    }
                });
            }
            return re;
        }
    }

    /**
     * Check account erp
     * 
     * @param {Name} acc info about this parameter
     * @param {Boolean} erpstate value of #haserp
     * @param {Boolean} edit is this function use to for edit zone
     * @return {Boolean} Returns .
     */
    function checkerp(erp, erpstate, edit) {
        sib = erp.siblings('.feedback');
        par = erp.parent();
        rule = /^[0-9A-Za-z]{8}/;
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
            sib.text("รหัส ERP ต้องเป็นตัวเลขหรือตัวอักษรภาษาอังกฤษ จำนวน 8 หลัก");
            par.addClass("has-error");
            par.removeClass("has-success");
            return false;
        }/* else {
         re=false;
         if (edit == false) {
         
         $.ajax({
         url: "../Valid/CheckAccErpDup",
         async: false,
         type: 'POST',
         data: {erp: erp.val()},
         success: function (data, textStatus, jqXHR) {
         if (data == 'dup')
         {
         sib.text("รหัส ERP นี้มีอยู่แล้วในระบบ");
         par.addClass("has-error");
         par.removeClass("has-success");
         re= false;
         } else {
         sib.text("");
         par.addClass("has-success");
         par.removeClass("has-error");
         re= true;
         }
         }+
         });
         
         } else if (edit == true) {
         $.ajax({
         url: "../Valid/CheckAccErpDupEdit",
         async: false,
         type: 'POST',
         data: {erp: erp.val(), id: accid},
         success: function (data, textStatus, jqXHR) {
         if (data == 'dup')
         {
         sib.text("รหัส ERP นี้มีอยู่แล้วในระบบ");
         par.addClass("has-error");
         par.removeClass("has-success");
         re= false;
         } else {
         sib.text("");
         par.addClass("has-success");
         par.removeClass("has-error");
         re= true;
         }
         }
         });
         }
         return re;
         }
         */
    }
    //----add
    $("#addm").click(function () {
        $("#modaladd").modal('show');
    });
    $("#addhaserp").change(function () {
        val = getCheckbox($(this));
        if (val)
            $("#modaladd").find(".erp").show();
        else
            $("#modaladd").find(".erp").hide();
    });
    $("#addhaspar").change(function () {
        val = getCheckbox($(this));
        if (val) {
            $("#modaladd").find(".par").show();
            $("#modaladd").find(".group").hide();
        }
        else {
            $("#modaladd").find(".group").show();
            $("#modaladd").find(".par").hide();
            $("#addpar").val("").focusout();
            $("#addgroup").val("").focusout();
        }
    });
    $("#addname").focusout(function () {
        checkname($("#addname"), false);
    });
    $("#adderp").focusout(function () {
        checkerp($("#adderp"), getCheckbox($("#addhaserp")), false);
    });


    $("#btnadd").click(function () {
        var name = $("#addname").val();
        var erp = $("#adderp").val();
        var par = $("#addpar").val();
        var group = $("#addgroup").val();
        var haserp = getCheckbox($("#addhaserp"));
        var haspar = getCheckbox($("#addhaspar"));
        var namestate = checkname($("#addname"), false);
        var erpstate = checkerp($("#adderp"), haserp, false);
        if (!haspar && group < 1){
            alert('กรุณาเลือกหมวด');
        }
        if (namestate && erpstate) {
            d = {
                name: name,
                erp: erp,
                par: par,
                group: group,
                haserp: haserp,
                haspar: haspar
            };
            $.ajax({
                url: "../Data/AccountAdd",
                async: false,
                type: 'POST',
                data: {
                    d: d
                },
                success: function (data, textStatus, jqXHR) {
                    if (data == 'ok') {
                        $("#addname").val("");
                        $("#adderp").val("");
                        $("#addpar").val("");
                        $("#addgroup").val("");
                        $("#addhaserp").prop("checked", true).change();
                        $("#addhaspar").prop("checked", true).change();
                        ReqData();
                        alert("การเพิ่มข้อมูลสำเร็จ");
                        $("#modaladd").modal('hide');
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

    //---edit
    $("#edithaserp").change(function () {
        val = getCheckbox($(this));
        if (val)
            $("#modaledit").find(".erp").show();
        else
            $("#modaledit").find(".erp").hide();
    });
    $("#edithaspar").change(function () {
        val = getCheckbox($(this));
        if (val) {
            $("#modaledit").find(".par").show();
            $("#modaledit").find(".group").hide();
        }
        else {
            $("#modaledit").find(".group").show();
            $("#modaledit").find(".par").hide();
            $("#editpar").val("").focusout();
            $("#editgroup").val("").focusout();
        }
    });
    $("#editname").focusout(function () {
        checkname($("#editname"), false);
    });
    $("#editerp").focusout(function () {
        checkerp($("#editerp"), getCheckbox($("#edithaserp")), true);
    });

    var accid;
    $("#accbody").on("click", ".edit", function () {
        accid = $(this).attr('data-id');
        $.ajax({
            url: "../Data/AskAccInfo",
            async: false,
            type: 'POST',
            data: {id: accid},
            success: function (data, textStatus, jqXHR) {
                $("#editname").val(data.name);
                if (data.erp != null) {
                    $("#edithaserp").prop("checked", true).change();
                    $("#editerp").val(data.erp).focusout();
                } else
                    $("#edithaserp").prop("checked", false).change();
                if (data.par != null) {
                    $("#edithaspar").prop("checked", true).change();
                    $("#editpar").val(data.par).change();
                    $("#editgroup").val(data.group);
                } else
                    $("#edithaspar").prop("checked", false).change();

            },
            dataType: 'json'
        });
        $("#modaledit").modal("show");
    });

    $("#btnedit").click(function () {
        var name = $("#editname").val();
        var erp = $("#editerp").val();
        var par = $("#editpar").val();
        var group = $("#editgroup").val();
        var haserp = getCheckbox($("#edithaserp"));
        var haspar = getCheckbox($("#edithaspar"));
        if (!haspar && group < 1) {
            alert('กรุณาเลือกหมวด');
        }
        if (checkname($("#editname"), true) && checkerp($("#editerp"), haserp, true)) {
            $.ajax({
                url: "../Data/AccountEdit",
                async: false,
                type: 'POST',
                data: {
                    d: {
                        id: accid,
                        name: name,
                        erp: erp,
                        par: par,
                        group: group,
                        haserp: haserp,
                        haspar: haspar
                    }
                },
                success: function (data, textStatus, jqXHR) {
                    if (data == 'ok') {
                        $("#editname").val("");
                        $("#editerp").val("");
                        $("#editpar").val("");
                        $("#editgroup").val("");
                        $("#edithaserp").prop("checked", true).change();
                        $("#edithaspar").prop("checked", true).change();
                        ReqData();
                        alert("การแก้ไขข้อมูลสำเร็จ");
                        $("#modaledit").modal('hide');
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




    //--main
    ReqData();
    $("#find").hide();
    $("#addhaserp").prop("checked", true).change();
    $("#addhaspar").prop("checked", true).change();
    $("#edithaserp").prop("checked", true).change();
    $("#edithaspar").prop("checked", true).change();




});