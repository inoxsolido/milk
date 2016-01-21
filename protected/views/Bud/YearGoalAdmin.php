<div class="container">
    <div style="text-align: center"><h4>กำหนดกรอบงบประมาณรายได้-รายจ่ายรวม ประจำปี <?=$year+543?> รอบ<?php if($round == 1)echo "ก่อนการประชุม";else echo "หลังการประชุม";?></h4></div>
    <div id='yearadmin' round='<?=$round?>' year='<?=$year?>' ">
        
    </div>
    <small class="text " style="font-weight:bold">*สามารถยกเลิกยอดรวมเดิมได้โดยการบันทึกค่า 0 ลงในช่องทั้ง 2 ช่องของแต่ละฝ่าย</small>
</div>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/numeral.min.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/decimalFixdown.js" type="text/javascript"></script>
<script src='<?= Yii::app()->request->baseUrl ?>/assets/js/yeargoaladmin.js' type='text/javascript'></script>