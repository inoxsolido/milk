<div style="margin:10% auto;">
    <div class="from-group" style="text-align: center;text-align-last: center; border-color:black;border-width:2px;border-style:solid;width:50%;margin:auto;">
        <label><input type='checkbox' id='chkconfirm'>&nbsp;&nbsp;ให้ทุกฝ่ายสามารถกลับไปแก้ไขข้อมูลหลังการประชุมได้</label>
        <br/><input type='button' id='btnconfirm' class='btn btn-primary btn-sm btn-block' style="display:none;" year='<?=$year?>' value='ยืนยัน'/>
    </div>
</div>
<script src='<?=Yii::app()->request->baseUrl?>/assets/js/approve2.js'></script>