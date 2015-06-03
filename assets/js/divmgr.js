$(function () {
    function ReqData() {
        $("#divbody").html("<image src='../../images/loading.gif' style='width:20%; height:20%'>");
        $.post('./../data/FillDiv',
                {
                    ajax: 0,
                    searchtxt: $("#txtfind").val()
                },
        function (data) {
            $("#divbody").html(data);
        });
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

    var divname = $("#addname");
    var diverp = $("#adderp");
    function checkname(div) {
        dsib = div.siblings('.feedback');
        par = div.parent();
        if (div.val() == "") {
            dsib.text("กรุณากรอกชื่อฝ่าย");
            par.addClass("has-error");
            par.removeClass("has-success");
            return false;
        } else {
            $.post('../Valid/ChkDivNameDup', {divname: div.val()}, function (data) {
                if (data == 'dup') {
                    dsib.text("ฝ่ายนี้มีอยู่แล้วในระบบ กรุณากรอกชื่อฝ่ายใหม่");
                    par.addClass("has-error");
                    par.removeClass("has-success");
                    return false;
                }
                else {
                    dsib.text("");
                    par.addClass("has-success");
                    par.removeClass("has-error");
                    return true;
                }
            });
        }
    }
    function checkerp(erp) {
        sib = erp.siblings('.feedback');
        par = erp.parent();
        rule = /^[0-9]/;
        if (erp.val() == "") {
            sib.text("กรุณากรอกรหัส ERP");
            par.addClass("has-error");
            par.removeClass("has-success");
            return false;
        } else if (!rule.test(erp.val())) {
            sib.text("รหัส ERP ต้องเป็นตัวเลขเท่านั้น");
            par.addClass("has-error");
            par.removeClass("has-success");
            return false;
        } else {
            $.post('../Valid/ChkDivErpDup', {erpid: erp.val()}, function (data) {
                if (data == 'dup') {
                    sib.text("รหัส ERP นี้มีอยู่แล้วในระบบ กรุณากรอกรหัส ERP ใหม่");
                    par.addClass("has-error");
                    par.removeClass("has-success");
                    return false;
                }
                else {
                    sib.text("");
                    par.addClass("has-success");
                    par.removeClass("has-error");
                    return true;
                }
            });
        }
    }
    divname.focusout(function () {
        checkname($(this));
    });
    diverp.focusout(function () {
        checkerp($(this));
    });
    $("#btnadd").click(function () {
        if (checkname(divname) && checkerp(diverp)) {
            $.post('../Data/AddDiv', {
                divname: divname.val(),
                erp: diverp.val()
            }, function (data) {
                if (data == 'ok') {
                    divname.val("");
                    diverp.val("");
                    ReqData();
                    alert("การเพิ่มข้อมูลสำเร็จ");
                    $("modaladd").modal('hide');
                    $(".feedback").txt("");
                    $(".feedback").parent().removeClass("has-error");
                    $(".feedback").parent().removeClass("has-success");
                } else {
                    alert("การเพิ่มข้อมูลล้มเหลว กรุณาลองใหม่");
                }
            });
        }
    });
    var divid;
    var edivname = $("#editname");
    var ediverp = $("#editerp");
    $("#divbody").on("click", ".edit", function () {
        divid = $(this).attr('data-id');
        $("#modaledit").modal('show');
        $.post('../Data/AskDivInfo',
        {
            did:divid
        },function(data){
            edivname.val(data.divname);
            ediverp.val(data.erp_id);
        },'json'
        );
    });
    $("#btnedit").click(function () {
        if (edivname.val() == "") {
            alert("กรุณากรอกชื่อฝ่าย");
        } else if (ediverp.val() == "") {
            alert("กรุณากรอกรหัส ERP");
        } else if (/^[0-9]/.test(ediverp.val())) {
            alert("กรุณากรอกรหัส ERP เป็นตัวเลขเท่านั้น");
        } else {
            $.post('../Data/DivEdit',
                    {
                        did: divid,
                        dname: edivname.val(),
                        derp: ediverp.val()
                    }, function (data) {
                if (data == 'ok') {
                    ReqData();
                    edivname.val("");
                    ediverp.val("");
                    alert("การแก้ไขข้อมูลสำเร็จ");
                    $("modaledit").modal('hide');
                    $(".feedback").txt("");
                    $(".feedback").parent().removeClass("has-error");
                    $(".feedback").parent().removeClass("has-success");
                } else if (data == 'namedup') {
                    alert("ชื่อฝ่ายนี้มีในระบบแล้ว");
                } else if (data == 'erpdup') {
                    alert("รหัส ERP นี้มีในระบบแล้ว");
                }
            });
        }
    });

});