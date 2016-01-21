<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/datepicker.css" rel="stylesheet" type="text/css"/>
<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/smart_wizard.css" rel="stylesheet" type="text/css"/>
<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/multilevelcheckbox.css" rel="stylesheet" type="text/css"/>
<style type="text/css">
    .center{
        margin: auto 5px 0px auto;

        height: 100%;
        padding: 10px;
        text-align: center;
    }
    .menu-edit{
        border:2px solid #FF6600;
        border-bottom-width: 3px;
        border-top: none;
        width:100%;
        margin-bottom: 10px;
    }
    .limit, current{
        font-weight: bold;
    }
    .limit input, .current input{
        background-color:#959595;
        text-align: right;
        cursor:not-allowed;
        width:120px;
        font-weight: 500 
    }
    .limit{
        color:red;
    }
    .current{
        color:blue;
    }
    input[month]{
        text-align: right;
    }
    .err{
        font-weight: bold;
        color: red;
    }
</style>
<div class="btn-group btn-group-justified menu">
    <a class="btn btn-warning btn-sm" id='verm'  onclick="$('#mver').slideToggle('fast'); $(window).scrollTop(0);">เลือกเวอร์ชั่นก่อนหน้า</a>
</div>
<div id="mver" class="form-inline center menu-edit" style="display: none">
    <div class="form-group-sm">
        <label>เลือกปี: </label>
        <input id="iyear" class="datepicker"  type="text" data-provide="datepicker" data-date-language="th-th">
        <label>เลือกเวอร์ชั่น:</label>
        <select id="iver" class="form-control">
            <option>เลือกเวอร์ชั่น</option>
        </select>
        <a id="msubmit" class="btn btn-sm btn-success">ตกลง <i class="glyphicon glyphicon-ok"></i></a>
    </div>
</div>
<div class="container">
    <div style="text-align:center"><h4><?=$method=='edit'?'แก้ไข':'กำหนด'?>กรอบงบประมาณรายปีสำหรับ <?= $cname ?> ประจำปี <?= $year + 543 ?></h4></div>
    <div id="mginput" year="<?= $year ?>" round="<?= $round ?>" method="<?= $method ?>" cid="<?= $cid ?>" url="<?=Yii::app()->createAbsoluteUrl("Bud/MonthGoal")?>">
        
    </div>
</div>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/bootstrap-datepicker-thai.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/locales/bootstrap-datepicker.th.js" type="text/javascript"></script>
<script type="text/javascript">
        $(function () {
            $('.datepicker').datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years",
                autoclose: true,
                clearBtn: true
            });
        });
</script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/collapse.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/jquery.smartWizard.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/numeral.min.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/decimalFixdown.js" type="text/javascript"></script>
<script type="text/javascript">numeral.defaultFormat('0,0.00');</script>
<script src='<?= Yii::app()->request->baseUrl ?>/assets/js/mginput.js' type='text/javascript'></script>
