<div class="btn-group btn-group-justified">
    <a class='btn btn-success btn-sm' id='addm'>เพิ่มฝ่ายใหม่</a>
    <a class="btn btn-info btn-sm" id='findshow'>ค้นหา</a>
</div>
<div class='row' id='find'>
    <br>
    <div class='col-sm-4 col-sm-offset-4'>
        <div class='form-horizontal'>
            <div class='form-group'>
                <div class='col-sm-8'>
                    <input type='text' placeholder='พิมพ์คำค้นหา' id='txtfind' class='form-control'>
                </div>
                <div class='col-sm-4'>
                    <a id='btnfind' class='btn btn-default '>ค้นหา <span class='glyphicon glyphicon-search'></span></a>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" >
    $("#find").hide();
    $("#findshow").click(function () {
        $("#find").fadeToggle();
    });
    $("#txtfind").keyup(function (ev) {
        if (ev.keyCode == 13) {
            $("#btnfind").click();
        }
    });
    $("#btnfind").click(function () {
        ReqData();
        $("#txtfind").val("");
        $("#find").fadeToggle();
    });
</script>
<div class='container' >
    <div class='row'>
        <div class='table-responsive'>
            <div>
                <table class='table table-bordered'>
                    <thead>
                        <tr>
                            <th>ชื่อฝ่าย</th>
                            <th>รหัส ERP </th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody id='divbody'>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?=Yii::app()->request->baseUrl;?>/assets/js/divmgr.js" type="text/javascript"></script>