$(function () {
    var year = $("#yeardiv").attr("year");
    var round = $("#yeardiv").attr("round");
    function ReqData() {
        $(".loading").fadeIn();
        $.ajax({
            url: "../Data/FillYearGoalDiv",
            async: false,
            type: 'POST',
            data: {
                year: year,
                round: round
            },
            success: function (data, textStatus, jqXHR) {
                $("#yeardiv").html(data);
            }
        });
        $(".loading").fadeOut();
    }
    function chkinputasdecimal(input) {
        var val = input.val();
        var sib = input.siblings('span');
        var letter = /^\d+\.?\d{0,2}$/;
        if (val == "") {
            sib.text("  กรุณากรอกค่า");
            return false;
        }
        if (!letter.test(val)) {
            sib.text("  กรุณากรอกค่าเป็นจำนวนเต็มหรือทศนิยม เช่น 1234567.25");
            return false;
        }
        sib.text("");
        return true;
    }
    //event
    $("#yeardiv").on("click", ".assign", function () {
        var cid = $(this).parent().attr("cid");

        window.location = "./YearGoal/assign/" + cid;
    });
    $("#yeardiv").on("click", ".edit", function () {
        var cid = $(this).parent().attr("cid");
        window.location = "./YearGoal/edit/" + cid;
    });
    $("#yeardiv").on("click", ".cancel", function () {
        $(".loading").show();
        var name = $(this).parent().parent().siblings().first().html();
        if(!confirm("คุณต้องการยกเลิกกรอบงบประมาณ "+name)){
            $(".loading").hide();
            return false;
        }
        var cid = $(this).parent().attr("cid");
        
        $.ajax({
            url: "../Data/YgDivDel",
            async: false,
            type: 'POST',
            data: {
                year: year,
                round: round,
                cid: cid,
            },success: function (data, textStatus, jqXHR) {
                alert(data);
                ReqData();
            }
        });

    });
    $(".decimal").change(function () {
        chkinputasdecimal($(this));
    });
    //main
    ReqData();
});


