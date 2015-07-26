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
class DataController extends Controller
{

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
            } else
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
            $sql = "SELECT u.*, position_name, d.div_name, par_name  "
                    . "FROM tb_user as u "
                    . "INNER JOIN tb_position ON  u.position_id = tb_position.position_id "
                    . "LEFT JOIN (SELECT division_id as div_id, division_name as div_name, parent_division as par_id FROM tb_division) d ON u.division_id = d.div_id "
                    . "LEFT JOIN (SELECT division_id as par_id, division_name as par_name FROM tb_division) p ON d.par_id = p.par_id ";
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
                if (!empty($stxt['par']))
                    $sql .=" p.par_name = '%" . $stxt['par'] . "%' AND";
                if ($stxt['pos'] != 99)
                    $sql .=" u.position_id = " . $stxt['pos'] . " AND";
                $sql = substr($sql, 0, -3);
            }
            echo $sql;
            $userinfo = Yii::app()->db->createCommand($sql)->queryAll();

            foreach ($userinfo as $user)
            {
                ?>
                <tr>
                    <td style="width:10%"><?= $user['username'] ?></td>
                    <td style="width:12.5%"><?= $user['fname'] ?></td>
                    <td style="width:12.5%"><?= $user['lname'] ?></td>
                    <td style="width:12.5%"><?= $user['person_id'] ?></td>
                    <td style="width:12.5%"><?= $user['div_name'] ?></td>
                    <td style="width:15%"><?= $user['par_name'] ?></td>
                    <td style="width:10%"><?= $user['position_name'] ?></td>
                    <td style="width:15%">
                        <button class='btn btn-sm btn-warning edit' data-id="<?= $user['user_id'] ?>">แก้ไข <span class='glyphicon glyphicon-wrench'></span></button>&nbsp;&nbsp;
                        <?php
                        if ($user['enable'] == 1)
                        {
                            ?><button class='btn btn-sm btn-danger deactive' data-id="<?= $user['user_id'] ?>">ยกเลิก <span class='glyphicon glyphicon-remove'></span></button>
                                <?php
                            } else if ($user['enable'] == 0)
                            {
                                ?><button class='btn btn-sm btn-success active' data-id="<?= $user['user_id'] ?>">เปิดใช้ <span class='glyphicon glyphicon-ok'></span></button><?php } ?>
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
            $sql = "UPDATE tb_user SET enable = $st "
                    . "WHERE user_id = $uid";
            echo Yii::app()->db->createCommand($sql)->execute() ? "ok" : 0;
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

    public function actionFillDiv()
    {
        if (isset($_POST['ajax']))
        {
            $stxt = "";
            if (isset($_POST['searchtxt']))
                $stxt = ($_POST['searchtxt']);
            $sql = "SELECT d.*, par_name FROM tb_division d LEFT JOIN (SELECT division_id as par_id, division_name as par_name FROM tb_division) dd "
                    . "ON d.parent_division = dd.par_id";

            if (!empty($stxt['name']) || !empty($txt['erp']) || !empty($stxt['par']) || !empty($stxt['office']) || $stxt['ispos'] != 99)
            {
                $sql .= " WHERE";
                if (!empty($stxt['name']))
                    $sql .= " d.division_name LIKE '%" . $stxt['name'] . "%' AND";
                if (!empty($stxt['erp']))
                    $sql .= " d.erp_id LIKE '" . $stxt['erp'] . "' AND";
                if (!empty($stxt['office']))
                    $sql .= " d.office_id LIKE '" . $stxt['office'] . "' AND";
                if (($stxt['ispos']) != 99)
                    if ($stxt['ispos'] == 0)
                        $sql .= " d.isposition = 1 AND";
                    else if ($stxt['ispos'] == 1)
                        $sql .= " d.parent_division != 0 AND";
                    else
                        $sql .= " d.isposition = 0 AND d.parent_division = 0 AND";
                if (!empty($stxt['par']))
                    $sql .= " par_name LIKE '%" . $stxt['par'] . "%' AND";
                $sql = substr($sql, 0, -3);
            }
            $sql .= " ORDER BY erp_id ASC, division_name ASC";

            $div = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($div as $row)
            {
                ?><tr>
                    <td style="width:30%"><?= $row['division_name'] ?></td>
                    <td style="width:10%"><?= $row['erp_id'] ?></td>
                    <td style="width:30%"><?= $row['par_name'] ?></td>
                    <td style='width:5%'><?= $row['office_id'] ?></td>
                    <td style="width:5%"><?php echo $row['isposition'] ? 'เป็น' : 'ไม่เป็น'; ?></td>
                    <td style="width:20%"><button class='btn btn-sm btn-warning edit' data-id="<?= $row['division_id'] ?>">แก้ไข <span class='glyphicon glyphicon-wrench'></span></button>&nbsp;&nbsp;
                        <?php
                        if ($row['enable'] == 1)
                        {
                            ?><button class='btn btn-sm btn-danger deactive' data-id="<?= $row['division_id'] ?>">ยกเลิก <span class='glyphicon glyphicon-remove'></span></button>
                                <?php
                            } else if ($row['enable'] == 0)
                            {
                                ?><button class='btn btn-sm btn-success active' data-id="<?= $row['division_id'] ?>">เปิดใช้ <span class='glyphicon glyphicon-ok'></span></button><?php } ?>
                    </td>
                </tr><?php
            }
        }
    }

    public function actionDivStateChange()
    {
        if (isset($_POST['divid']) && isset($_POST['state']))
        {
            $did = $_POST['divid'];
            $st = $_POST['state'];

            $sql = "UPDATE tb_division SET enable = $st WHERE division_id = $did";
            echo Yii::app()->db->createCommand($sql)->execute() ? "ok" : 0;
        }
    }

    public function actionFillDivParentAdd()
    {
        if (isset($_POST['ajax']))
        {
            $model = TbDivision::model()->findAll("enable=1 AND erp_id != '' AND isposition != 1 AND parent_division = 0 ORDER BY erp_id ASC, division_name ASC");
            //echo "<option value='0'>ไม่มีสังกัด</option>";
            foreach ($model as $row)
            {
                ?><option value="<?= $row->division_id ?>"><?= $row->division_name ?></option><?php
            }
        }
    }

    public function actionAddDiv()
    {
        if (isset($_POST['divname']) && isset($_POST['erp']) && isset($_POST['erpoffice']) && isset($_POST['par']) && isset($_POST['haserp']) && isset($_POST['isdiv']))
        {
            $name = $_POST['divname'];
            $erp = $_POST['erp'];
            $officeerp = $_POST['erpoffice'];
            $parent = $_POST['par'];
            $haserp = $_POST['haserp'] == 'true' ? true : false;
            $isdiv = $_POST['isdiv'] == 'true' ? true : false;
            $ispos = $_POST['ispos'] == 'true' ? 1 : 0;

            if (!$isdiv)
            {
                $result = TbDivision::model()->find("division_name = '$name' AND parent_division = $parent");
                if (count($result))
                {
                    echo 'dup';
                    return;
                }
            }


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
                $sql .="'',";

            $sql .= "$ispos,1)";
            echo Yii::app()->db->createCommand($sql)->execute() ? 'ok' : 'fail';
        }
    }

    public function actionDivEdit()
    {
        if (isset($_POST['divid']) && isset($_POST['divname']) && isset($_POST['erp']) && isset($_POST['erpoffice']) && isset($_POST['par']) && isset($_POST['haserp']) && isset($_POST['isdiv']))
        {
            $id = $_POST['divid'];
            $name = $_POST['divname'];
            $erp = $_POST['erp'];
            $officeerp = $_POST['erpoffice'];
            $parent = $_POST['par'];
            $haserp = $_POST['haserp'] == 'true' ? true : false;
            $isdiv = $_POST['isdiv'] == 'true' ? true : false;
            $ispos = $_POST['ispos'] == 'true' ? 1 : 0;

            $parent = $isdiv ? 0 : intval($parent);

            $model = TbDivision::model()->findByPk(intval($id));
            if (count($model))
            {

                $oldname = $model->division_name;
                $oldpar = $model->parent_division;
                if (($oldname != $name || $parent != $oldpar))
                {
                    $result = TbDivision::model()->find("division_name = '$name' AND parent_division = $parent");
                    if (count($result))
                    {
                        echo 'dup';
                        return;
                    }
                }

                $model->division_name = $name;
                $model->erp_id = $haserp ? $erp : '';
                $model->office_id = $officeerp;
                $model->parent_division = $parent;
                $model->isposition = $ispos ? 1 : 0;
                echo $model->save() ? 1 : 0;
            } else
                echo 0;
        }
    }

    public function actionAskDivInfo()
    {
        if (isset($_POST['did']))
        {
            $did = $_POST['did'];

            $sql = "SELECT * FROM tb_division d LEFT JOIN (SELECT division_id, division_name as par_name FROM tb_division) dd "
                    . "ON d.parent_division = dd.division_id "
                    . "WHERE d.division_id = $did";
            $result = Yii::app()->db->createCommand($sql)->queryAll();

            if ($result)
            {
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

    //----filling zone-----
    public function actionFillFilling()
    {
        if (isset($_POST['ajax']))
        {
            $sql = "SELECT tb_profile_fill.owner_div_id as pk1, tb_profile_fill.division_id as pk2, ow.own_name, ow.own_par_name, "
                    . "tp.tar_name, tp.tar_par_name "
                    . "FROM tb_profile_fill "
                    . "INNER JOIN (SELECT division_id as div_id, division_name as own_name, own_par_name FROM tb_division d "
                    . "LEFT JOIN (SELECT division_id as o_div_id, division_name as own_par_name FROM tb_division) oo ON d.parent_division = oo.o_div_id "
                    . ") ow ON tb_profile_fill.owner_div_id = ow.div_id "
                    . "INNER JOIN (SELECT division_id as div_id, division_name as tar_name, tar_par_name FROM tb_division d "
                    . "LEFT JOIN (SELECT division_id as t_div_id, division_name as tar_par_name FROM tb_division) tt ON tt.t_div_id = d.parent_division "
                    . ") tp ON tb_profile_fill.division_id = tp.div_id";
            if (isset($_POST['search']['owner']) && isset($_POST['target']) && isset($_POST['search']['ownerpar']) && isset($_POST['search']['targetpar']))
                if (!empty($_POST['search']['owner']) || !empty($_POST['search']['target']) || !empty($_POST['search']['ownerpar']) || !empty($_POST['search']['targetpar']))
                {
                    $sql .= " WHERE";
                    $s = $_POST['search'];
                    if (!empty($s['owner']))
                        $sql .= " own_name LIKE '%" . $s['owner'] . "%' AND";
                    if (!empty($s['ownerpar']))
                        $sql .= " own_par_name LIKE '%" . $s['ownerpar'] . "%' AND";
                    if (!empty($s['target']))
                        $sql .= " tar_name LIKE '%" . $s['target'] . "%' AND";
                    if (!empty($s['targetpar']))
                        $sql .= " tar_par_name LIKE '%" . $s['targetpar'] . "%' AND";
                    $sql = substr($sql, 0, -3);
                }
            $sql .= " ORDER BY own_name ASC, tar_name ASC";
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            //print_r($result);

            foreach ($result as $row)
            {
                ?><tr>
                    <td style="width:40%"><?= $row['own_name'] . " " . $row['own_par_name'] ?></td>
                    <td style="width:40%"><?= $row['tar_name'] . " " . $row['tar_par_name'] ?></td>
                    <td style="width:20%"><button class='btn btn-sm btn-warning edit' data-id1="<?= $row['pk1'] ?>" data-id2="<?= $row['pk2'] ?>">แก้ไข <span class='glyphicon glyphicon-wrench'></span></button>&nbsp;&nbsp;
                        <button class="btn btn-sm btn-danger delete" data-id1="<?= $row['pk1'] ?>" data-id2="<?= $row['pk2'] ?>">ลบ <span class="glyphicon glyphicon-remove"></span></button>
                    </td>
                </tr><?php
            }
        }
    }

    public function actionFillFillingOwner()
    {
        if (isset($_POST['ajax']))
        {
            $sql = "SELECT division_id as div_id, division_name as div_name, div_par_name "
                    . "FROM tb_division "
                    . "LEFT JOIN (SELECT division_id as par_id, division_name as div_par_name FROM tb_division) p "
                    . "ON tb_division.parent_division = p.par_id ";
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($result as $row)
            {
                ?><option value="<?= $row['div_id'] ?>"><?= $row['div_name'] . " " . $row['div_par_name'] ?></option><?php
            }
        }
    }

    public function actionFillFillingTarget()
    {
        if (isset($_POST['ajax']))
        {
            $sql = "SELECT division_id as div_id, division_name as div_name, div_par_name "
                    . "FROM tb_division "
                    . "LEFT JOIN (SELECT division_id as par_id, division_name as div_par_name FROM tb_division) p "
                    . "ON tb_division.parent_division = p.par_id "
                    . "WHERE division_id NOT IN (SELECT division_id FROM tb_profile_fill)";
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($result as $row)
            {
                ?><option value="<?= $row['div_id'] ?>"><?= $row['div_name'] . " " . $row['div_par_name'] ?></option><?php
            }
        }
    }

    public function actionFillFillingTargetEdit()
    {
        if (isset($_POST['ajax']) && isset($_POST['pk1']) && isset($_POST['pk2']))
        {
            $pk1 = $_POST['pk1'];
            $pk2 = $_POST['pk2'];
            $sql = "SELECT division_id as div_id, division_name as div_name, div_par_name "
                    . "FROM tb_division "
                    . "LEFT JOIN (SELECT division_id as par_id, division_name as div_par_name FROM tb_division) p "
                    . "ON tb_division.parent_division = p.par_id "
                    . "WHERE division_id NOT IN (SELECT division_id FROM tb_profile_fill) OR division_id IN (SELECT division_id FROM tb_profile_fill WHERE owner_div_id = $pk1 AND division_id = $pk2)";
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($result as $row)
            {
                ?><option value="<?= $row['div_id'] ?>"><?= $row['div_name'] . " " . $row['div_par_name'] ?></option><?php
                }
            }
        }

        public function actionFillingDel()
        {
            if (isset($_POST['ajax']) && isset($_POST['id']) && !empty($_POST['id']['id1']) && !empty($_POST['id']['id2']))
            {
                echo TbProfileFill::model()->deleteByPk(array('owner_div_id' => $_POST['id']['id1'], 'division_id' => $_POST['id']['id2']));
            }
        }

        public function actionFillingAdd()
        {
            if (isset($_POST['pk1']) && isset($_POST['pk2']))
            {
                $pk1 = $_POST['pk1'];
                $pk2 = $_POST['pk2'];

                $sql = "INSERT INTO tb_profile_fill VALUES ($pk1,$pk2)";
                echo Yii::app()->db->createCommand($sql)->execute() ? 1 : 0;
            }
        }

        public function actionFillingEdit()
        {
            if (isset($_POST['pk1']) && isset($_POST['pk2']) && isset($_POST['val1']) && isset($_POST['val2']))
            {
                $pk1 = $_POST['pk1'];
                $pk2 = $_POST['pk2'];
                $val1 = $_POST['val1'];
                $val2 = $_POST['val2'];

                $model = TbProfileFill::model()->findByPk(array("owner_div_id" => $pk1, "division_id" => $pk2));

                $model->owner_div_id = $val1;
                $model->division_id = $val2;
                echo $model->save() ? 1 : 0;
            }
        }

        //----account-----
        //--search
        public function actionFillAcc()
        {
            if (isset($_POST['ajax']) && isset($_POST['search']))
            {
                $erp = $_POST['search']['erp'];
                $name = $_POST['search']['name'];
                $group = $_POST['search']['group'];
                $par = $_POST['search']['par'];
                $haspar = $_POST['search']['haspar'];

                $sql = "SELECT a.*, par.par_name,group_name "
                        . "FROM tb_account a "
                        . "LEFT JOIN (SELECT acc_id as par_id, acc_name as par_name FROM tb_account) par ON a.parent_acc_id = par.par_id "
                        . "LEFT JOIN tb_group g ON a.group_id = g.group_id ";
                if (!(empty($erp) && empty($name) && empty($group) && empty($par)))
                {
                    $sql .= " WHERE ";
                    if (!empty($erp))
                        $sql .= " acc_erp LIKE '$erp' AND";
                    if (!empty($name))
                        $sql .= " account_name LIKE '%$name%' AND";
                    if (!empty($group))
                        $sql .= " a.group_id = $group AND";
                    if ($haspar == "true" && !empty($par))
                        $sql .= " par_name LIKE '%$par%' AND";
                    if ($haspar == "false")
                        $sql .= " parent_acc_id IS NULL AND";
                    $sql = substr($sql, 0, -3);
                }
                $sql .= " ORDER BY a.group_id ASC, a.acc_number1 ASC, a.acc_number2 ASC, a.acc_number3 ASC, a.acc_number4 ASC ";
                //echo $sql;
                $result = Yii::app()->db->createCommand($sql)->queryAll();

                if (count($result))
                {
                    foreach ($result as $row)
                    {
                        ?>
                    <tr>
                        <td style='width:8%'><?= $row['acc_erp'] ?></td>
                        <td style='width:32%'><?= $row['acc_name'] ?></td>
                        <td style='width:15%'><?= $row['group_name'] ?></td>
                        <td style='width:25%'><?= $row['par_name'] ?></td>
                        <td style="width:20%"><button class='btn btn-sm btn-warning edit' data-id="<?= $row['acc_id'] ?>">แก้ไข <span class='glyphicon glyphicon-wrench'></span></button>&nbsp;&nbsp;
                            <button class="btn btn-sm btn-danger delete" data-id ="<?= $row['acc_id'] ?>">ลบ <span class="glyphicon glyphicon-remove"></span></button>
                        </td>
                    </tr>
                    <?php
                }
            }
        } else
            echo 'invalid request';
    }

    public function actionFillAccPar()
    {
        if (isset($_POST['ajax']))
        {
            $model = TbAccount::model()->findAll(array('order' => 'group_id ASC, acc_name ASC'));

            foreach ($model as $row)
            {
                ?><option value="<?= $row['acc_id'] ?>"><?= $row['acc_erp'] ?>&nbsp;&nbsp;<?= $row['acc_name'] ?></option><?php
            }
        }
    }

    //delete
    public function actionAccountDel()
    {
        if (isset($_POST['ajax']) && isset($_POST['id']))
        {
            echo TbAccount::model()->deleteByPk(intval($_POST['id'])) ? 1 : 0;
        }
    }

    //add
    public function actionAccountAdd()
    {
        if (isset($_POST['d']))
        {
            $d = $_POST['d'];
            $name = $d['name'];
            $erp = $d['erp'];
            $par = $d['par'];
            $group = $d['group'];
            $haserp = $d['haserp'];
            $haspar = $d['haspar'];

            $parent = TbAccount::model()->findByPk(intval($par));

            if (!count($parent) && $haspar == "true")
            {
                echo 'invalid parent id';
                return;
            }


            $model = new TbAccount();

            if ($model->isNewRecord)
            {
                $arr;
                $number = preg_replace("/[ก-์\s].{0,}|[a-zA-Z\s].{0,}/", "", $name);
                $count = preg_match_all("/[0-9]{1,2}\.|[0-9]{1,2}/", $number, $arr);
                if ($count == 0)
                {
                    $model->acc_number1 = 99;
                    $model->acc_number2 = 0;
                    $model->acc_number3 = 0;
                    $model->acc_number4 = 0;
                }
                if ($count > 0)
                {
                    $model->acc_number1 = $arr[0][0];
                    $model->acc_number2 = 0;
                    $model->acc_number3 = 0;
                    $model->acc_number4 = 0;
                }
                if ($count > 1)
                    $model->acc_number2 = $arr[0][1];
                if ($count > 2)
                    $model->acc_number3 = $arr[0][2];
                if ($count > 3)
                    $model->acc_number4 = $arr[0][3];

                $model->acc_name = $name;
                $model->group_id = $haspar == "true" ? $parent->group_id : $group;
                $model->acc_erp = $haserp == "true" ? $erp : NULL;
                $model->parent_acc_id = $parent ? $par : NULL;
                echo $model->save(false) ? "ok" : "not";
            }
        }
    }

    public function actionAskAccInfo()
    {
        if (isset($_POST['id']))
        {
            $id = $_POST['id'];
            $model = TbAccount::model()->findByPk(intval($id));
            if (count($model))
            {
                $result = array(
                    "name" => $model->acc_name,
                    "erp" => $model->acc_erp,
                    "par" => $model->parent_acc_id,
                    "group" => $model->group_id
                );
                echo json_encode($result);
            }
        }
    }

    public function actionAccountEdit()
    {
        if (isset($_POST['d']))
        {
            $d = $_POST['d'];
            $id = $d['id'];
            $name = $d['name'];
            $erp = $d['erp'];
            $par = $d['par'];
            $group = $d['group'];
            $haserp = $d['haserp'];
            $haspar = $d['haspar'];

            $number = preg_replace("/[ก-์\s].{0,}|[a-zA-Z\s].{0,}/", "", $name);

            //echo empty($number)?99:$number;

            $parent = TbAccount::model()->findByPk(intval($par));

            if (!count($parent) && $haspar == "true")
            {
                echo 'invalid parent id';
                return;
            }

            $model = TbAccount::model()->findByPk(intval($id)); //lv1

            if (!$model->isNewRecord)
            {
                //acc number
                $arr;
                $number = preg_replace("/[ก-์\s].{0,}|[a-zA-Z\s].{0,}/", "", $name);
                $count = preg_match_all("/[0-9]{1,2}\.|[0-9]{1,2}/", $number, $arr);
                if ($count == 0)
                {
                    $model->acc_number1 = 99;
                    $model->acc_number2 = 0;
                    $model->acc_number3 = 0;
                    $model->acc_number4 = 0;
                }
                if ($count > 0)
                {
                    $model->acc_number1 = $arr[0][0];
                    $model->acc_number2 = 0;
                    $model->acc_number3 = 0;
                    $model->acc_number4 = 0;
                }
                if ($count > 1)
                    $model->acc_number2 = $arr[0][1];
                if ($count > 2)
                    $model->acc_number3 = $arr[0][2];
                if ($count > 3)
                    $model->acc_number4 = $arr[0][3];

                $model->acc_name = $name;
                $model->group_id = $haspar == "true" ? $parent->group_id : $group;
                $model->acc_erp = $haserp == "true" ? $erp : NULL;
                $model->parent_acc_id = $haspar == "true" ? $par : NULL;
                $result = $model->save(false) ? "ok" : "not";
                echo $result;
                //group recursive
                if ($result == "ok")
                {
                    //recursive update depth = 4
                    //ch lv2
                    $sql1 = "UPDATE tb_account SET group_id = $model->group_id WHERE parent_acc_id = $model->acc_id";
                    $res1 = Yii::app()->db->createCommand($sql1)->execute();
                    //$res1 = TbAccount::model()->updateAll("group_id = $model->group_id", "parent_acc_id = $model->acc_id");
                    if ($res1)
                    {
                        $level2 = TbAccount::model()->findAll("parent_acc_id = $model->acc_id");
                        if (count($level2))
                        {
                            foreach ($level2 as $lv2)
                            {
                                //ch lv3
                                $sql2 = "UPDATE tb_account SET group_id = $model->group_id WHERE parent_acc_id = $lv2->acc_id";
                                $res2 = Yii::app()->db->createCommand($sql2)->execute();
                                if ($res2)
                                {
                                    $level3 = TbAccount::model()->findAll("parent_acc_id = $lv2->acc_id");
                                    if (count($level3))
                                    {
                                        foreach ($level3 as $lv3)
                                        {
                                            //ch lv4
                                            $sql3 = "UPDATE tb_account SET group_id = $model->group_id WHERE parent_acc_id = $lv3->acc_id";
                                            $res3 = Yii::app()->db->createCommand($sql3)->execute();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } else
                echo 'invalid id';
        }
    }

    // AccountYearAssign
    public function actionFillAccYearEmpty()
    {
        ?><div class="swMain"><?php
        $group = TbGroup::model()->findAll(array('order' => "group_id ASC"));
        $i = 1;
        ?><ul><!--Stepbar--><?php
                foreach ($group as $g)
                {
                    ?><li><a href="#step-<?= $i++ ?>">
                            <span class="stepDesc">
                                ประเภท<?= $g->group_name ?><br />
                            </span>
                        </a>
                    </li><?php
                }
                ?></ul><?php
            $i = 1;
            //main
            foreach ($group as $g)//group
            {
                ?><div id="step-<?= $i++ ?>">
                    <h3 class="StepTitle">บัญชีในประเภท<?= $g->group_name ?></h3>
                    <ul class="checkbox-tree">
                        <li><label><input type="checkbox" name="selall"/>เลือกทั้งหมด</label>
                            <?php
                            $resultlv1 = TbAccount::model()->findAll("parent_acc_id IS NULL AND group_id = $g->group_id");
                            if (count($resultlv1))
                            {
                                ?><ul><?php
                                        foreach ($resultlv1 as $lv1)//level 1
                                        {
                                            ?><li><?php
                                            ?><label><input type="checkbox" name="<?= $lv1->acc_id ?>"/><?= $lv1->acc_name ?></label><?php
                                            $resultlv2 = TbAccount::model()->findAll(array('condition' => "parent_acc_id = $lv1->acc_id", 'order' => "acc_name ASC"));
                                            if (count($resultlv2))
                                            {
                                                ?><ul><?php
                                                        foreach ($resultlv2 as $lv2)//level 2
                                                        {
                                                            ?><li><?php
                                                            ?><label><input type="checkbox" name="<?= $lv2->acc_id ?>"/><?= $lv2->acc_name ?></label><?php
                                                            $resultlv3 = TbAccount::model()->findAll(array('condition' => "parent_acc_id = $lv2->acc_id", 'order' => "acc_name ASC"));
                                                            if (count($resultlv3))
                                                            {
                                                                ?><ul><?php
                                                                        foreach ($resultlv3 as $lv3)//level 3
                                                                        {
                                                                            ?><li><?php
                                                                            ?><label><input type="checkbox" name="<?= $lv3->acc_id ?>"/><?= $lv3->acc_name ?></label><?php
                                                                            $resultlv4 = TbAccount::model()->findAll(array('condition' => "parent_acc_id = $lv3->acc_id", 'order' => "acc_name ASC"));
                                                                            if (count($resultlv4))
                                                                            {
                                                                                ?><ul><?php
                                                                                        foreach ($resultlv4 as $lv4)
                                                                                        {
                                                                                            ?><li><?php
                                                                                            ?><label><input type="checkbox" name="<?= $lv4->acc_id ?>"/><?= $lv4->acc_name ?></label><?php
                                                                                            ?></li><?php
                                                                                    }
                                                                                    ?></ul><?php
                                                                                }
                                                                                ?></li><?php
                                                                        }
                                                                        ?></ul><?php
                                                                }
                                                                ?></li><?php
                                                        }
                                                        ?></ul><?php
                                                }
                                                ?></li><?php
                                        }
                                        ?></ul><?php
                                }
                                ?></li>
                    </ul><?php ?></div><?php
            }//foreach group end
            ?></div><?php
        }

        public function actionAccYear_AccinYear()
        {
            if (isset($_POST['year']))
            {
                $year = $_POST['year'] - 543; //chirst
                $sql = "SELECT acc_id FROM tb_acc_year WHERE `year` = $year";
                $result = Yii::app()->db->createCommand($sql)->queryAll();
                if (count($result))
                {
                    echo json_encode($result);
                } else
                {
                    $year += 543;
                    echo "ไม่พบบัญชีในปีที่ $year";
                }
            } else
            {
                echo 'variable year not available';
            }
        }

        public function actionFillAccYear_Year()
        {
            $sql = "SELECT DISTINCT(`year`) FROM `tb_acc_year` ORDER BY `Year` ASC";
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            ?><option value=0>เลือกปีที่จะแก้ไข</option><?php
            foreach ($result as $row)
            {
                ?><option value="<?= $row['year'] + 543 ?>">พ.ศ. <?= $row['year'] + 543 ?></option><?php
        }
    }

    public function actionAddAccYear()
    {
        if (isset($_POST['year']) && isset($_POST['fdata']))
        {
            $year = $_POST['year'] - 543;
            $form = $_POST['fdata'];
            foreach ($form as $row)
            {
                $model = new TbAccYear;
                if (!$model->isNewRecord)
                {
                    echo 'การบันทึกล้มเหลว';
                } else
                {
                    $model->year = $year;
                    $model->acc_id = intval($row);
                    if (!$model->save(true))
                    {
                        echo 'การบันทึกล้มเหลว';
                        return false;
                    }
                }
            }

            echo 'ok';
        } else
            echo 'year or fdata is not available';
    }

    public function actionEditAccYear()
    {
        if (isset($_POST['year']))
        {
            $year = $_POST['year'] - 543;
            if (isset($_POST['fdata']))
            {
                $form = $_POST['fdata'];
                $sql = "DELETE FROM `tb_acc_year` WHERE `year` = $year; INSERT INTO `tb_acc_year` VALUES";
                foreach ($form as $row)
                {
                    $sql .= "($year,$row),";
                }
                $sql = substr($sql, 0, -1);
                $sql .= ';';
            } else
                $sql = "DELETE FROM `tb_acc_year` WHERE `year` = $year;";
            $result = Yii::app()->db->createCommand($sql)->execute();
            if ($result)
                echo 'ok';
            else
                echo isset($_POST['fdata']) ? 'การบันทึกข้อมูลล้มเหลว' : 'การลบข้อมูลล้มเหลว';
        } else
            echo 'year  is not available';
    }

    //chinfo
    public function actionAskPersonInfo()
    {
        if (isset($_POST['uname']))
        {
            $uname = $_POST['uname'];
            $model = TbUser::model()->find("`username` = '$uname'");
            if (count($model))
            {
                $x = [
                    'fname' => $model->fname,
                    'lname' => $model->lname,
                ];
                echo json_encode($x);
            } else
            {
                echo 'ไม่มีผู้ใช้นี้ในระบบ';
            }
        }
    }

    public function actionUpdateUserInfo()
    {
        if (isset($_POST['uname']) && isset($_POST['fname']) && $_POST['lname'] && $_POST['pwd'])
        {
            $usr = $_POST['uname'];
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $pwd = $_POST['pwd'];
            $sql = "UPDATE tb_user SET `fname` = '$fname', `lname` = '$lname'";
            if ($pwd != "")
            {
                $pwd = Yii::app()->Encryption->EncryptPassword($pwd);
                $sql .= ",`password` = '$pwd'";
            }
            $sql .= " WHERE `username` = '$usr'";
            $result = Yii::app()->db->createCommand($sql)->execute();
            if ($result)
            {
                echo 'ok';
            } else
                echo "update error";
        } else
            echo 'some resource missing';
    }

}
