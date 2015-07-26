<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/datepicker.css" rel="stylesheet" type="text/css"/>
<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/jquery-checktree.css" ref="stylesheet" type="text/css" />
<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/smart_wizard.css" rel="stylesheet" type="text/css"/>
<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/multilevelcheckbox.css" rel="stylesheet" type="text/css"/>
<nav class="menu btn-group btn-group-justified">
    <a class="btn btn-sm btn-success" id="addm" onclick="showmenuadd();">เพิ่ม <i class="glyphicon glyphicon-plus"></i></a>
    <a class="btn btn-sm btn-warning" id="editm" onclick="showmenuedit();">แก้ไข <i class="glyphicon glyphicon-edit"></i></a>
</nav>
<style>
    .center{
        margin: auto 5px 0px auto;
        width: 100%;
        border-bottom-width: 3px;
        padding: 10px;
        text-align: center;
    }
    .text-warning{
        color: #FF7518 !important;
    }
    .text-danger{
        color: #a80026 !important;
    }
    #madd{
        border:2px solid #8AC007;
    }
    #medit{
        border:2px solid #c00;
    }
</style>

<!--<h3 style="text-align: center">กำหนดบัญชีที่ใช้ในแต่ละปี</h3>-->
<div id="madd"  class="form-horizontal center" style="display: none">
    <div class="from-group" >
        <label>เพิ่มบัญชีที่ใช้สำหรับปี: </label>
        <input id="mayear" class="datepicker"  type="text" data-provide="datepicker" data-date-language="th-th">
        <a id="mbtnselyear" class="btn btn-sm btn-success">ตกลง <i class="glyphicon glyphicon-ok"></i></a>
    </div>
</div>
<div id="medit"  class="form-horizontal center" style="display: none">
    <div class="from-group" >
        <label>แก้ไขบัญชีที่ใช้สำหรับปี: </label>
        <select id="meyear">
            <option>year 1</option>
            <option>year 2</option>
            <option>year 3</option>
        </select>
        <a class="btn btn-sm btn-success">ตกลง <i class="glyphicon glyphicon-ok"></i></a><br/>
        <span class="text-warning" style="display: inline!important;">การแก้ไขจะส่งผลกระทบต่อการเรียกดูสรุปผล</span>
        <span class="text-danger" >*สามารถลบข้อมูลได้โดยการบันทึกค่าว่าง*</span>
    </div>
</div>

<!--content-->
<div class="container">
    <form id="accyear">
    </form>
</div>

<!--script zone-->
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/bootstrap-datepicker-thai.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/locales/bootstrap-datepicker.th.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/jquery-checktree.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/jquery.smartWizard.js" type="text/javascript"></script>
<script type="text/javascript">
        $(function () {
            $('.datepicker').datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years",
                autoclose: true,
                clearBtn: true,
            });
        });
</script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/AccYearAssign.js" type="text/javascript"></script>
<!--<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/multilevelcheckbox.js" type="text/javascript"></script>-->