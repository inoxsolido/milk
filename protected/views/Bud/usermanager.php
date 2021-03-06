<div class="btn-group btn-group-justified menu">
    <a class='btn btn-success btn-sm' id='regis'>เพิ่มผู้ใช้รายใหม่</a>
    <a class="btn btn-info btn-sm" id='findshow'>ค้นหา</a>
</div>
<div class='container' id='find'>
    <br>
    <div class='form-horizontal'>
        <table class='table table-bordered form-group-sm' >
            <thead>
                <tr>
                    <th>Username</th>
                    <th>ชื่อ</th>
                    <th>นามสกุล</th>
                    <th>รหัสประจำตัวประชาชน</th>
                    <th>สังกัด</th>
                    <th>ฝ่าย</th>
                    <th>สิทธิ์การเข้าใช้</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="text" class="form-control" id="fdusr"></td>
                    <td><input type="text" class="form-control" id="fdname"></td>
                    <td><input type="text" class="form-control" id="fdlname"></td>
                    <td><input type="text" class="form-control" id="fdperid"></td>
                    <td><input type="text" class="form-control" id="fddiv"></td>
                    <td><input type="text" class="form-control" id="fdpar"</td>
                    <td><select id="fdpos">
                            <option value="99" selected="selected">--ไม่เลือก--</option>
                            <option value="1">หัวหน้าแผนก</option>
                            <option value="2">หัวหน้าฝ่าย</option>
                            <option value="3">Administrator</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="7"><a id='btnfind' class='btn btn-default btn-block btn-sm'>ค้นหา <span class='glyphicon glyphicon-search'></span></a></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class='container' style="width:90%">
    <div class='row'>
        <div class='table-responsive'>
            <div>
                <table class='table table-bordered'>
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>ชื่อ</th>
                            <th>นามสกุล</th>
                            <th>รหัสประจำตัวประชาชน</th>
                            <th>สังกัด</th>
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
                    <div class='form-group div'>
                        <label for="div">สังกัด</label>
                        <select id='div'>
                            <?php foreach ($div as $row): ?>
                                <option value="<?= $row['division_id'] ?>"><?= $row['division_name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class='form-group dep'>
                        <label for='dep'>แผนก/ตำแหน่งพิเศษ</label>
                        <select id='dep'>
                            <?php foreach ($dep as $row): ?>
                                <option value="<?= $row['division_id'] ?>"><?= $row['par_name'] ." -- ".$row['division_name']?></option>
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

<!--edit-->
<div class="modal fade" id='medit'>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">แก้ไขข้อมูล</h3>
            </div>
            <div class="modal-body">
                <form class='form-horizontal' id='frmedit'>
                    <div class='form-group'>
                        <label for='user'>Username</label>
                        <input type='text' class='form-control' id='eusername' placeholder='Username' required>
                        <span class='efeedback'></span>
                    </div>
                    <div class='form-group epwd'>
                        <label for='password'>Password</label>
                        <input type='password' class='form-control' id='epassword' placeholder='Password' required>
                        <span class='efeedback'></span>
                    </div>
                    <div class='form-group'>
                        <label for='fname'>ชื่อจริง</label>
                        <input type='text' class='form-control' id='efname' placeholder="นายธนเดช" required>
                        <span class='efeedback'></span>
                    </div>
                    <div class="form-group">
                        <label>นามสกุล</label>
                        <input type='text' class='form-control' id='elname' placeholder='นามสกุล' required>
                        <span class='efeedback'></span>
                    </div>
                    <div class='form-group'>
                        <label>เพศ</label>
                        <select id='egender'>
                            <option value='ชาย'>ชาย</option>
                            <option value='หญิง'>หญิง</option>
                        </select>
                    </div>
                    <div class='form-group'>
                        <label for='personid'>รหัสประจำตัวประชาชน</label>
                        <input type='text' class='form-control' id='epersonid' maxlength="13" required>
                        <span class='feedback'></span>
                    </div>
                    <div class='form-group epos'>
                        <label for='position'>ระดับสิทธิผู้ใช้</label>
                        <select id='eposition'>
                            <?php foreach ($pos as $row): ?>
                                <option value="<?= $row->position_id ?>"><?= $row->position_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class='form-group ediv'>
                        <label for="div">สังกัด</label>
                        <select id='ediv'>
                            <?php foreach ($div as $row): ?>
                                <option value="<?= $row['division_id'] ?>"><?= $row['division_name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class='form-group edep'>
                        <label for='dep'>แผนก/ตำแหน่งพิเศษ</label>
                        <select id='edep'>
                            <?php foreach ($dep as $row): ?>
                                <option value="<?= $row['division_id'] ?>"><?= $row['par_name'] ." -- ".$row['division_name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-primary" id='btnedit'>บันทึก</button>
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