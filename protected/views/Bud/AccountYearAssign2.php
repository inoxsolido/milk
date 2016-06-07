<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/datepicker.css" rel="stylesheet" type="text/css"/>
<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/jquery-checktree.css" ref="stylesheet" type="text/css" />
<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/smart_wizard.css" rel="stylesheet" type="text/css"/>
<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/multilevelcheckbox.css" rel="stylesheet" type="text/css"/>
<style>
    .table td, .table th{
        vertical-align: middle !important;
    }
</style>
<nav class="menu">
    <button id="btnback" class="btn btn-info" style="display:none"><i class="glyphicon glyphicon-backward"></i> ย้อนกลับ </button>
</nav>
<div class="container">
    <table id="yearlist" class="table table-bordered">
        <caption>ส่วนจัดการบัญชีที่ใช้ในแต่ละปีงบประมาณ</caption>
        <thead>
        <th>ปีงบประมาณ</th>
        <th>สถานะงบประมาณ</th>
        <th width="200">จัดการ</th>
        </thead>
        <tbody id="yearlistbody">
            <tr><td>Hello</td><td><button class="btn btn-success glyphicon glyphicon-plus">เพิ่ม</button></td></tr>
        </tbody>
    </table>
    <form id="accyear">
    </form>
</div>

<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/jquery-checktree.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/jquery.smartWizard.js" type="text/javascript"></script>
<script type="text/javascript" src="<?= Yii::app()->request->baseUrl ?>/assets/js/accountyearassign2.js"></script>