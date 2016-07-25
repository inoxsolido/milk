<style type="text/css">
    .empty, .error{
        color: red;
        font-style: oblique;
        display:none;
    }
    .loading{
        color: blue;
    }
    
</style>
<div class='container'>
    <div class='row'>
        <div class='form col-sm-4 col-sm-offset-4'>
            <form class='form-horizontal' id='frmlogin'>
                <div class="form-group">
                    <label for='username'>Username</label>
                    <input class="form-control" type="text" placeholder='Username' id='username'>
                    <span class="empty">Insert username</span>
                </div>
                <div class="form-group">
                    <label for='password'>Password</label>
                    <input class="form-control" type='password' placeholder='Password' id='password'>
                    <span class="empty">Insert password</span>
                </div>
                <div class="form-group">
                    <input type='button' class='btn btn-success btn-block' value='Login' id='slogin'/>
                    <span class="loading"></span>
                    <span class='error'>Username or Password Incorrect </span>
                </div>
            </form>
        </div>
    </div>
</div>
<script type='text/javascript'>
    $(document).ready(function () {
        var user = $("#username");
        var pass = $("#password");
        $("#slogin").click(function () {
            if (user.val() == "")
                user.siblings("span").show();
            else
                user.siblings("span").hide();

            if (pass.val() == "")
                pass.siblings("span").show();
            else
                pass.siblings("span").hide();
            
            if(user.val() != "" && pass.val() != ""){
                $(".loading").show();
            }
            if (user.val() != "" && pass.val() != "") {
                $.post('../Valid/Login',
                        {
                            'user': user.val(),
                            'pass': pass.val()
                        },
                function (data) {
                    $(".loading").hide();
                    if (data == '1') {
                        $(".loading").show();
                        $(".error").text("");
                        location.reload();
                    }
                    else if (data == '2') {
                        $("#frmlogin")[0].reset();
                        user.focus();
                        $(".error").text("คุณถูกระงับการใช้งาน กรุณาติดต่อผู้ดูแลระบบ");
                        $(".error").hide();
                        $(".error").show();
                    } else {
                        $("#frmlogin")[0].reset();
                        user.focus();
                        $(".error").text("Username หรือ Password ผิด! กรุณาลองใหม่");
                        $(".error").hide();
                        $(".error").show();
                    }
                });
            }
        });
        $("input").keyup(function (event) {
            if (event.keyCode == 13) {
                $("#slogin").click();
            }
        });
        
        


    });
</script>