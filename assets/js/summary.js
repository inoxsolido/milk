$(function () {
    var round = 1;
    $("#exyear").on("change", function () {
        var year = $(this).val();
        var wh = $("#exwh").val();
        var type = $("[name=extype]:checked").val();
        if (year != 0) {
            if (wh == "month_goal") {
                $.ajax({
                    url: "../Data/FillDivInYear",
                    type: 'POST',
                    async: false,
                    data: {
                        year: year,
                        wh: wh,
                        tartype: type
                    }, success: function (data, textStatus, jqXHR) {
                        if (data.error == "") {
                            for (var i in data.d) {
                                var ele = "<option value='" + data.d[i].id + "'>" + data.d[i].name + "</option>";
                                $("#extar").html($("#extar").html() + ele);
                            }
                            $(".exgtar").show();
                            $(".exgdn").show();
                        } else
                            alert(data.error);
                    }, dataType: 'json'
                });
            } else if (wh == "over") {
                $(".exgtar").hide();
                $(".exgdn").show();
            } else {
                $(".exgtar").hide();
                $(".exgdn").hide();
            }
        }
    });
    $("#exwh").change(function () {
        var val = $(this).val();
        if (val != "") {
            if (val == "over") {
                $(".exgtype").hide();
                $(".exgyear").show();
                $(".exgtar").hide();
                $(".exgdn").hide();
            } else if (val == "month_goal") {
                $(".exgtype").show();
                $(".exgyear").hide();
                $(".exgtar").hide();
                $(".exgdn").hide();
            } else {
                $(".exgtype").hide();
                $(".exgyear").hide();
                $(".exgtar").hide();
                $(".exgdn").hide();
            }
        }
    });
    $("[name=extype]").change(function () {
        $("#exyear").val(0).change();
        $(".exgyear").show();
        $(".exgtar").hide();
        $(".exgdn").hide();
    });
    $("#btnExport").on("click change", function () {
        var wh = $("#exwh").val();
        var year = $("#exyear").val();
        var type = $("[name=extype]:checked").val();
        var tar = $("#extar").val();
        var query = "";
        if(type != "div"){
            query = "/milk/export/mg";
        }else
            query = "/milk/export/div";
        query += "?y="+year+"&ro=1&id="+tar;
        window.location=query;
    });
});

