<div class="container">
    <div style="text-align: center"><h4>กำหนดกรอบงบประมาณรายได้-รายจ่ายรวม ประจำปี <?=$year+543?> รอบ<?php if($round == 1)echo "ก่อนการประชุม";else echo "หลังการประชุม";?></h4></div>
    <div id='yearadmin' round='<?=$round?>' year='<?=$year?>' ">
        
    </div>
</div>
<script src='<?= Yii::app()->request->baseUrl ?>/assets/js/yeargoaladmin.js' type='text/javascript'></script>