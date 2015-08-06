<div class="btn-group btn-group-justified menu">
    <a class='btn btn-success btn-sm' id='addm'>เพิ่ม รายการกรอกให้แต่ละ ตำแหน่ง/แผนก/กอง/ฝ่าย</a>
    <a class="btn btn-info btn-sm" id='findshow'>ค้นหา</a>
</div>
<div class="container" id='find'>
    <br>
    <div class='form-horizontal'>
        <table class='table table-bordered form-group-sm' >
            <thead>
                <tr>
                    <th>ตำแหน่ง/แผนก/กอง/ฝ่าย ที่รับผิดชอบ</th>
                    <th>ฝ่ายที่สังกัด</th>
                    <th>ตำแหน่ง/แผนก/กอง/ฝ่าย ที่ได้รับการกรอก</th>
                    <th>ฝ่ายที่สังกัด</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="text" class="form-control" id="fdowner"></td>
                    <td><input type="text" class="form-control" id="fdownerpar"></td>
                    <td><input type="text" class="form-control" id="fdtarget"></td>
                    <td><input type="text" class="form-control" id="fdtargetpar"></td>
                </tr>
                <tr>
                    <td colspan="4"><a id='btnfind' class='btn btn-default btn-block btn-sm'>ค้นหา <span class='glyphicon glyphicon-search'></span></a></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class='container' >
    <div class='row'>
        <div class='table-responsive'>
            <div>
                <table class='table table-bordered'>
                    <thead>
                        <tr>
                            <th>ตำแหน่ง/แผนก/กอง/ฝ่าย ที่รับผิดชอบ</th>
                            <th>ตำแหน่ง/แผนก/กอง/ฝ่าย ที่ได้รับการกรอก</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody id='fillingbody'>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .feedback{
        color:red;
        font-weight: bold;
    }
</style>
<!-- Modal add -->
<div class="modal fade" id="modaladd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">เพิ่มรายการกรอกใหม่</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group-sm">
                        <label class="control-label">ตำแหน่ง/แผนก/กอง/ฝ่ายที่กรอก</label>
                        <select class="form-control" id="addown">

                        </select>
                    </div>
                    <div class="form-group-sm">
                        <label class="control-label">ตำแหน่ง/แผนก/กอง/ฝ่ายที่ถูกกรอก</label>
                        <select class="form-control" id="addtar">

                        </select>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-primary" id="btnadd">ยืนยันการเพิ่มข้อมูล</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal edit -->
<div class="modal fade" id="modaledit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">แก้ไขรายการกรอก</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group-sm">
                        <label class="control-label">ตำแหน่ง/แผนก/กอง/ฝ่ายที่กรอก</label>
                        <select class="form-control" id="editown">

                        </select>
                    </div>
                    <div class="form-group-sm">
                        <label class="control-label">ตำแหน่ง/แผนก/กอง/ฝ่ายที่ถูกกรอก</label>
                        <select class="form-control" id="edittar">

                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-primary" id="btnedit">ยืนยันการแก้ไข</button>
            </div>
        </div>
    </div>
</div>
<script src="<?= Yii::app()->request->baseUrl; ?>/assets/js/filling.js" type="text/javascript"></script>