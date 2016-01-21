<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/datepicker.css" rel="stylesheet" type="text/css"/>
<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/jquery-checktree.css" rel="stylesheet" type="text/css" />
<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/jquery.steps.css" rel="stylesheet" type="text/css"/>
<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/smart_wizard.css" rel="stylesheet" type="text/css"/>
<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/multilevelcheckbox.css" rel="stylesheet" type="text/css"/>


<style>
    .center{
        margin: auto 5px 0px auto;

        height: 100%;
        padding: 10px;
        text-align: center;
    }
    .menu-edit{
        border:2px solid #FF6600;
        border-bottom-width: 3px;
    }
    .page-warp{
        position: fixed;    
        top: 0px;     
        left: 0px;    
        background: #99ccff;     
        width: 100%;     
        height: 100%;     
        opacity: 1;     
        filter: alpha(opacity=100);     
        -moz-opacity: 1;    
        z-index: 799;   
    }
    .in-page-warp{
        width: 50%;
        height: auto;
        margin: 10% auto;
        background-color: white;
        opacity: 1;     
        filter: alpha(opacity=100);     
        -moz-opacity: 1;  
        z-index: 800;

    }

</style>
<div class="page-warp">
    <div class="center in-page-warp form-inline" >
        <div class='form-group-sm'>
            <label>ข้อมูลนี้เป็นของ:&nbsp;&nbsp;</label><select id='target' class='form-control'>
                <?php
                if (!empty($targets)):
                    foreach ($targets AS $target):
                        ?>
                        <option value="<?= $target['division_id'] ?>"><?= $target['division_name'] ?></option>
                        <?php
                    endforeach;
                else:
                    ?>
                    <option value='0'>ไม่มีแผนก/กอง/ฝ่าย ที่คุณสามารถกรอกได้</option>
                <?php endif; ?>
            </select>&nbsp;&nbsp;&nbsp;<a class='btn btn-sm btn-success' style='width: 120px' id='btntarget'><b>ตกลง</b></a>
        </div>
    </div>
</div>
<div class="btn-group btn-group-justified menu menu-edit">
    <a class="btn btn-warning btn-sm" id='verm'  onclick="mver();">เลือกเวอร์ชั่นก่อนหน้า</a>
</div>
<div id="mver" class="form-inline center" style="display: none">
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
    <div id="fstep" year="<?= $year ?>">
        <h3>ระบุบัญชีที่ต้องการกรอก สำหรับปี<?= $year ?></h3>
        <section class="fchkbox" ><!--get form accyearempty-->
        </section>
        <h3>กรอกงบประมาณรายเดือน สำหรับปี<?= $year ?></h3>
        <section class="finput"><!-- get form afterchkbox-->
        </section>
    </div>
</div>

<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/bootstrap-datepicker-thai.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/locales/bootstrap-datepicker.th.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/jquery-checktree.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/jquery.steps.min.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/jquery.smartWizard.js" type="text/javascript"></script>
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
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/monthgoal.js" type="text/javascript"></script>