$(function () {
    ReqData();
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
        userfail = chkusr(user);
    });
    pwd.focusout(function () {
        pfail = chkrepass(pass,repass);
    });
    $("#regis").click(function () {
        $("#mregis").modal('show');
    });
    function ReqData() {
        $.post('./../data/FillUsr', {ajax: 0}, function (data) {
            $("#usrbody").html(data);
        });
    }
})