<div class="btn-group btn-group-justified menu">
    <a class='btn btn-success btn-sm' id='addm'>เพิ่ม ตำแหน่ง/แผนก/กอง/ฝ่าย</a>
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
                        <th>ฝ่ายที่สังกัด</th>
                        <th>รหัสสำนักงาน</th>
                        <th>สถานะ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" class="form-control" id="fdname"></td>
                        <td><input type="text" class="form-control" id="fderp"></td>
                        <td><input type="text" class="form-control" id="fdpar"></td>
                        <td><input type="text" class="form-control" id="fdof"></td>
                        <td><select id="fdstatus">
                                <option value="99" selected="selected">--ไม่เลือก--</option>
                                <option value="1">เป็นตำแหน่ง/แผนก</option>
                                <option value="2">เป็นกอง</option>
                                <option value="3">เป็นฝ่าย</option>
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
                            <th>ฝ่ายที่สังกัด</th>
                            <th>ด้าน</th>
                            <th>รหัสสำนักงาน</th>
                            <th>สถานะ</th>
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
                <h4 class="modal-title" id="myModalLabel">เพิ่ม ตำแหน่ง/แผนก/กอง/ฝ่ายใหม่</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group-sm">
                        <label class="control-label">ชื่อ ตำแหน่ง/แผนก/กอง/ฝ่าย</label>
                        <input type="text" class="form-control" placeholder="ชือตำแหน่ง/แผนก/กอง/ฝ่าย" id="addname">
                        <span class="feedback"></span>
                    </div>
                    <div class="form-group-sm status">
                        <label class="control-label" for="addstatus">สถานะ</label>
                        <label class="control-label"><input type="radio" name="addstatus" value="1" checked="checked"/>แผนก/ตำแหน่ง</label>
                        <label class="control-label"><input type="radio" name="addstatus" value="2"/>กอง</label>
                        <label class="control-label"><input type="radio" name="addstatus" value="3"/>ฝ่าย</label>
                        <label class="control-label"><input type="radio" name="addstatus" value="4"/>แผนก/กอง/ฝ่าย - รวม</label>
                    </div>
                    <div class='form-group-sm hsub'>
                        <label class='control-label'><input type='checkbox' id='addhassub'> มีกองที่สังกัด </label>
                    </div>
                    <div class="form-group-sm subp" style="display:none">
                        <label class="control-label">ชื่อกองที่สังกัด</label>
                        <select class="form-control" id="addsub"></select>
                    </div>
                    <div class="form-group-sm subo" >
                        <label class="control-label">ชื่อฝ่ายที่สังกัด</label>
                        <select class="form-control" id="addpar">                            
                        </select>
                    </div>
                    <div class="form-group-sm section" style="display: none">
                        <label class="control-label">ด้านที่สังกัด</label>
                        <select class="form-control" id="addsection">
                            <option value="1">ด้านบริหาร</option>
                            <option value="2">ด้านอำนวยการ</option>
                            <option value="3">ด้านกิจการโคนม</option>
                            <option value="4">ด้านอุตสาหกรรม</option>
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
                            <input type="text" class="form-control" placeholder="รหัส ERP" id="adderp" maxlength="5">
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
                <h4 class="modal-title" id="myModalLabel">แก้ไข ตำแหน่ง/แผนก/กอง/ฝ่าย</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group-sm">
                        <label class="control-label">ชื่อ ตำแหน่ง/แผนก/กอง/ฝ่าย</label>
                        <input type="text" class="form-control" placeholder="ชือตำแหน่ง/แผนก/กอง/ฝ่าย" id="editname">
                        <span class="feedback"></span>
                    </div>
                    <div class="form-group-sm status">
                        <label class="control-label" for="editstatus">สถานะ</label>
                        <label class="control-label"><input type="radio" name="editstatus" value="1" checked="checked"/>แผนก/ตำแหน่ง</label>
                        <label class="control-label"><input type="radio" name="editstatus" value="2"/>กอง</label>
                        <label class="control-label"><input type="radio" name="editstatus" value="3"/>ฝ่าย</label>
                        <label class="control-label"><input type="radio" name="editstatus" value="4"/>แผนก/กอง/ฝ่าย - รวม</label>
                    </div>
                    <div class='form-group-sm hsub'>
                        <label class='control-label'><input type='checkbox' id='edithassub'> มีกองที่สังกัด </label>
                    </div>
                    <div class="form-group-sm subp" style="display:none">
                        <label class="control-label">ชื่อกองที่สังกัด</label>
                        <select class="form-control" id="editsub"></select>
                    </div>
                    <div class="form-group-sm subo">
                        <label class="control-label">ชื่อกอง/ฝ่ายที่สังกัด</label>
                        <select class="form-control" id="editpar">
                        </select>
                    </div>
                    <div class="form-group-sm section" style="display: none">
                        <label class="control-label">ด้านที่สังกัด</label>
                        <select class="form-control" id="editsection">
                            <option value="1">ด้านบริหาร</option>
                            <option value="2">ด้านอำนวยการ</option>
                            <option value="3">ด้านกิจการโคนม</option>
                            <option value="4">ด้านอุตสาหกรรม</option>
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
                            <input type="text" class="form-control" placeholder="รหัส ERP 5 ตัว" id="editerp" maxlength="5">
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