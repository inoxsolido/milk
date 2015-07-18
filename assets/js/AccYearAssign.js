$(function(){
    function ReqData(){
        $.ajax({
            url: '../Data/FillAccYear',
            type: 'POST',
            async: false,
            success: function (data, textStatus, jqXHR) {
                $("#accyear").html(data);
            }
        });
    }
    ReqData();
});