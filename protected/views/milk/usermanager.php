<div class="btn-group btn-group-justified">
    <a class='btn btn-success btn-sm' id='regis'>เพิ่มผู้ใช้รายใหม่</a>
    <a class="btn btn-info btn-sm" id='findshow'>ค้นหา</a>
    <a class='btn btn-warning btn-sm' id='setting'>ตั้งค่าการแสดงผล</a>
</div>
<div class='container' >

    <div class='row'>
        <div class='table-responsive'>
            <div>
                <table class='table table-bordered'>
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>ชื่อ</th>
                            <th>นามสกุล</th>
                            <th>แผนก</th>
                            <th>ฝ่าย</th>
                            <th>สิทธิ์การเข้าใช้</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody id='usrbody'>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!--regis-->
<div class="modal fade" id='mregis'>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">เพิ่มผู้ใช้ใหม่</h3>
            </div>
            <div class="modal-body">
                <form class='form-horizontal' id='frmregis'>
                    <div class='form-group'>
                        <label for='user'>Username</label>
                        <input type='text' class='form-control' id='username' placeholder='Username' required>
                        <span class='feedback'></span>
                    </div>
                    <div class='form-group pwd'>
                        <label for='password'>Password</label>
                        <input type='password' class='form-control' id='password' placeholder='Password' required>
                        <label for='retype'>Re-type password</label>
                        <input type='password' class='form-control' id='re-type' placeholder='Password agian' required>
                        <span class='feedback'></span>
                    </div>
                    <div class='form-group'>
                        <label for='fname'>ชื่อจริง</label>
                        <input type='text' class='form-control' id='fname' placeholder="นายธนเดช" required>
                        <span class='feedback'></span>
                    </div>
                    <div class="form-group">
                        <label>นามสกุล</label>
                        <input type='text' class='form-control' id='lname' placeholder='นามสกุล' required>
                        <span class='feedback'></span>
                    </div>
                    <div class='form-group'>
                        <label>เพศ</label>
                        <select id='gender'>
                            <option value='ชาย'>ชาย</option>
                            <option value='หญิง'>หญิง</option>
                        </select>
                    </div>
                    <div class='form-group'>
                        <label for='personid'>รหัสประจำตัวประชาชน</label>
                        <input type='text' class='form-control' id='personid' maxlength="13" required>
                        <span class='feedback'></span>
                    </div>
                    <div class='form-group pos'>
                        <label for='position'>ระดับสิทธิผู้ใช้</label>
                        <select id='position'>
                            <?php foreach ($pos as $row): ?>
                                <option value="<?= $row->position_id ?>"><?= $row->position_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class='form-group fac'>
                        <label for="fac">ฝ่าย</label>
                        <select id='fac'>
                            <?php foreach ($fac as $row): ?>
                                <option value="<?= $row->faction_id ?>"><?= $row->faction_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class='form-group dep'>
                        <label for='dep'>แผนก/ตำแหน่งพิเศษ</label>
                        <select id='dep'>
                            <?php foreach ($dep as $row): ?>
                                <option value="<?= $row->dep_id ?>"><?= $row->dep_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-primary" id='add'>บันทึก</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript" src="<?= Yii::app()->request->baseUrl ?>/assets/js/regis.js"></script>
<style>
    .feedback{
        color:red;
    }
</style>