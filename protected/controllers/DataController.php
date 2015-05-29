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

    public function actionFillFac() {
        if (isset($_POST['AJAX'])) {
            $model = TbFaction::model()->findAll();
            foreach ($model as $row):
                ?>
                <option value="<?= $row->fac_id ?>"><?= $row->fac_name ?></option>
                <?php
            endforeach;
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
            $fac = $_POST['fac'];
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
        $stxt = $_POST['searchtxt'] == "" || $_POST['searchtxt'] == NULL ? "" : $_POST['searchtxt'];
        $shwsql;
        if ($stxt != "") {
            $shwsql = "SELECT u.*, tb_position.*, j.dep_name, j.faction_name "
                    . "FROM tb_user as u NATURAL JOIN tb_position "
                    . "LEFT JOIN "
                    . "(SELECT d.dep_id,d.dep_name,f.faction_id,f.faction_name "
                    . "FROM tb_department as d NATURAL JOIN tb_faction as f) as j "
                    . "ON u.faction_id = j.faction_id OR u.department_id = j.dep_id "
                    . "WHERE u.username LIKE '%$stxt%' OR "
                    . "u.fname LIKE '%$stxt%' OR u.lname LIKE '%$stxt%' OR "
                    . "u.person_id LIKE '%$stxt%' OR tb_position.position_name LIKE '%$stxt%'";
        } else {

            $shwsql = "SELECT u.*, tb_position.*, j.dep_name, j.faction_name "
                    . "FROM tb_user as u NATURAL JOIN tb_position "
                    . "LEFT JOIN "
                    . "(SELECT d.dep_id,d.dep_name,f.faction_id,f.faction_name "
                    . "FROM tb_department as d NATURAL JOIN tb_faction as f) as j "
                    . "ON u.faction_id = j.faction_id OR u.department_id = j.dep_id ";
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
                <td><?php if ($user['position_id'] == 1) echo $user['dep_name'] ?></td>
                <td><?= $user['faction_name'] ?></td>
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
            $fac = $_POST['fac'];
            $gen = $_POST['gen'];
            $oldusrname = TbUser::model()->findByPk(intval($uid))->username;
            $chkusrdup = TbUser::model()->findAll("username = '$user' AND username <> '$oldusrname'");
            if(count($chkusrdup)!=0){
                echo 'usrdup';
                return;
            }
            $oldperid = TbUser::model()->findByPk(intval($uid))->person_id;
            $chkperiddup = TbUser::model()->findAll("person_id = '$perid' AND person_id <> '$oldperid'");
            if(count($chkperiddup)!=0){
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
            if($pass!="")$model->password = $pass;
            $model->fname = $fname;
            $model->lname = $lname;
            $model->gender = $gen;
            $model->person_id = $perid;
            $model->position_id = $pos;
            $model->faction_id = $fac;
            $model->department_id = $dep;
            $result = $model->save();
            if($result == 1){
                echo 'ok';
            }
        }
    }

}
