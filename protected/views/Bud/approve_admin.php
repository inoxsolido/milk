<div class='container'>
    <div style="text-align: center"><h4>การยืนยันข้อมูลประจำปี <?=$year+543?> รอบ<?php if($round == 1)echo "ก่อนการประชุม";else echo "หลังการประชุม";?></h4></div>
    <div id='approveadmin' round='<?=$round?>' year='<?=$year?>'>
    </div>
    <span><small>*หากประชุมเสร็จแล้วต้อง <a href="<?=Yii::app()->createAbsoluteUrl("Bud/YearGoal")?>">กำหนดกรอบ</a> ให้กับแต่ละฝ่ายก่อน</small></span>
</div>
<script src='<?= Yii::app()->request->baseUrl ?>/assets/js/approveadmin.js' type='text/javascript'></script>