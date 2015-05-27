<style type="text/css">
    div.sea{
        display:inline-block;
    }
</style>

<div class='container'>
    <div class='row'>
        <div class='col-md-2' style="float:right;">
            <button class='btn btn-success btn-block' " id='regis'>เพิ่มผู้ใช้รายใหม่</button>
        </div>
    </div>
    <!-- search -->
    <div class="row">
        <form id="search" class="form-inline">
            <span class="row">
                <div class="col-md-2">
                    <h3>ค้นหาโดย</h3>
                </div>
                <div class="col-md-2 col-md-offset-8">
                    
                </div>
            </span>
            <div class="col-md-12" style="padding-right:0">
                <div class="form-group-sm sea">
                    <input type="checkbox" id="cuser">
                    <label for="tuser">Username</label>
                    <input type="text" id="tuser" class="form-control">
                </div>
                <div class="form-group-sm sea">
                    <input type="checkbox" id="cfname">
                    <label for="tfname">ชื่อจริง</label>
                    <input type="text" id="tfname" class="form-control">
                </div>
                <div class="form-group-sm sea">
                    <input type="checkbox" id="clname">
                    <label for="tlname">นามสกุล</label>
                    <input type="text" id="tlname" class="form-control">
                </div>
                <div class="form-group-sm sea">
                    <input type="checkbox" id="cpersonid">
                    <label for="tpersonid">รหัสประจำตัวประชาชน</label>
                    <input type="text" id="tpersonid" class="form-control">
                </div>
                <div class="form-group-sm sea">
                    <input type="checkbox" id="cdep">
                    <label for="tdep">แผนก</label>
                    <select id='tdep'>
                        <?php foreach ($dep as $row): ?>
                            <option value="<?= $row->dep_id ?>"><?= $row->dep_name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group-sm sea">
                    <input type="checkbox" id="cfac">
                    <label for="tfac">ฝ่าย</label>
                    <select id='tfac'>
                        <?php foreach ($fac as $row): ?>
                            <option value="<?= $row->faction_id ?>"><?= $row->faction_name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group-sm sea">
                    <input type="checkbox" id="cposition">
                    <label for="tposition">ตำแหน่ง</label>
                    <select id='tposition'>
                        <?php foreach ($pos as $row): ?>
                            <option value="<?= $row->position_id ?>"><?= $row->position_name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2"  style="float:right;padding:0px;margin:0px;" >
                <button class='btn btn-default btn-sm btn-block'>ค้นหา</button>
                </div>
            </div>
        </form>
    </div><p>
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