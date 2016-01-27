<div class="btn-group btn-group-justified menu">
    <a class='btn btn-success btn-sm' id='addm'>เพิ่มบัญชีใหม่</a>
    <a class="btn btn-info btn-sm" id='findshow'>ค้นหา</a>
</div>
<div class="container" id='find' style="display:none">
    <br>
    <div class='form-horizontal'>
        <table class='table table-bordered form-group-sm' >
            <thead>
                <tr>
                    <th>รหัส ERP</th>
                    <th>ชื่อบัญชี</th>
                    <th>หมวด</th>
                    <th>มีบัญชีที่สังกัด</th>
                    <th>ชื่อบัญชีที่สังกัด</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="text" class='form-control' id='fderp'></td>
                    <td><input type="text" class="form-control" id="fdname"></td>
                    <td>
                        <select id="fdgroup">
                            <option value="">--ไม่เลือก--</option>
                            <?php foreach ($group as $row) { ?>
                                <option value="<?= $row['group_id'] ?>"><?= $row['group_name'] ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td style='text-align: center'><input type='checkbox' id='fdhaspar'></td>
                    <td><select class="form-control" id="fdpar"></select></td>
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
                            <th>รหัส ERP</th>
                            <th>ชื่อบัญชี</th>
                            <th>ประเภทงบ</th>
                            <th>ชื่อบัญชีที่สังกัด</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody id='accbody'>
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
                <h4 class="modal-title" id="myModalLabel">เพิ่มบัญชีใหม่</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group-sm">
                        <label class="control-label">ชื่อ บัญชี</label>
                        <input type="text" class="form-control" placeholder="ชื่อบัญชี" id="addname">
                        <span class="feedback"></span>
                    </div>
                    <div class="form-group-sm">
                        <label class="control-label"><input type="checkbox" id="addhaserp"> มีรหัส erp </label>
                    </div>
                    <div class="form-group-sm">
                        <label class="control-label"><input type="checkbox" id="addhassum"> มีการรวมยอด </label>
                    </div>
                    <div class="form-group-sm erp">
                        <label class="control-label">รหัส ERP ของบัญชี</label>
                        <input type="text" class="form-control" placeholder='61010101' id='adderp' maxlength='8'>
                        <span class='feedback'></span>
                    </div>
                    <div class="form-group-sm">
                        <label class="control-label"><input type="checkbox" id="addhaspar"> มีบัญชีที่สังกัด </label>
                    </div>
                    <div class="form-group-sm par">
                        <label class="control-label">ชื่อบัญชีที่สังกัด</label>
                        <select class="form-control" id="addpar">
                        </select>
                    </div>
                    <div class="form-group-sm ord">
                        <label class="control-label">ลำดับต่อจาก</label>
                        <select class="form-control" id="addorder"></select>
                    </div>
                    <div class='form-group-sm group'>
                        <label class='control-label'>ชื่อหมวด</label>
                        <select id="addgroup">
                            <?php foreach ($group as $row) { ?>
                                <option value="<?= $row['group_id'] ?>"><?= $row['group_name'] ?></option>
                            <?php } ?>
                        </select>
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
                <h4 class="modal-title" id="myModalLabel">แก้ไขบัญชีใหม่</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group-sm">
                        <label class="control-label">ชื่อ บัญชี</label>
                        <input type="text" class="form-control" placeholder="ชื่อบัญชี" id="editname">
                        <span class="feedback"></span>
                    </div>
                    <div class="form-group-sm">
                        <label class="control-label"><input type="checkbox" id="edithaserp"> มีรหัส erp </label>
                    </div>
                    <div class="form-group-sm">
                        <label class="control-label"><input type="checkbox" id="edithassum"> มีการรวมยอด </label>
                    </div>
                    <div class="form-group-sm erp">
                        <label class="control-label">รหัส ERP ของบัญชี</label>
                        <input type="text" class="form-control" placeholder='61010101' id='editerp' maxlength='8'>
                        <span class='feedback'></span>
                    </div>
                    <div class="form-group-sm">
                        <label class="control-label"><input type="checkbox" id="edithaspar"> มีบัญชีที่สังกัด </label>
                    </div>
                    <div class="form-group-sm par">
                        <label class="control-label">ชื่อบัญชีที่สังกัด</label>
                        <select class="form-control" id="editpar">
                        </select>
                    </div>
                    <div class="form-group-sm ord">
                        <label class="control-label">ลำดับต่อจาก</label>
                        <select class="form-control" id="editorder"></select>
                    </div>
                    <div class='form-group-sm group'>
                        <label class='control-label'>ชื่อหมวด</label>
                        <select id="editgroup">
                            <?php foreach ($group as $row) { ?>
                                <option value="<?= $row['group_id'] ?>"><?= $row['group_name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-primary" id="btnedit">ยืนยันการแก้ไขข้อมูล</button>
            </div>
        </div>
    </div>
</div>
<script src="<?= Yii::app()->request->baseUrl; ?>/assets/js/accmgr.js" type="text/javascript"></script>