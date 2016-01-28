<div class="container">
    <div class="form">
        <form id="frmEx" action="#" method="post" class="form form-horizontal">
            <div class="form-group">
                <select id="exwh" class="form-control">
                    <option value="over">ภาพรวม</option>
                    <option value="month_goal">เป้ารายเดือน</option>
                    <option value="erp">ERP</option>
                </select>
            </div>
            <div class="form-group">
                <label class="control-label"><input id="extype" name="extype" type="radio" value="div"> รายฝ่าย</label>
                <label class="control-label"><input id="extype" name="extype" type="radio" value="dep"> รายแผนก/กอง</label>
            </div>
            <div class="form-group" id="exgyear">
                <select id="exyear" class="form-control">
                <?php foreach($years as $year){ ?>
                    <option value="<?=$year['year']?>"><?=($year['year']+543)?></option>
                <?php } ?>
                </select>
            </div>
            <div class="form-group" id="exgtar" style="display:none">
                <select id="extar" class="form-control"></select>
            </div>
        </form>
    </div>
</div>

<script src="<?=Yii::app()->request->baseUrl?>/assets/js/summary.js">