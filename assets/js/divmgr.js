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
    
});