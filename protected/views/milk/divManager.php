<div class="btn-group btn-group-justified">
    <a class='btn btn-success btn-sm' id='addm'>เพิ่มฝ่ายใหม่</a>
    <a class="btn btn-info btn-sm" id='findshow'>ค้นหา</a>
</div>
<div class='row' id='find'>
    <br>
    <div class='col-sm-4 col-sm-offset-4'>
        <div class='form-horizontal'>
            <div class='form-group'>
                <div class='col-sm-8'>
                    <input type='text' placeholder='พิมพ์คำค้นหา' id='txtfind' class='form-control'>
                </div>
                <div class='col-sm-4'>
                    <a id='btnfind' class='btn btn-default '>ค้นหา <span class='glyphicon glyphicon-search'></span></a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class='container' >
    <div class='row'>
        <div class='table-responsive'>
            <div>
                <table class='table table-bordered'>
                    <thead>
                        <tr>
                            <th>ชื่อฝ่าย</th>
                            <th>รหัส ERP </th>
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
        <h4 class="modal-title" id="myModalLabel">เพิ่มฝ่ายใหม่</h4>
      </div>
      <div class="modal-body">
          <form class="form-horizontal">
              <div class="form-group-sm">
                  <label class="control-label">ชื่อฝ่าย</label>
                  <input typ="text" class="form-control" placeholder="ชือฝ่าย" id="addname">
                  <span class="feedback"></span>
              </div>
              <div class="form-group-sm">
                  <label class="control-label">รหัส ERP </label>
                  <input typ="text" class="form-control" placeholder="ERP ID" id="adderp">
                  <span class="feedback"></span>
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
        <h4 class="modal-title" id="myModalLabel">แก้ไขฝ่าย</h4>
      </div>
      <div class="modal-body">
          <form class="form-horizontal">
              <div class="form-group-sm">
                  <label class="control-label">ชื่อฝ่าย</label>
                  <input typ="text" class="form-control" placeholder="ชือฝ่าย" id="editname">
                  <span class="feedback"></span>
              </div>
              <div class="form-group-sm">
                  <label class="control-label">รหัส ERP </label>
                  <input typ="text" class="form-control" placeholder="ERP ID" id="editerp">
                  <span class="feedback"></span>
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
<script src="<?=Yii::app()->request->baseUrl;?>/assets/js/divmgr.js" type="text/javascript"></script>