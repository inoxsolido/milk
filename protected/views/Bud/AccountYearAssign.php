<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/datepicker.css" rel="stylesheet" type="text/css"/>

<div class="container">
    <h3 style="text-align: center">กำหนดบัญชีที่ใช้ในแต่ละปี</h3>
</div>
<div class="container">
    <div class="row">
        <div class="col-lg-4 col-lg-offset-4">
            <form class="form form-inline">
                <div class="form-group-sm">
                    <label class="control-label" style="width: 16%">เลือกปี</label>
                    <input id="y" class="form-control datepicker" style="width:60%"  type="text" data-provide="datepicker" data-date-language="th-th">
                    <a class="btn  btn-success btn-sm" style="width:22%">ตกลง</a>
                </div>
            </form>
        </div>
    </div>
</div>
<!--content-->
<div class="container">
    
</div>

<!--script zone-->
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/bootstrap-datepicker-thai.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/locales/bootstrap-datepicker.th.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function () {
        $('.datepicker').datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years",
            autoclose: true,
            clearBtn: true,
        });
    });
</script>