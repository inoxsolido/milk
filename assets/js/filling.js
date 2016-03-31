$(function () {
    function ReqData() {
        $("#fillingbody").html('<image src="./../images/loading.gif" >');
        search = {
            owner: $("#fdowner").val(),
            target: $("#fdtarget").val(),
        };
        $.post('./../data/FillFilling',
                {
                    ajax: 0,
                    searchtxt: search
                },
        function (data) {
            $("#fillingbody").html(data);
        });
        $.post('../Data/FillFillingOwner',
                {
                    ajax: 0
                }, function (data) {
            $("#addown").html(data);
            $("#editown").html(data);
        });
        $.post('../Data/FillFillingTarget',
                {
                    ajax: 0
                }, function (data) {
            $("#addtar").html(data);
        });

    }
    $("#find").hide();
    $("#findshow").click(function () {
        $("#find").fadeToggle();
        $("#txtfind").focus();
    });
    $("#btnfind").click(function () {
        ReqData();
        $("#find").fadeToggle();
    });
    ReqData();
    var d1, d2;
    //deleting zone ----------------------
    $("#fillingbody").on("click", ".delete", function () {
        d1 = $(this).attr("data-id1");
        d2 = $(this).attr("data-id2");
        if (confirm("ยืนยันการลบ!?") == true)
        {
            $.ajax({
                url: "../Data/FillingDel",
                async: false,
                type: 'POST',
                data: {
                    ajax: 0,
                    id: {id1: d1, id2: d2}
                },
                success: function (data, textStatus, jqXHR) {
                    if (data == 1)
                    {
                        alert("การลบเสร็จสมบูรณ์");
                        ReqData();
                    } else {
                        alert("การลบล้มเหลว!");
                    }
                }
            });
        }
    });
    //end delete zone -----------------
    //start add zone ----------------
    $("#addm").click(function () {
        $("#modaladd").modal("show");
    });
    $("#btnadd").click(function () {
        $.ajax({
            url: "../data/FillingAdd",
            async: false,
            type: 'POST',
            data: {
                pk1: $("#addown").val(),
                pk2: $("#addtar").val()
            },
            success: function (data, textStatus, jqXHR) {
                if (data == '1') {
                    alert("การเพิ่มข้อมูลสำเร็จ");
                    ReqData();
                } else {
                    alert("การเพิ่มข้อมูลล้มเหลว");
                }
            }
        });
    });
    //edit----
    $("#fillingbody").on("click", ".edit", function () {
        d1 = $(this).attr("data-id1");
        d2 = $(this).attr("data-id2");

        $.ajax({
            url: "../Data/FillFillingTargetEdit",
            async: false,
            type: 'POST',
            data: {
                ajax: 0,
                pk1: d1,
                pk2: d2
            },
            success: function (data, textStatus, jqXHR) {
                $("#edittar").html(data);
                $("#edittar").val(d2);
                $("#editown").val(d1);
            }
        });

        $("#modaledit").modal("show");

    });
    $("#btnedit").click(function () {
        $.ajax({
            url: "../data/FillingEdit",
            async: false,
            type: 'POST',
            data: {
                pk1: d1,
                pk2: d2,
                val1: $("#editown").val(),
                val2: $("#edittar").val()
            },
            success: function (data, textStatus, jqXHR) {
                if (data == '1') {
                    alert("การแก้ไขข้อมูลสำเร็จ");
                    ReqData();
                    $("#modaledit").modal("hide");
                } else {
                    alert("การแก้ไขข้อมูลล้มเหลว");
                }
            }
        });
    });

});

