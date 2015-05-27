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
        $usr = Yii::app()->session['username'];
        $shwsql = "SELECT u.*, tb_position.*, j.dep_name, j.faction_name "
                . "FROM tb_user as u NATURAL JOIN tb_position "
                . "LEFT JOIN "
                . "(SELECT d.dep_id,d.dep_name,f.faction_id,f.faction_name "
                . "FROM tb_department as d NATURAL JOIN tb_faction as f) as j "
                . "ON u.faction_id = j.faction_id OR u.department_id = j.dep_id ";
        //."WHERE u.username != '$usr'";
        $userinfo = Yii::app()->db->createCommand($shwsql)->queryAll();

        foreach ($userinfo as $user) {
            ?>
            <tr>
                <td><?= $user['username'] ?></td>
                <td><?= $user['fname'] ?></td>
                <td><?= $user['lname'] ?></td>
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

    public function actionusrstatechange() {
        if (isset($_POST['uid']) && isset($_POST['state'])) {
            $st = $_POST['state'];

            $uid = $_POST['uid'];
            $sql = "UPDATE tb_user SET enable = $st "
                    . "WHERE user_id = $uid";
            echo Yii::app()->db->createCommand($sql)->execute()?"ok":0;
        }
    }

}
