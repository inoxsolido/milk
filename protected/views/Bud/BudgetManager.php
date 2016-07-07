<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/datepicker.css" rel="stylesheet" type="text/css"/>

<style>
    .text-white{
        color: white !important;
    }
    .dropdown-menu-close{
        padding:0px;
        margin-left:5px;
        margin-top:0px;
    }
    .table td, .table th{
        vertical-align: middle !important;
        text-align: center;
    }
   
</style>
<nav class='menu'>
    <button id="btnaddbudyear" class='btn btn-success btn-sm' data-toggle="modal" data-target="#modal_add_budyear" style='float:right'>เพิ่มปีงบประมาณใหม่ <i class='glyphicon glyphicon-plus'></i></button>
</nav>
<div class='container'>
    <table id="tbbudyear" class='table table-bordered'>
        <thead>
        <th>ปีงบประมาณ</th>
        <th>รอบการปรับปรุง</th>
        <th>เวอร์ชัน</th>
        <th>สถานะคำขอตั้ง<br/>งบประมาณ</th>
        <th>จัดการ</th>
        </thead>
        <tbody>
            <tr year="">
                <td>2559</td>
                <td>0:ปกติ</td>
                <td>อนุมัติแล้ว/ยังไม่อนุมัติ</td>
                <td>
                    <div class="dropdown" style="display:inline-block">
                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">จัดการงบประมาณ (Adjust) <i class="glyphicon glyphicon-edit"></i>
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu dropdown-menu-close">
                            <li><a href="#" class='btn btn-sm btn-success adjust-add'><span class="text-white">ปรับปรุง <i class="glyphicon glyphicon-upload"></i></span> </a></li>
                            <li><a href="#" class='btn btn-sm btn-danger adjust-cancel'><span class="text-white">ยกเลิก <i class="glyphicon glyphicon-remove-circle"></i></span></a></li>
                        </ul>
                    </div>
                    <div class="dropdown"style="display:inline-block">
                        <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown">จัดการเวอร์ชั่น <i class="glyphicon glyphicon-book"></i>
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu dropdown-menu-close">
                            <li><a href="#" class='btn btn-sm btn-success version-add'><span class="text-white">เพิ่ม <i class="glyphicon glyphicon-duplicate"></i></span> </a></li>
                            <li><a href="#" class='btn btn-sm btn-danger version-cancel'><span class="text-white">ลบ <i class="glyphicon glyphicon-remove"></i></span></a></li>
                        </ul>
                    </div>
                    <div class="dropdown"style="display:inline-block">
                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">บัญชีที่ใช้ <i class="glyphicon glyphicon-edit"></i>
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu dropdown-menu-close">
                            <li><a href="#" class="btn btn-sm btn-success accyear-add text-white">กำหนดบัญชีที่ใช้ <i class="glyphicon glyphicon-plus"></i></a></li>
                            <li><a href="#" class="btn btn-sm btn-warning accyear-edit text-white">แก้ไขบัญชีที่ใช้ <i class="glyphicon glyphicon-edit"></i></a></li>
                            <li><a href="#" class="btn btn-sm btn-danger accyear-cancel text-white">ยกเลิกการกำหนด <i class="glyphicon glyphicon-trash"></i></a></li>
                        </ul>
                    </div>
                    <div class="dropdown"style="display:inline-block">
                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">โครงสร้างองค์กร <i class="glyphicon glyphicon-list-alt"></i>
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu dropdown-menu-close">
                            <li><a href="#" class="btn btn-sm btn-success org-add text-white">กำหนดโครงสร้างองค์กร <i class="glyphicon glyphicon-plus"></i></a></li>
                            <li><a href="#" class="btn btn-sm btn-warning org-edit text-white">แก้ไขโครงสร้างองค์กร <i class="glyphicon glyphicon-edit"></i></a></li>
                            <li><a href="#" class="btn btn-sm btn-danger org-cancel text-white">ลบโครงสร้างองค์กร <i class="glyphicon glyphicon-trash"></i></a></li>
                        </ul>
                    </div>


                    <button class="btn btn-sm btn-danger budgetyear-cancel">ลบปีงบปรมาณ <span class="glyphicon glyphicon-trash"></span></button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="modal_add_budyear" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">เพิ่มปีงบประมาณ</h4>
            </div>
            <div class="modal-body">
                <p>เลือกปี: <input id="dpbudyear" class="datepicker"  type="text" data-provide="datepicker" data-date-language="th-th"/> <span class="output"></span></p>
                <p id="dpbudyearerr" class="text-danger"></p>
            </div>
            <div class="modal-footer">
                <button id="btnsavebudyear" type="button" class="btn btn-primary" style="width:75%; float:left;">บันทึก</button>
                <button type="button" class="btn btn-default" style="width:25%; float:left; margin:0px;" data-dismiss="modal">ปิด</button>
            </div>
        </div>

    </div>
</div>

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
<script type="text/javascript" src="<?= Yii::app()->request->baseUrl ?>/assets/js/BudgetManager.js"></script>