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
    var fac = $("#fac");
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
    dep.parent().hide();
    fac.parent().hide();
    pos.change(function () {
        if (pos.val() == 3) {
            dep.parent().hide();
            fac.parent().hide();
            dep.val("");
            fac.val("");
        } else if (pos.val() == 2) {
            dep.parent().hide();
            fac.parent().show();
            dep.val("");
        } else if (pos.val() == 1) {
            dep.parent().show();
            fac.parent().hide();
            fac.val("");
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
        $("#add").focus();
        if (pos.val() == 2) {
            if (fac.val() != 0)
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
                fac: fac.val(),
                dep: dep.val()
            }, function (data) {
                if (data == 'ok') {
                    alert("การเพิ่มข้อมูลสำเร็จ");
                    $("#frmregis")[0].reset();
                    dep.val("0");
                    fac.val("0");
                    pos.val("0");
                    dep.hide();
                    fac.hide();
                    $("#mregis").modal('hide');
                } else {
                    alert("การเพิ่มข้อมูลไม่สำเร็จ");
                }
            });
        }
    });
    function ReqData() {
        $.post('./../data/FillUsr', {ajax: 0}, function (data) {
            $("#usrbody").html(data);
        });
    }
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
    $("#search").children('div').children().children(".form-control").hide();
    $("#search").children('div').children().children("select").hide();
    $("[type^=checkbox]").click(function(){
        $(this).siblings("[type^=text]").toggle('fast','linear');
        $(this).siblings("select").toggle('fast','swing');
    });
    
    
    //edit btn
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

