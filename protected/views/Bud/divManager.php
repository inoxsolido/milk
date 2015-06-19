<div class="btn-group btn-group-justified">
    <a class='btn btn-success btn-sm' id='addm'>เพิ่มฝ่าย ตำแหน่ง/แผนก/กอง/ฝ่ายใหม่</a>
    <a class="btn btn-info btn-sm" id='findshow'>ค้นหา</a>
</div>
<div class="container" id='find'>
    <br>
        <div class='form-horizontal'>
            <table class='table table-bordered form-group-sm' >
                <thead>
                    <tr>
                        <th>ชื่อตำแหน่ง/แผนก/กอง/ฝ่าย</th>
                        <th>รหัส ERP </th>
                        <th>แผนก/กอง/ฝ่ายที่สังกัด</th>
                        <th>รหัสสำนักงาน</th>
                        <th>เป็นตำแหน่ง</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" class="form-control" id="fdname"></td>
                        <td><input type="text" class="form-control" id="fderp"></td>
                        <td><input type="text" class="form-control" id="fdpar"></td>
                        <td><input type="text" class="form-control" id="fdof"></td>
                        <td><select id="fdispos">
                                <option value="99" selected="selected">--ไม่เลือก--</option>
                                <option value="0">ไม่เป็น</option>
                                <option value="1">เป็น</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5"><a id='btnfind' class='btn btn-default btn-block btn-sm'>ค้นหา <span class='glyphicon glyphicon-search'></span></a></td>
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
                            <th>ชื่อตำแหน่ง/แผนก/กอง/ฝ่าย</th>
                            <th>รหัส ERP </th>
                            <th>แผนก/กอง/ฝ่ายที่สังกัด</th>
                            <th>รหัสสำนักงาน</th>
                            <th>เป็นตำแหน่ง</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody id='divbody'>
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
                <h4 class="modal-title" id="myModalLabel">เพิ่มฝ่าย ตำแหน่ง/แผนก/กอง/ฝ่ายใหม่</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group-sm">
                        <label class="control-label">ชื่อ ตำแหน่ง/แผนก/กอง/ฝ่าย</label>
                        <input typ="text" class="form-control" placeholder="ชือตำแหน่ง/แผนก/กอง/ฝ่าย" id="addname">
                        <span class="feedback"></span>
                    </div>
                    <div class="form-group-sm ispos">
                        <label class="control-label"><input type="checkbox" id="addispos"> เป็นตำแหน่ง </label>
                    </div>
                    <div class='form-group-sm isdiv'>
                        <label class='control-label'><input type='checkbox' id='addisdiv'> เป็นฝ่าย</label>
                    </div>
                    <div class="form-group-sm subo">
                        <label class="control-label">ชื่อแผนก/กอง/ฝ่าย ที่สังกัด</label>
                        <select class="form-control" id="addpar">                            
                        </select>
                    </div>
                    <div class="form-group-sm">
                        <label class="control-label">รหัส ERP ของสำนักงาน</label>
                        <input type="text" class="form-control" placeholder='เช่นสำนักงานใหญ่ คือ 01' id='addoffice' maxlength='2'>
                        <span class='feedback'></span>
                    </div>
                    <div class='form-group-sm'>
                        <label class='control-label'><input type='checkbox' id='addhaserp'> มีรหัส ERP </label>
                    </div>
                    <div class='option'>
                        <div class="form-group-sm">
                            <label class="control-label">รหัส ERP </label>
                            <input typ="text" class="form-control" placeholder="รหัส ERP" id="adderp" maxlength="5">
                            <span class="feedback"></span>
                        </div>
                    </div>
                </form>
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
                <h4 class="modal-title" id="myModalLabel">แก้ไขฝ่าย ตำแหน่ง/แผนก/กอง/ฝ่าย</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group-sm">
                        <label class="control-label">ชื่อ ตำแหน่ง/แผนก/กอง/ฝ่าย</label>
                        <input typ="text" class="form-control" placeholder="ชือตำแหน่ง/แผนก/กอง/ฝ่าย" id="editname">
                        <span class="feedback"></span>
                    </div>
                    <div class="form-group-sm ispos">
                        <label class="control-label"><input type="checkbox" id="editispos"> เป็นตำแหน่ง </label>
                    </div>
                    <div class='form-group-sm isdiv'>
                        <label class='control-label'><input type='checkbox' id='editisdiv'> เป็นฝ่าย</label>
                    </div>
                    <div class="form-group-sm subo">
                        <label class="control-label">ชื่อแผนก/กอง/ฝ่าย ที่สังกัด</label>
                        <select class="form-control" id="editpar">
                        </select>
                    </div>
                    <div class="form-group-sm">
                        <label class="control-label">รหัส ERP ของสำนักงาน</label>
                        <input type="text" class='form-control' placeholder='เช่นสำนักงานใหญ่ คือ 01' id='editoffice' maxlength='2'>
                        <span class='feedback'></span>
                    </div>
                    <div class='form-group-sm'>
                        <label class='control-label'><input type='checkbox' id='edithaserp'> มีรหัส ERP  </label>
                    </div>
                    <div class='option'>
                        <div class="form-group-sm">
                            <label class="control-label">รหัส ERP </label>
                            <input typ="text" class="form-control" placeholder="รหัส ERP 5 ตัว" id="editerp" maxlength="5">
                            <span class="feedback"></span>
                        </div>
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
<script src="<?= Yii::app()->request->baseUrl; ?>/assets/js/divmgr.js" type="text/javascript"></script>