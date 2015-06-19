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
                . "LEFT JOIN tb_division ON u.division_id = tb_division.division_id ";
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

    public function actionFillDiv() {
        if (isset($_POST['ajax'])) {
            $stxt = "";
            if (isset($_POST['searchtxt']))
                $stxt = ($_POST['searchtxt']);
            $sql = "SELECT * FROM tb_division d LEFT JOIN (SELECT division_id as par_id, division_name as par_name FROM tb_division) dd "
                    . "ON d.parent_division = dd.par_id";

            if (!empty($stxt['name']) || !empty($txt['erp']) || !empty($stxt['par']) || !empty($stxt['office']) || $stxt['ispos']!=99) {
                $sql .= " WHERE";
                if (!empty($stxt['name']))
                    $sql .= " d.division_name LIKE '%" . $stxt['name'] . "%' AND";
                if (!empty($stxt['erp']))
                    $sql .= " d.erp_id LIKE '" . $stxt['erp'] . "' AND";
                if (!empty($stxt['office']))
                    $sql .= " d.office_id LIKE '" . $stxt['office'] . "' AND";
                if (($stxt['ispos'])!=99)
                    $sql .= " d.isposition = " . $stxt['ispos']. " AND";
                if(!empty($stxt['par']))
                    $sql .= " dd.par_name LIKE '%". $stxt['par_name'] ."%' AND";
                $sql = substr($sql, 0,-3);
            }
            
            
            $div = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($div as $row) {
                ?><tr>
                    <td style="width:30%"><?= $row['division_name'] ?></td>
                    <td style="width:10%"><?= $row['erp_id'] ?></td>
                    <td style="width:30%"><?= $row['par_name'] ?></td>
                    <td style='width:5%'><?= $row['office_id'] ?></td>
                    <td style="width:5%"><?php echo $row['isposition'] ? 'เป็น' : 'ไม่เป็น'; ?></td>
                    <td style="width:20%"><button class='btn btn-sm btn-warning edit' data-id="<?= $row['division_id'] ?>">แก้ไข <span class='glyphicon glyphicon-wrench'></span></button>&nbsp;&nbsp;
                        <?php if ($row['enable'] == 1) { ?><button class='btn btn-sm btn-danger deactive' data-id="<?= $row['division_id'] ?>">ยกเลิก <span class='glyphicon glyphicon-remove'></span></button>
                <?php } else if ($row['enable'] == 0) { ?><button class='btn btn-sm btn-success active' data-id="<?= $row['division_id'] ?>">เปิดใช้ <span class='glyphicon glyphicon-ok'></span></button><?php } ?>
                    </td>
                </tr><?php
            }
             
             
        }
    }

    public function actionDivStateChange() {
        if (isset($_POST['divid']) && isset($_POST['state'])) {
            $did = $_POST['divid'];
            $st = $_POST['state'];

            $sql = "UPDATE tb_division SET enable = $st WHERE division_id = $did";
            echo Yii::app()->db->createCommand($sql)->execute() ? "ok" : 0;
        }
    }

    public function actionFillDivParentAdd() {
        if (isset($_POST['ajax'])) {
            $model = TbDivision::model()->findAll("enable=1 AND erp_id != '' AND isposition != 1");
            //echo "<option value='0'>ไม่มีสังกัด</option>";
            foreach ($model as $row) {
                ?><option value="<?= $row->division_id ?>"><?= $row->division_name ?></option><?php
            }
        }
    }

    public function actionAddDiv() {
        if (isset($_POST['divname']) && isset($_POST['erp']) && isset($_POST['erpoffice']) && isset($_POST['par']) && isset($_POST['haserp']) && isset($_POST['isdiv'])) {
            $name = $_POST['divname'];
            $erp = $_POST['erp'];
            $officeerp = $_POST['erpoffice'];
            $parent = $_POST['par'];
            $haserp = $_POST['haserp'];
            $isdiv = $_POST['isdiv'];
            $ispos = $_POST['ispos'];
            //sql making
            $sql = "INSERT INTO tb_division VALUES(NULL,'$name',";
            if ($isdiv)
                $sql .= "0,";
            else
                $sql .= "$parent,";

            $sql .= "'$officeerp',";

            if ($haserp)
                $sql .="'$erp',";
            else
                $sql .="NULL,";

            $sql .= "$ispos,1)";
            echo Yii::app()->db->createCommand($sql)->execute() ? 'ok' : 'fail';
        }
    }

    public function actionDivEdit() {
        if (isset($_POST['divid']) && isset($_POST['divname']) && isset($_POST['erp']) && isset($_POST['erpoffice']) && isset($_POST['par']) && isset($_POST['haserp']) && isset($_POST['isdiv'])) {
            $id = $_POST['divid'];
            $name = $_POST['divname'];
            $erp = $_POST['erp'];
            $officeerp = $_POST['erpoffice'];
            $parent = $_POST['par'];
            $haserp = $_POST['haserp'];
            $isdiv = $_POST['isdiv'];
            $ispos = $_POST['ispos'];

            $model = TbDivision::model()->findByPk(intval($id));
            if (count($model)) {
                $model->division_name = $name;
                $model->erp_id = $haserp ? $erp : '';
                $model->office_id = $officeerp;
                $model->parent_division = $isdiv ? intval($parent) : NULL;
                $model->isposition = $ispos ? 1 : 0;
                echo $model->save() ? 1 : 0;
            } else
                echo 0;
        }
    }

    public function actionAskDivInfo() {
        if (isset($_POST['did'])) {
            $did = $_POST['did'];

            $sql = "SELECT * FROM tb_division d LEFT JOIN (SELECT division_id, division_name as par_name FROM tb_division) dd "
                    . "ON d.parent_division = dd.division_id "
                    . "WHERE d.division_id = $did";
            $result = Yii::app()->db->createCommand($sql)->queryAll();

            if ($result) {
                $x = array(
                    'divid' => $result[0]['division_id'],
                    'divname' => $result[0]['division_name'],
                    'erp_id' => $result[0]['erp_id'],
                    'parname' => $result[0]['par_name'],
                    'office_id' => $result[0]['office_id'],
                    'par_id' => $result[0]['parent_division'],
                    'ispos' => $result[0]['isposition']
                );
                echo json_encode($x);
            }
        }
    }

}
