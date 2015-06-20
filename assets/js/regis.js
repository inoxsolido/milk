$(function () {
    ReqData();
    $("#regis").click(function () {
        $("#mregis").modal('show');
    });
    var user = $("#username");
    var pwd = $(".pwd");
    var pass = $("#password");
    var repass = $("#re-type");
    var fname = $("#fname");
    var lname = $("#lname");
    var gender = $("#gender");
    var personid = $("#personid");
    var pos = $("#position");
    var div = $("#div");
    var dep = $("#dep");
    var userfail = 0;
    var pfail = 0;
    var perfail = 0;
    var ffail = 0, lfail = 0;
    user.focusout(function () {
        var sib = $(this).siblings(".feedback");
        var rule = /^[A-Za-z][A-Za-z0-9]*$/;
        var passs = 0;
        if (user.val() != "") {
            passs = rule.test(user.val());
            if (!passs) {
                userfail = 1;
                sib.text("Username ต้องเป็นตัวเลขหรืออังกฤษเท่านั้น");
            }
            if (passs) {
                $.post('./../valid/chkusrdup',
                        {
                            user: user.val()
                        }, function (data) {
                    if (data == 'dup') {
                        sib.text("Username ซ้ำ ! กรุณากรอก Username ใหม่");
                        userfail = 1;
                    } else if (data == 'no') {
                        sib.text("");
                        userfail = 0;
                    }
                });
            }
        } else {
            sib.text("กรุณากรอก Username");
            userfail = 1;
        }
        if (userfail) {
            $(this).parent().removeClass("has-success");
            $(this).parent().addClass("has-error");
        } else {
            $(this).parent().removeClass("has-error");
            $(this).parent().addClass("has-success");
        }
    });
    pwd.focusout(function () {
        var sib = repass.siblings(".feedback");
        var sibtext;
        if (pass.val() == '' || repass.val() == '') {
            sibtext = "Password ว่าง กรุณากรอก password";
            pfail = 1;
        } else {
            sibtext = "";
            pfail = 0;
        }
        if (repass.val() !== pass.val()) {
            sibtext = "Password ไม่ตรงกัน กรุณากรอก Password ใหม่";
            pfail = 1;
        }
        if (pfail) {
            sib.text(sibtext);
            $(this).removeClass("has-success");
            $(this).addClass("has-error");
        } else {
            sib.text("");
            $(this).removeClass("has-error");
            $(this).addClass("has-success");
        }
    });
    personid.focusout(function () {
        var sib = $(this).siblings(".feedback");
        var perid = personid.val().toString();
        if (checkID(perid)) {
            perfail = 0;
            sib.text("");
        } else {
            perfail = 1;
            sib.text("รหัสประจำตัวประชาชนไม่ถูกต้อง");
        }

        if (!perfail) {
            $.post('./../valid/chkperdup', {personid: perid}, function (data) {
                if (data == 'dup') {
                    sib.text("รหัสประจำตัวประชาชนนี้มีในระบบแล้ว");
                    perfail = 1;
                } else {
                    sib.text("");
                    perfail = 0;
                }
            });
        }

        if (perfail) {
            $(this).parent().removeClass("has-success");
            $(this).parent().addClass("has-error");
        } else {
            $(this).parent().removeClass("has-error");
            $(this).parent().addClass("has-success");
        }
    });
    div.parent().hide();
    pos.change(function () {
        if (pos.val() == 3) {
            dep.parent().hide();
            div.parent().hide();
            dep.val("");
            div.val("");
        } else if (pos.val() == 2) {
            dep.parent().hide();
            div.parent().show();
            dep.val("");
        } else if (pos.val() == 1) {
            dep.parent().show();
            div.parent().hide();
            div.val("");
        }
    });
    fname.focusout(function () {
        var sib = $(this).siblings(".feedback");
        if (fname.val() == '') {
            ffail = 1;
            sib.text("กรุณากรอก ชื่อ");
        } else {
            ffail = 0;
            sib.text("");
            var letters = /^[\u0E01-\u0E5B]+$/;
            if (letters.test(fname.val())) {
                ffail = 0;
                sib.text("");
            } else {
                ffail = 1;
                sib.text("ชื่อ ต้องเป็นภาษาไทยเท่านั้น");
            }
        }
        if (ffail) {
            $(this).parent().removeClass("has-success");
            $(this).parent().addClass("has-error");
        } else {
            $(this).parent().removeClass("has-error");
            $(this).parent().addClass("has-success");
        }
    });
    lname.focusout(function () {
        var sib = $(this).siblings(".feedback");
        if (lname.val() == '') {
            lfail = 1;
            sib.text("กรุณากรอก นามสกุล");
        } else {
            lfail = 0;
            sib.text("");
            var letters = /^[\u0E01-\u0E5B]+$/;
            if (letters.test(lname.val())) {
                lfail = 0;
                sib.text("");
            } else {
                lfail = 1;
                sib.text("นามสกุล ต้องเป็นภาษาไทยเท่านั้น");
            }
        }
        if (lfail) {
            $(this).parent().removeClass("has-success");
            $(this).parent().addClass("has-error");
        } else {
            $(this).parent().removeClass("has-error");
            $(this).parent().addClass("has-success");
        }
    });
    $("#add").click(function () {
        user.focus();
        pass.focus();
        repass.focus();
        fname.focus();
        lname.focus();
        personid.focus();
        ok = 0;
        $("#add").focus();
        if (pos.val() == 2) {
            if (div.val() != 0)
                ok = 1;
            else
                ok = 0;
        } else if (pos.val() == 1) {
            if (dep.val() != 0)
                ok = 1;
            else
                ok = 0;
        }
        if (userfail || pfail || perfail || ffail || lfail) {
            ok = 0;
        } else {
            ok = 1;
        }
        if (ok) {
            $.post('./../data/addmember', {
                u: user.val(),
                p: pass.val(),
                f: fname.val(),
                l: lname.val(),
                g: gender.val(),
                pid: personid.val(),
                pos: pos.val(),
                div: div.val(),
                dep: dep.val()
            }, function (data) {
                if (data == 'ok') {
                    alert("การเพิ่มข้อมูลสำเร็จ");
                    $("#frmregis")[0].reset();
                    dep.val("0");
                    div.val("0");
                    pos.val("0");
                    dep.parent().hide();
                    div.parent().hide();
                    $("#mregis").modal('hide');
                    $("#mregis").find(".feedback").text("");
                    $("#mregis").find("div.form-group-sm").removeClass("has-error");
                    $("#mregis").find("div.form-group-sm").removeClass("has-success");
                    $(".feedback").text("");
                    $(".feedback").parent().removeClass("has-error");
                    $(".feedback").parent().removeClass("has-success");
                    ReqData();
                } else {
                    alert("การเพิ่มข้อมูลไม่สำเร็จ");
                }
            });
        }
    });
    //search-----
    $("#find").hide();
    $("#findshow").click(function () {
        $("#find").fadeToggle();
    });
    $("#btnfind").click(function () {
        ReqData();
        $("#find").fadeToggle();
    });
    //endsearch----
    function ReqData() {
        $("#usrbody").html('<image src="./../images/loading.gif">');
        $.post('./../data/FillUsr',
                {
                    ajax: 0,
                    search: {
                        usr: $("#fdusr").val(),
                        fname: $("#fdname").val(),
                        lname: $("#fdlname").val(),
                        perid: $("#fdperid").val(),
                        div: $("#fddiv").val(),
                        par: $("#fdpar").val(),
                        pos: $("#fdpos").val()
                    }
                },
        function (data) {
            $("#usrbody").html(data);
        });
    }
    //active-deactive------
    $("#usrbody").on('click', '.deactive', function () {
        $.post('./../data/usrstatechange', {
            uid: $(this).attr('data-id'),
            state: 0
        }, function (data) {
            if (data == 'ok') {
                ReqData();
            }
        });
    });
    $("#usrbody").on('click', '.active', function () {
        $.post('./../data/usrstatechange', {
            uid: $(this).attr('data-id'),
            state: 1
        }, function (data) {
            if (data == 'ok') {
                ReqData();
            }
        });
    });
    //endactive-------



    //--edit zone ---------------
    var epos = $("#eposition");
    var ediv = $("#ediv");
    var edep = $("#edep");
    ediv.parent().hide();
    edep.parent().hide();
    epos.change(function () {
        if (epos.val() == 3) {
            edep.parent().hide();
            ediv.parent().hide();
            edep.val("");
            ediv.val("");
        } else if (epos.val() == 2) {
            edep.parent().hide();
            ediv.parent().show();
            edep.val("");
        } else if (epos.val() == 1) {
            edep.parent().show();
            ediv.parent().hide();
            ediv.val("");
        }
    });
    //edit btn
    var eid;
    $("#usrbody").on('click', '.edit', function () {
        eid = $(this).attr('data-id');
        id = eid;

        $.ajax({
            url: "../Data/AskUserInfo",
            async: false,
            type: 'POST',
            data: {id: id},
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                $("#eusername").val(data.user);
                $("#epersonid").val(data.perid);
                $("#efname").val(data.fname);
                $("#elname").val(data.lname);
                epos.val(data.pos).change();
                $("#ediv").val(data.div);
                $("#egender").val(data.gen);
                $("#epassword").val("");
            }
        });
        $("#medit").modal('show');
    });
    $("#btnedit").click(function () {
        euser = $("#eusername");
        var letters = /^[\u0E01-\u0E5B]+$/;
        ok = false;
        if (euser.val() == "") {
            alert("การแก้ไขข้อมูล Username ต้องไม่เป็นค่าว่าง");
        } else if ($("#efname").val() == "") {
            alert("ชื่อจริงต้องไม่ว่าง");
        } else if (letters.test($("#efname").val()) == false) {
            alert("ชื่อจริงต้องเป็นภาษาไทยเท่านั้น");
        } else if ($("#elname").val() == "") {
            alert("นามสกุลต้องไม่ว่าง");
        } else if (letters.test($("#elname").val()) == false) {
            alert("นามสกุลต้องเป็นภาษาไทยเท่านั้น");
        } else if ($("#epersonid").val() == false) {
            alert("รหัสประจำตัวประชาชนต้องไม่เป็นค่าว่าง");
        } else if (checkID($("#epersonid").val()) == false) {
            alert("รหัสประจำตัวประชาชนไม่ถูกต้อง");
        } else {
            ok = true;
        }
        if (!ok)
            return;

        $.post('../Data/MemberEdit',
                {
                    uid: eid,
                    username: $("#eusername").val(),
                    password: $("#epassword").val(),
                    fname: $("#efname").val(),
                    lname: $("#elname").val(),
                    gen: $("#egender").val(),
                    perid: $("#epersonid").val(),
                    pos: $("#eposition").val(),
                    div: $("#ediv").val(),
                    dep: $("#edep").val()
                }, function (data) {
            if (data == 'usrdup') {
                alert("Username นี้มีอยู่แล้วในระบบ");
            } else if (data == 'perdup') {
                alert("รหัสประจำตัวประชาชนนี้มีอยู่แล้วในระบบ");
            } else if (data == 'ok') {
                alert("การเปลี่ยนแปลงข้อมูลเสร็จสิ้น");
                $("#frmedit")[0].reset();
                edep.val("0");
                ediv.val("0");
                epos.val("0");
                edep.parent().hide();
                ediv.parent().hide();
                $("#medit").modal('hide');
                ReqData();
            }
        });
    });

});
function checkID(id) {
    if (id.length !== 13)
        return false;
    for (i = 0, sum = 0; i < 12; i++)
        sum += parseFloat(id.charAt(i)) * (13 - i);
    if ((11 - sum % 11) % 10 !== parseFloat(id.charAt(12)))
        return false;
    return true;
}

