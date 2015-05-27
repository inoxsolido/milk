function chkusr(userfield){
        var sib = userfield.siblings(".feedback");
        var pass = 1;
        var fail = 0;
        if (user.val() != "") {
            //check user must be english alphabet or digit
            pass = rule.test(user.val());
                if (!pass) {
                    userfail = 1;
                    sib.text("Username ต้องเป็นตัวเลขหรืออังกฤษเท่านั้น");
                }
            if (pass) {
                $.post('./../valid/chkusrdup',
                        {
                            user: user.val()
                        }, function (data) {
                    if (data == 'dup') {
                        sib.text("Username ซ้ำ ! กรุณากรอก Username ใหม่");
                        fail=1;
                    } else if (data == 'no') {
                        sib.text("");
                        fail = 0;
                    }
                });
            }
        } else {
            sib.text("กรุณากรอก Username");
            fail = 1;
        }
        if (fail) {
            userfield.parent().removeClass("has-success");
            userfield.parent().addClass("has-error");
        } else {
            userfield.parent().removeClass("has-error");
            userfield.parent().addClass("has-success");
        }
        return fail;
}
function chkrepass(passfield,repassfield){
	var sib = repassfield.siblings(".feedback");
        var sibtext;
        var fail;
        pass = passfield.val();
        repass = repassfield.val();
        if (pass.val() == '' || repass.val() == '') {
            sibtext = "Password ว่าง กรุณากรอก password";
            fail = 1;
        } else {
            sibtext = "";
            fail = 0;
        }
        if (repass.val() !== pass.val()) {
            sibtext = "Password ไม่ตรงกัน กรุณากรอก Password ใหม่";
            fail = 1;
        }
        else {
            sibtext = "";
            fail = 0;
        }

        if (fail) {
            sib.text(sibtext);
            repassfield.removeClass("has-success");
            repassfield.addClass("has-error");
        } else {
            sib.text("");
            repassfield.removeClass("has-error");
            repassfield.addClass("has-success");
        }
    return fail;
}
function chkperid(peridfield){
	var sib = peridfield.siblings(".feedback");
        var perid = peridfield.val().toString();
        var fail;
        //chk format
        if (checkID(perid)) {
            fail = 0;
            sib.text("");
        } else {
            fail = 1;
            sib.text("รหัสประจำตัวประชาชนไม่ถูกต้อง");
        }
        //chk dup
        if(!fail){
        	$.post('./../valid/chkperdup',{personid:perid},function(data){
        		if(data=='dup'){
        			sib.text("รหัสประจำตัวประชาชนนี้มีในระบบแล้ว");
        			fail = 1;
        		}else{
        			sib.text("");
        			fail=0;
        		}
        	});
        }
        if (fail) {
            peridfield.parent().removeClass("has-success");
            peridfield.parent().addClass("has-error");
        } else {
            peridfield.parent().removeClass("has-error");
            peridfield.parent().addClass("has-success");
        }
    return fail;
}
function checkID(id) {
    if (id.length !== 13)
        return false;
    for (i = 0, sum = 0; i < 12; i++)
        sum += parseFloat(id.charAt(i)) * (13 - i);
    if ((11 - sum % 11) % 10 !== parseFloat(id.charAt(12)))
        return false;
    return true;
}
function chkfname(fnamefield){
        var sib = fnamefield.siblings(".feedback");
        var fail=0;
        if (fname.val() == '') {
            fail = 1;
            sib.text("กรุณากรอก ชื่อ");
        } else {
            fail = 0;
            sib.text("");
            var letters = /^[\u0E01-\u0E5B]+$/;
            if (letters.test(fname.val())) {
                fail = 0;
                sib.text("");
            } else {
                fail = 1;
                sib.text("ชื่อ ต้องเป็นภาษาไทยเท่านั้น");
            }
        }
        if (fail) {
            fnamefield.parent().removeClass("has-success");
            fnamefield.parent().addClass("has-error");
        } else {
            fnamefield.parent().removeClass("has-error");
            fnamefield.parent().addClass("has-success");
        }
 	return fail;
}
function chklname(lnamefield){
        var sib = lnamefield.siblings(".feedback");
        var fail=0;
        if (lname.val() == '') {
            fail = 1;
            sib.text("กรุณากรอก นามสกุล");
        } else {
            fail = 0;
            sib.text("");
            var letters = /^[\u0E01-\u0E5B]+$/;
            if (letters.test(lname.val())) {
                fail = 0;
                sib.text("");
            } else {
                fail = 1;
                sib.text("นามสกุล ต้องเป็นภาษาไทยเท่านั้น");
            }
        }
        if (fail) {
            lnamefield.parent().removeClass("has-success");
            lnamefield.parent().addClass("has-error");
        } else {
            lnamefield.parent().removeClass("has-error");
            lnamefield.parent().addClass("has-success");
        }
    return fail;
}