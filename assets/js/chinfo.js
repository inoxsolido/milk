$(function () {
    $("#chinfo").click(function () {
        $(".loading").fadeIn();
        //load data
        $.ajax({
            url: "../Data/AskPersonInfo",
            async: false,
            type: 'POST',
            dataType: 'JSON',
            data: {
                uname: $("#chinfosubmit").attr('uid')
            }, success: function (data, textStatus, jqXHR) {
                if (typeof (data) == 'object')
                {
                    console.log(data);
                    //bind data to field
                    $("#chname").val(data.fname);
                    $("#chlname").val(data.lname);
                    //remove all class error and success
                    addState("#chname", "norm");
                    addState("#chlname", "norm");
                    addState("#chpwd1", "norm");
                    addState("#chpwd2", "norm");
                    //open modal
                    $("#mchinfo").modal("show");

                }else{
                    alert(data);
                }
            }
        });
        $(".loading").hide();

    });

    $("#chinfosubmit").click(function () {
        var nameerr = true,lnameerr=true,pwderr=true;
        var letter = /^[\u0E01-\u0E5B]+$/;
        if ($("#chname").val() == "") {
            addState($("#chname"), "error", "กรุณากรอกชื่อจริง");
        } else if (!letter.test($("#chname").val())) {
            addState($("#chname"), "error", "กรุณากรอกชื่อจริงเป็นภาษาไทย");
        } else{
            nameerr = false;
            addState($("#chname"), "success");
        }
            
        if ($("#chlname").val() == "") {
            addState($("#chlname"), "error", "กรุณากรอกนามสกุล");
        } else if (!letter.test($("#chlname").val())) {
            addState($("#chlname"), "error", "กรุณากรอกนามสกุลป็นภาษาไทย");
        } else{
            lnameerr = false;
            addState($("#chlname"), "success");
        }
        if ($("#chpwd1").val() != "" && ($("#chpwd1").val() != $("#chpwd2").val())) {
            addState($("#chpwd1"), "error", "");
            addState($("#chpwd2"), "error", "กรุณากรอกรหัสผ่านให้ตรงกันทั้งสองช่อง")
        } else{
            pwderr = false;
            addState($("#chpwd1"), "success");
            addState($("#chpwd2"), "success");
        }

        if (!(nameerr||lnameerr|pwderr)) {
            $.ajax({
                url: "../Data/UpdateUserInfo",
                async: false,
                type: 'POST',
                data: {
                    uname: $("#chinfosubmit").attr('uid'),
                    fname: $("#chname").val(),
                    lname: $("#chlname").val(),
                    pwd: $("#chpwd1").val()
                }, success: function (data, textStatus, jqXHR) {
                    if (data == 'ok')
                    {
                        alert("การเปลี่ยนแปลงข้อมูลสำเร็จ");
                        addState("#chname", "norm");
                        addState("#chlname", "norm");
                        addState("#chpwd1", "norm");
                        addState("#chpwd2", "norm");
                    } else
                        alert("การเปลี่ยนแปลงข้อมูลล้มเหลว (รหัสผ่านใหม่อาจเหมือนกับรหัสผ่านเดิม)");
                }
            });
        }

    });

    function addState(control, state, msg)
    {
        if (state == "success")
        {
            $(control).parent().removeClass("has-error");
            $(control).parent().addClass("has-success");
            $(control).siblings(".err").text("");
        } else if (state == "norm") {
            $(control).parent().removeClass("has-error");
            $(control).parent().removeClass("has-success");
            $(control).siblings(".err").text("");
        } else {
            $(control).parent().removeClass("has-success");
            $(control).parent().addClass("has-error");
            $(control).siblings(".err").text(msg);
        }
    }
});
