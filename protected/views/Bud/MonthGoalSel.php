<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/jquery-checktree.css" rel="stylesheet" type="text/css" />
<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/smart_wizard.css" rel="stylesheet" type="text/css"/>
<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/multilevelcheckbox.css" rel="stylesheet" type="text/css"/>
<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/datepicker.css" rel="stylesheet" type="text/css"/>

<style type="text/css">
    .tdcenter{
        vertical-align: middle;
    }
    .thcenter{
        vertical-align: middle;
        text-align: center;
    }
    .tbcenter{
        margin: 10px auto;
    }
    select{
        color:black;
    }
    #saveopt{
        text-align: center;
    }
    #recover_version{
        display: inline-block;
    }
</style>
<nav id="saveopt" class="menu" style="display:none; background-color:white; height:60px; position:fixed !important">
    <button id="btnback" class="btn btn-info" style=" /*min-width:107px;*/ height:100%; float:left;" ><i class="glyphicon glyphicon-backward"></i> ย้อนกลับ </button>
    <button id="btn_recover" class="btn btn-warning" style=" /*min-width:107px;*/ height:100%; "  ><i class="glyphicon glyphicon-repeat"></i> เรียกข้อมูลงบประมาณ </button>
    <div id="recover_version" style="background-color: #f0751d;color:white; height:100%; display:none">
        <div style="display:block; text-align:center"><label>เรียกคืนข้อมูลงบประมาณ </label></div>
        <div id="recover_version_selector" style="display:block; text-align:center">
            <span><label>ปีงบประมาณ</label><select id="select_r_year"><option>2559</option></select><span>
            <span><label>รอบการจัดสรร</label><select id="select_r_round"><option>1</option></select></span>
            <span><label>เวอร์ชัน</label><select id="select_r_version"><option>1</option></select></span>
            <span><label>เวอร์ชันการเรียกคืนข้อมูล</label><select id="select_r_subversion"><option>20</option></select></span>&nbsp;&nbsp;&nbsp;&nbsp;
            <span class="btn-group-sm" style="float:right;">
                <button id="btn_recover_ok" style="float:left;" class="btn btn-sm btn-success">ตกลง</button>
                <button id="btn_recover_close" style="float:left;" class="btn btn-sm btn-danger" >ปิด</button>
            </span>
        </div>
        <div id ="recover_version_error"></div>
    </div>
    <button id="btnsave" class="btn btn-success" style="; /*min-width:107px;*/ height:100%; float:right"><i class="glyphicon glyphicon-book" ></i> บันทึก </button>
</nav>
<div id="selector" class="container" style="min-width:600px; max-width: 800px">
    <div id="budyearselect" style="text-align: center"><label>เลือกปีงบประมาณ <select id="selbudyear" style="width:115px"></select></label></div>
    <div id='joblist' round='' year='' >
        
    </div>
</div>
<div id="monthgoalform">
    
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

<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/jquery-checktree.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/collapse.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/jquery.smartWizard.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/numeral.min.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/decimalFixdown.js" type="text/javascript"></script>
<script type="text/javascript">numeral.defaultFormat('0,0.00');</script>

<script src='<?= Yii::app()->request->baseUrl ?>/assets/js/mgsel.js' type='text/javascript'></script>
<script type="text/javascript">
    $("#btn_recover").click(function(){$("#recover_version").show();$("#btn_recover").hide();$("#saveopt").css({"background-color":"#f0751d"});});
    $("#btn_recover_close").click(function(){$("#recover_version").hide();$("#btn_recover").show();$("#saveopt").css({"background-color":"white"});});
</script>