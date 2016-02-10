<div class="container">
    <div class="form">
        <form id="frmEx" action="#" method="post" class="form form-horizontal">
            <div class="form-group">
                <select class="form-control" id="exwh">
                    <option value="" selected="" >เลือกประเภทไฟล์</option>
                    <option value="over">ภาพรวม</option>
                    <option value="month_goal">เป้ารายเดือน</option>
                    <option value="erp">ERP</option>
                </select>
            </div>
            <div class="form-group exgtype" style="display:none">
                <label class="control-label"><input id="extype" name="extype" type="radio" value="div"> รายฝ่าย</label>
                <label class="control-label"><input id="extype" name="extype" type="radio" value="dep"> รายแผนก/กอง</label>
            </div>
            <div class="form-group exgyear" style="display:none">
                <select id="exyear" class="form-control">
                    <option selected  value="0">เลือกปี</option>
                <?php foreach($years as $year){ ?>
                    <option value="<?=$year['year']?>"><?=($year['year']+543)?></option>
                <?php } ?>
                </select>
            </div>
            <div class="form-group exgtar" style="display:none">
                <select id="extar" class="form-control"></select>
            </div>
            <div class="form-group exgdn" style="display: none">
                <input id="btnExport" type="btn" class="btn btn-primary" value="Download"/>
            </div>
        </form>
    </div>
</div>

<script src="<?=Yii::app()->request->baseUrl?>/assets/js/summary.js">