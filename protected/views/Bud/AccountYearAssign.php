<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/datepicker.css" rel="stylesheet" type="text/css"/>
<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/multilevelcheckbox.css" rel="stylesheet" type="text/css"/>
<div class="container">
    <h3 style="text-align: center">กำหนดบัญชีที่ใช้ในแต่ละปี</h3>
</div>
<div class="container">
    <div class="row">
        <div class="col-xs-4 col-xs-offset-4">
            <form class="form form-inline">
                <div class="form-group-sm">
                    <label class="control-label">เลือกปี</label>
                    <input id="y" class="form-control datepicker"  type="text" data-provide="datepicker" data-date-language="th-th">
                    <a class="btn  btn-success btn-sm">&nbsp;&nbsp;ตกลง&nbsp;&nbsp;</a>
                </div>
            </form>
        </div>
    </div>
</div>
<!--content-->
<div class="container">
    <form id="accyear">
        <ul>
            <li>
                <input type="checkbox" name="tall" id="tall">
                <label for="tall">Tall Things</label>
                <ul>
                    <li>
                        <input type="checkbox" name="tall-1" id="tall-1">
                        <label for="tall-1">Buildings</label>
                    </li>
                    <li>
                        <input type="checkbox" name="tall-2" id="tall-2">
                        <label for="tall-2">Giants</label>
                        <ul>
                            <li>
                                <input type="checkbox" name="tall-2-1" id="tall-2-1">
                                <label for="tall-2-1">Andre</label>
                            </li>
                            <li>
                                <input type="checkbox" name="tall-2-2" id="tall-2-2">
                                <label for="tall-2-2">Paul Bunyan</label>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <input type="checkbox" name="tall-3" id="tall-3">
                        <label for="tall-3">Two sandwiches</label>
                    </li>
                </ul>
            </li>
            <li>
                <input type="checkbox" name="short" id="short">
                <label for="short">Short Things</label>
                <ul>
                    <li>
                        <input type="checkbox" name="short-1" id="short-1">
                        <label for="short-1">Smurfs</label>
                    </li>
                    <li>
                        <input type="checkbox" name="short-2" id="short-2">
                        <label for="short-2">Mushrooms</label>
                    </li>
                    <li>
                        <input type="checkbox" name="short-3" id="short-3">
                        <label for="short-3">One Sandwich</label>
                    </li>
                </ul>
            </li>
        </ul>
    </form>
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
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/AccYearAssign.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/multilevelcheckbox.js" type="text/javascript"></script>