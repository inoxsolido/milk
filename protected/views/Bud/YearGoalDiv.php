<div class="container">
    <div style="text-align: center"><h4>กำหนดกรอบงบประมาณรายปี ประจำปี <?=$year+543?> รอบ<?php if($round == 1)echo "ก่อนการประชุม";else echo "หลังการประชุม";?></h4></div>
    <div id='yeardiv' round='<?=$round?>' year='<?=$year?>' >
        
    </div>
</div>
<script src='<?= Yii::app()->request->baseUrl ?>/assets/js/yeargoaldiv.js' type='text/javascript'></script>