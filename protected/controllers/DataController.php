<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DataController
 *
 * @author s5602041620019
 */
class DataController extends Controller {

    public function actionFillDiv() {
        if (isset($_POST['ajax'])) {
            $stxt = $_POST['searchtxt'] ? $_POST['searchtxt'] : "";
            $sql = "SELECT * FROM tb_division ";
            if ($stxt != "") {
                $sql .= "WHERE erp_id LIKE '%$stxt%' OR division_name LIKE '%$stxt%'";
            }
            $div = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($div as $row) {
                ?><tr>
                    <td style="width:60%"><?= $row['division_name'] ?></td>
                    <td style="width:20%"><?= $row['erp_id'] ?></td>
                    <td style="width:20%"><button class='btn btn-sm btn-warning edit' data-id="<?= $row['division_id'] ?>">แก้ไข <span class='glyphicon glyphicon-wrench'></span></button>&nbsp;&nbsp;
                        <?php if ($row['enable'] == 1) { ?><button class='btn btn-sm btn-danger deactive' data-id="<?= $row['division_id'] ?>">ยกเลิก <span class='glyphicon glyphicon-remove'></span></button>
                        <?php } else if ($row['enable'] == 0) { ?><button class='btn btn-sm btn-success active' data-id="<?= $row['division_id'] ?>">เปิดใช้ <span class='glyphicon glyphicon-ok'></span></button><?php } ?>
                    </td>
                </tr><?php
            }
        }
    }
    
    public function actionDivStateChange(){
        if (isset($_POST['divid']) && isset($_POST['state'])) {
            $did=$_POST['divid'];
            $st = $_POST['state'];
            
            $sql = "UPDATE tb_division SET enable = $st WHERE division_id = $did";
            echo Yii::app()->db->createCommand($sql)->execute() ? "ok" : 0;
            
        }
    }
    
    public function actionFillDep() {
        if (isset($_POST['AJAX'])) {
            $model = TbDepartment::model()->findAll();
            foreach ($model as $row):
                ?>
                <option value="<?= $row->fac_id ?>"><?= $row->fac_name ?></option>
                <?php
            endforeach;
        }
    }

    public function actionAddMember() {
        if (isset($_POST['u'])) {
            $user = $_POST['u'];
            $pass = $_POST['p'];
            $fname = $_POST['f'];
            $lname = $_POST['l'];
            $perid = $_POST['pid'];
            $pos = $_POST['pos'];
            $dep = $_POST['dep'];
            $div = $_POST['div'];
            $g = $_POST['g'];

            $result = Yii::app()->db->createCommand("INSERT INTO tb_user VALUES("
                            . "NULL,'$user','$pass','$fname','$lname','$g',"
                            . "'$perid','$dep','$fac','$pos',1)")->execute();
            if ($result == 1) {
                echo 'ok';
            } else {
                echo 'not ok';
            }
        }
    }

    public function actionFillUsr() {
        if (!isset($_POST['ajax']))
            return;
        $stxt = $_POST['searchtxt'];
        $shwsql = "SELECT u.*, department_name, division_name, position_name "
                    . "FROM tb_user as u "
                    . "LEFT JOIN tb_position ON  u.position_id = tb_position.position_id "
                    . "LEFT JOIN tb_department ON u.Department_id = tb_department.department_id "
                    . "LEFT JOIN tb_division ON u.division_id = tb_division.division_id " ;
        if ($stxt != "") {
            $shwsql .= "WHERE u.username LIKE '%$stxt%' OR "
                    . "u.fname LIKE '%$stxt%' OR u.lname LIKE '%$stxt%' OR "
                    . "u.person_id LIKE '%$stxt%' OR tb_position.position_name LIKE '%$stxt%' "
                    . "OR department_name LIKE '%$stxt%' OR division_name LIKE '%$stxt%' ";
            //."WHERE u.username != '$usr'";
        }
        $userinfo = Yii::app()->db->createCommand($shwsql)->queryAll();

        foreach ($userinfo as $user) {
            ?>
            <tr>
                <td><?= $user['username'] ?></td>
                <td><?= $user['fname'] ?></td>
                <td><?= $user['lname'] ?></td>
                <td><?= $user['person_id'] ?></td>
                <td><?php if ($user['position_id'] == 1) echo $user['department_name'] ?></td>
                <td><?= $user['division_name'] ?></td>
                <td><?= $user['position_name'] ?></td>
                <td>
                    <button class='btn btn-sm btn-warning edit' data-id="<?= $user['user_id'] ?>">แก้ไข <span class='glyphicon glyphicon-wrench'></span></button>&nbsp;&nbsp;
                    <?php if ($user['enable'] == 1) { ?><button class='btn btn-sm btn-danger deactive' data-id="<?= $user['user_id'] ?>">ยกเลิก <span class='glyphicon glyphicon-remove'></span></button>
                    <?php } else if ($user['enable'] == 0) { ?><button class='btn btn-sm btn-success active' data-id="<?= $user['user_id'] ?>">เปิดใช้ <span class='glyphicon glyphicon-ok'></span></button><?php } ?>
                </td>
            </tr>
            <?php
        }
    }

    public function actionUsrstatechange() {
        if (isset($_POST['uid']) && isset($_POST['state'])) {
            $st = $_POST['state'];

            $uid = $_POST['uid'];
            $sql = "UPDATE tb_user SET enable = $st "
                    . "WHERE user_id = $uid";
            echo Yii::app()->db->createCommand($sql)->execute() ? "ok" : 0;
        }
    }

