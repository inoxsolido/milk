<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/jquery-checktree.css" rel="stylesheet" type="text/css" />
<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/multilevelcheckbox.css" rel="stylesheet" type="text/css"/>
<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/smart_wizard.css" rel="stylesheet" type="text/css"/>
<style type="text/css">
    .txtacc{
        display: inline;
    }
    .disabled input[type=text]{
        text-align: right;
    }
    #YgdInput input[type=text]{
        text-align: right;
    }
    .overlimit{
        color: red;
    }
    .inlimit{
        color: blue;
    }
    .equlimit{
        color: green;
    }
    .disabled > span{
        width: 50%;
        text-align: right;
        float: left;
        position: relative;
        border-right: 2px solid lightsteelblue;
        border-left: 2px solid lightsteelblue;
        padding: 3px 3px;
        padding-top: 0px;
    }
    #canlimit > span{
        border-bottom: none;
        border-top: 2px solid lightsteelblue;
        padding-bottom: 0px;
        padding-top: 3px;
    }
    #headlimit{
        border-top: 2px solid lightsteelblue;
        border-bottom: 2px solid lightsteelblue;
        
    }
    .disabled{
        height: 28px;
    }
</style>
<div class="container">
    <div style="text-align:center"><h4><?=$method=='edit'?'แก้ไข':'กำหนด'?>กรอบงบประมาณรายปีสำหรับ <?= $cname ?> ประจำปี <?= $year + 543 ?></h4></div>
    <div id="headlimit"><!--head-->
        <?php if ($round != 1): ?>
            <div id="canlimit" class="disabled">
                <span>กรอบงบประมาณ<b><u>รายได้</u></b>ที่<b>สามารถกำหนดได้</b> <input type="text" readonly="readonly" id="canincome" style=""/> <b>บาท</b></span>
                <span style="border-left: none;">กรอบงบประมาณ<b><u>รายจ่าย</u></b>ที่<b>สามารถกำหนดได้</b> <input type="text" readonly="readonly" id="canexpend"/> <b>บาท</b></span>
            </div>
        <?php endif; ?>
        <div class="disabled">
            <span>กรอบงบประมาณ<b><u>รายได้</u></b>ที่<b>กำหนดไปแล้ว</b> <input type="text" readonly="readonly" id="nowincome"/> <b>บาท</b></span>
            <span style="border-left: none;">กรอบงบประมาณ<b><u>รายจ่าย</u></b>ที่<b>กำหนดไปแล้ว</b> <input type="text" readonly="readonly" id="nowexpend"/> <b>บาท</b></span>
        </div>
    </div>

    <div id="YgdInput" year="<?= $year ?>" round="<?= $round ?>" method="<?= $method ?>" cid="<?= $cid ?>" url="<?=Yii::app()->createAbsoluteUrl("Bud/YearGoal")?>">

    </div>
</div>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/jquery-checktree.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/collapse.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/jquery.smartWizard.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/YgInput.js" type="text/javascript"></script>