$(function (){
    $("#chkconfirm").change(function(){
        if($(this).prop("checked"))
            $("#btnconfirm").show();
        else
            $("#btnconfirm").hide();
    });
    $("#btnconfirm").click(function(){
        
    });
});