    public function actionAskUser() {
        if (isset($_POST['uid'])) {
            $uid = $_POST['uid'];
            $result = TbUser::model()->findByPk(intval($uid));
            echo $result->username;
        }
    }

    public function actionAskFname() {
        if (isset($_POST['uid'])) {
            $uid = $_POST['uid'];
            $result = TbUser::model()->findByPk(intval($uid));
            echo $result->fname;
        }
    }

    public function actionAskLname() {
        if (isset($_POST['uid'])) {
            $uid = $_POST['uid'];
            $result = TbUser::model()->findByPk(intval($uid));
            echo $result->lname;
        }
    }

    public function actionAskPerId() {
        if (isset($_POST['uid'])) {
            $uid = $_POST['uid'];
            $result = TbUser::model()->findByPk(intval($uid));
            echo $result->person_id;
        }
    }

    public function actionAskPosId() {
        if (isset($_POST['uid'])) {
            $uid = $_POST['uid'];
            $result = TbUser::model()->findByPk(intval($uid));
            echo $result->position_id;
        }
    }

    public function actionAskFacId() {
        if (isset($_POST['uid'])) {
            $uid = $_POST['uid'];
            $result = TbUser::model()->findByPk(intval($uid));
            echo $result->faction_id ? $result->faction_id : "";
        }
    }

    public function actionAskDepId() {
        if (isset($_POST['uid'])) {
            $uid = $_POST['uid'];
            $result = TbUser::model()->findByPk(intval($uid));
            echo $result->department_id ? $result->department_id : "";
        }
    }

    public function actionAskGen() {
        if (isset($_POST['uid'])) {
            $uid = $_POST['uid'];
            $result = TbUser::model()->findByPk(intval($uid));
            echo $result->gender;
        }
    }

    public function actionMemberEdit() {
        if (isset($_POST['uid'])) {
            $uid = $_POST['uid'];
            $user = $_POST['username'];
            $pass = $_POST['password'];
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $perid = $_POST['perid'];
            $pos = $_POST['pos'];
            $dep = $_POST['dep'];
            $div = $_POST['div'];
            $gen = $_POST['gen'];
            $oldusrname = TbUser::model()->findByPk(intval($uid))->username;
            $chkusrdup = TbUser::model()->findAll("username = '$user' AND username <> '$oldusrname'");
            if (count($chkusrdup) != 0) {
                echo 'usrdup';
                return;
            }
            $oldperid = TbUser::model()->findByPk(intval($uid))->person_id;
            $chkperiddup = TbUser::model()->findAll("person_id = '$perid' AND person_id <> '$oldperid'");
            if (count($chkperiddup) != 0) {
                echo 'perdup';
                return;
            }
            /* not work !!! why !
             * $result = Yii::app()->db->createCommand(""
              . "UPDATE tb_user SET "
              . "username = '$user',"
              . "password = '$pass',"
              . "fname = '$fname',"
              . "lname = '$lname',"
              . "person_id = '$perid',"
              . "position_id = $pos, "
              . "faction_id = $fac, "
              . "department_id = $dep, "
              . "gender = '$gen' "
              . "WHERE user_id = $uid")->execute();
             * 
             */
            $model = TbUser::model()->findByPk($uid);
            $model->username = $user;
            if ($pass != "")
                $model->password = $pass;
            $model->fname = $fname;
            $model->lname = $lname;
            $model->gender = $gen;
            $model->person_id = $perid;
            $model->position_id = $pos;
            $model->division_id = $div;
            $model->department_id = $dep;
            $result = $model->save();
            if ($result == 1) {
                echo 'ok';
            }
        }
    }
    
    public function actionAddDiv(){
        if(isset($_POST['divname'])){
            $name = $_POST['divname'];
            $erp = $_POST['erp'];
            
            $sql = "INSERT INTO tb_division VALUES(NULL, $erp, '$name', 1)";
            echo Yii::app()->db->createCommand($sql)->execute()?'ok':'fail';
        }
    }
    public function actionDivEdit(){
        if(isset($_POST['did'])){
            $did = $_POST['did'];
            $dname = $_POST['dname'];
            $derp = $_POST['derp'];
            
            $oldname = TbDivision::model()->findByPk(intval($did))->division_name;
            $olderp = TbDivision::model()->findByPk(intval($did))->erp_id;
            
            $chknamedup = TbDivision::model()->findAll("division_name = '$dname' AND division_name <> '$oldname'");
            $chkerpdup = TbDivision::model()->findAll("erp_id = $derp AND erp_id <> $olderp");
            
            if(count($chknamedup)){
                echo "namedup";
                return;
            }else if(count($chkerpdup)){
                echo "erpdup";
                return;
            }
            
            $sql = "UPDATE tb_division SET division_name = '$dname', erp_id = $derp "
                    . "WHERE division_id = $did ";
            echo Yii::app()->db->createCommand($sql)->execute()?"ok":"fail";
        }
    }
    public function actionAskDivInfo(){
        if(isset($_POST['did'])){
            $did = $_POST['did'];
            $model = TbDivision::model()->findByPk(intval($did));
            
            if($model){
                $x = array(
                    'divname'=>$model->division_name,
                    'erp_id'=>$model->erp_id
                );
                echo json_encode($x);
            }
        }
    }

}
