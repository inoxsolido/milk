<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserManagerController
 *
 * @author Ball
 */
class UserManagerController extends Controller {
    //put your code here
    public function actionAddMember()
    {
        if (isset($_POST['u']))
        {
            $user = $_POST['u'];
            $pass = $_POST['p'];
            $fname = $_POST['f'];
            $lname = $_POST['l'];
            $perid = $_POST['pid'];
            $pos = $_POST['pos'];
            $dep = $_POST['dep'];
            $div = $_POST['div'];
            $g = $_POST['g'];
            $d = $pos == 3 ? NULL : $pos == 2 ? $div : $dep;
            $pass = Yii::app()->Encryption->EncryptPassword($pass);
            $result = Yii::app()->db->createCommand("INSERT INTO tb_user VALUES("
                            . "NULL,'$user','$pass','$fname','$lname','$g',"
                            . "'$perid',$d,'$pos',1)")->execute();
            if ($result == 1)
            {
                echo 'ok';
            }
            else
            {
                echo 'not ok';
            }
        }
    }

    public function actionFillUsr()
    {
        if (isset($_POST['ajax']) && isset($_POST['search']['usr']) && isset($_POST['search']['fname']) && isset($_POST['search']['lname']) &&
                isset($_POST['search']['perid']) && isset($_POST['search']['div']) && isset($_POST['search']['pos'])
        )
        {
            $stxt = $_POST['search'];
            $sql = "SELECT u.*, position_name, d.div_name  "
                    . "FROM tb_user as u "
                    . "INNER JOIN tb_position ON  u.position_id = tb_position.position_id "
                    . "LEFT JOIN (SELECT division_id as div_id, division_name as div_name FROM tb_division) d ON u.division_id = d.div_id ";
                    
            if (!(empty($stxt['usr']) && empty($stxt['fname']) && empty($stxt['lname']) &&
                    empty($stxt['perid']) && empty($stxt['div']) && empty($stxt['par']) && ($stxt['pos'] == 99)))
            {
                $sql .= "WHERE ";
                if (!empty($stxt['usr']))
                    $sql .= " u.username LIKE '%" . $stxt['usr'] . "%' AND";
                if (!empty($stxt['fname']))
                    $sql .=" u.fname LIKE '%" . $stxt['fname'] . "%' AND";
                if (!empty($stxt['lname']))
                    $sql .=" u.lname LIKE '%" . $stxt['lname'] . "%' AND";
                if (!empty($stxt['perid']))
                    $sql .=" u.person_id LIKE '" . $stxt['perid'] . "' AND";
                if (!empty($stxt['div']))
                    $sql .=" d.divname = '%" . $stxt['div'] . "%' AND";
                
                if ($stxt['pos'] != 99)
                    $sql .=" u.position_id = " . $stxt['pos'] . " AND";
                $sql = substr($sql, 0, -3);
            }
            $sql .=" ORDER BY position_id DESC, u.enable DESC";
            //echo $sql;
            $userinfo = Yii::app()->db->createCommand($sql)->queryAll();

            foreach ($userinfo as $user)
            {
                ?>
                <tr>
                    <td style="width:10%"><?= $user['username'] ?></td>
                    <td style="width:12.5%"><?= $user['fname'] ?></td>
                    <td style="width:12.5%"><?= $user['lname'] ?></td>
                    <td style="width:20%"><?= $user['person_id'] ?></td>
                    <td style="width:15%"><?= $user['div_name'] ?></td>
                    
                    <td style="width:12%"><?= $user['position_name'] ?></td>
                    <td style="width:150px">
                        <div class='btn-group-sm' style='width:100%'>
                        <button class='btn btn-sm btn-warning edit' style="float:left; width:33%" data-id="<?= $user['user_id'] ?>">แก้ไข <span class='glyphicon glyphicon-wrench'></span></button><?php
                        if ($user['enable'] == 1)
                        {
                            ?><button class='btn btn-sm btn-info deactive' style="float:left; width:33%" data-id="<?= $user['user_id'] ?>">ยกเลิก <span class='glyphicon glyphicon-remove'></span></button>
                                <?php
                            }
                        else if ($user['enable'] == 0)
                        {
                            ?><button class='btn btn-sm btn-success active' style="float:left; width:33%" data-id="<?= $user['user_id'] ?>">เปิดใช้ <span class='glyphicon glyphicon-ok'></span></button><?php 

                        } ?>
                        <button class='btn btn-sm btn-danger del' style="float:left; width:33%" data-id="<?= $user['user_id'] ?>">ลบ <span class='glyphicon glyphicon-trash'></span></button>
                        </div>
                    </td>
                </tr>
                <?php
            }
        }
    }

    public function actionUsrstatechange()
    {
        if (isset($_POST['uid']) && isset($_POST['state']))
        {
            $st = $_POST['state'];

            $uid = $_POST['uid'];
            //ตรวจสอบ admin ว่ามีทั้งหมดกี่คน 
            $check = "SELECT COUNT(user_id) FROM tb_user WHERE user_id != $uid AND position_id = 3 AND enable = 1";
            if($st == 0 && !Yii::app()->db->createCommand($check)->queryScalar()){
                echo 'Admin zero';
            }else{
                $sql = "UPDATE tb_user SET enable = $st "
                        . "WHERE user_id = $uid";
                echo Yii::app()->db->createCommand($sql)->execute();
            }
        }
    }
    
    public function actionDeleteUser(){
        if(isset($_POST['uid'])){
            $uid = $_POST['uid'];
            $pos = TbUser::model()->findByPk("$uid");
            $pos = count($pos)?$pos->position_id:intval(0);
            if(!$pos){
                echo 'no pos';
                return false;
            }
            if ($pos == 3){
                //check quantity of admin
                $check = "SELECT COUNT(user_id) FROM tb_user 
                    WHERE user_id != $uid AND position_id = 3 AND enable = 1";
                if(!Yii::app()->db->createCommand($check)->queryScalar()){
                    echo 'Admin zero';
                    return FALSE;
                }
            }
            echo TbUser::model()->deleteByPk($uid)?'ok':'not';
        }
    }
    
    public function actionAskUserInfo()
    {
        if (isset($_POST['id']))
        {
            $id = $_POST['id'];
            $model = TbUser::model()->findByPk(intval($id));

            $result = array(
                "user" => $model->username,
                "perid" => $model->person_id,
                "fname" => $model->fname,
                "lname" => $model->lname,
                "pos" => $model->position_id,
                "div" => $model->division_id,
                "gen" => $model->gender
            );
            echo json_encode($result);
        }
    }

    public function actionMemberEdit()
    {
        if (isset($_POST['uid']))
        {
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
            if (count($chkusrdup) != 0)
            {
                echo 'usrdup';
                return;
            }
            $oldperid = TbUser::model()->findByPk(intval($uid))->person_id;
            $chkperiddup = TbUser::model()->findAll("person_id = '$perid' AND person_id <> '$oldperid'");
            if (count($chkperiddup) != 0)
            {
                echo 'perdup';
                return;
            }

            $model = TbUser::model()->findByPk($uid);
            $model->username = $user;
            if ($pass != "")
                $model->password = Yii::app()->Encryption->EncryptPassword($pass);
            $model->fname = $fname;
            $model->lname = $lname;
            $model->gender = $gen;
            $model->person_id = $perid;
            $model->position_id = $pos;
            $model->division_id = $pos == 3 ? NULL : $pos == 2 ? $div : $dep;
            $result = $model->save();
            if ($result == 1)
            {
                echo 'ok';
            }
        }
    }
}
