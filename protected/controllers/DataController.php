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
            //echo $sql;
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
                            }
                            else if ($user['enable'] == 0)
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
            $sql = "SELECT d.*, dl.description as dldes,sct.section_name as sectname,par_name FROM tb_division d "
                    . "INNER JOIN tb_division_level dl ON d.division_level = dl.ID  "//AND dl.ID <= 3
                    . "LEFT JOIN (SELECT division_id as par_id, division_name as par_name FROM tb_division) dd "
                    . "ON d.parent_division = dd.par_id "
                    . "LEFT JOIN tb_section sct ON sct.section_id = d.section ";

            if (!empty($stxt['name']) || !empty($txt['erp']) || !empty($stxt['par']) || !empty($stxt['office']) || $stxt['status'] != 99)
            {
                $sql .= " WHERE";
                if (!empty($stxt['name']))
                    $sql .= " d.division_name LIKE '%" . $stxt['name'] . "%' AND";
                if (!empty($stxt['erp']))
                    $sql .= " d.erp_id LIKE '" . $stxt['erp'] . "' AND";
                if (!empty($stxt['office']))
                    $sql .= " d.office_id LIKE '" . $stxt['office'] . "' AND";
                if (($stxt['status']) != 99)
                    $sql .= " d.division_level = ".$stxt['status']." AND";
                if (!empty($stxt['par']))
                    $sql .= " par_name LIKE '%" . $stxt['par'] . "%' AND";
                $sql = substr($sql, 0, -3);
            }
            $sql .= " ORDER BY erp_id ASC, division_name ASC";
            
            $div = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($div as $row)
            {
                ?><tr>
                    <td style="width:200px"><?= $row['division_name'] ?></td>
                    <td style="width:50px"><?= $row['erp_id'] ?></td>
                    <td style="width:200px"><?= $row['par_name'] ?></td>
                    <td style="width:100px"><?= $row['sectname'] ?></td>
                    <td style='width:50px'><?= $row['office_id'] ?></td>
                    <td style="width:100px"><?= $row['dldes'] ?></td>
                    <td style="width:160px"><button class='btn btn-sm btn-warning edit' data-id="<?= $row['division_id'] ?>">แก้ไข <span class='glyphicon glyphicon-wrench'></span></button>&nbsp;&nbsp;
                        <?php
                        if ($row['enable'] == 1)
                        {
                            ?><button class='btn btn-sm btn-danger deactive' data-id="<?= $row['division_id'] ?>">ยกเลิก <span class='glyphicon glyphicon-remove'></span></button>
                                <?php
                            }
                            else if ($row['enable'] == 0)
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
            $model = TbDivision::model()->findAll("enable=1 AND division_level = 3  ORDER BY erp_id ASC, division_name ASC");
            //echo "<option value='0'>ไม่มีสังกัด</option>";
            foreach ($model as $row)
            {
                ?><option value="<?= $row->division_id ?>"><?= $row->erp_id ?> -- <?= $row->division_name ?></option><?php
            }
        }
    }
    public function actionFillDivSubParentAdd(){
        if(isset($_POST['ajax'])){
            $model = TbDivision::model()->findAll("enable = 1 AND division_level = 2 ORDER BY erp_id");
            
            foreach ($model as $row)
            {
                ?><option value="<?= $row->division_id ?>"><?= $row->erp_id ?> -- <?= $row->division_name ?></option><?php
            }
        }
    }

    public function actionAddDiv()
    {
        if (isset($_POST['divname']) && isset($_POST['erp']) && isset($_POST['erpoffice']) && isset($_POST['par']) && isset($_POST['haserp']) && isset($_POST['dlevel']) && isset($_POST['section']))
        {
            $name = $_POST['divname'];
            $erp = $_POST['erp'];
            $officeerp = $_POST['erpoffice'];
            $parent = $_POST['par'];
            $haserp = $_POST['haserp'] == 'true' ? true : false;
            $dlevel = $_POST['dlevel'];
            $section = $_POST['section'];
            $hassub = $_POST['hassub'] == 'true'? true: false;
            $subparent = $_POST['subparent'];
            $parent = $dlevel == 3 ? intval(0) : intval($parent);
            $subparent = $hassub? intval($subparent) : intval(0);
            if ($dlevel != 3)
            {
                $result = TbDivision::model()->find("division_name = '$name' AND parent_division = $parent");
                if (count($result))
                {
                    echo 'dup';
                    return;
                }
                if($subparent != 0){
                    $result = TbDivision::model()->find("division_id = $subparent");
                    $parent = $result->parent_division;
                }
                $result = TbDivision::model()->find("division_id = $parent");
                $section = $result->section;
            }

            //sql making
            $sql = "INSERT INTO tb_division VALUES(NULL,'$name',";
            if ($section == 3)
                $sql .= "0,";
            else
                $sql .= "$parent,";

            $sql .= "$subparent,";
            
            $sql .= "'$officeerp',";

            if ($haserp)
                $sql .="'$erp',";
            else
                $sql .="'',";
            
            $sql .= "$dlevel,";
            $sql .= "$section,";
            $sql .= "1)";
            echo Yii::app()->db->createCommand($sql)->execute() ? 'ok' : 'fail';
        }
    }

    public function actionDivEdit()
    {
        if (isset($_POST['divid']) && isset($_POST['divname']) && isset($_POST['erp']) && isset($_POST['erpoffice']) && isset($_POST['par']) && isset($_POST['haserp']) && isset($_POST['section'])&&isset($_POST['dlevel']))
        {
            $id = $_POST['divid'];
            $name = $_POST['divname'];
            $erp = $_POST['erp'];
            $officeerp = $_POST['erpoffice'];
            $parent = $_POST['par'];
            $haserp = $_POST['haserp'] == 'true' ? true : false;
            $section = $_POST['section'];
            $dlevel = $_POST['dlevel'];
            $subparent = $_POST['subparent'];
            $hassub = $_POST['hassub'] == 'true'? true: false;
            
            $parent = $dlevel == 3 ? intval(0) : intval($parent);
            $subparent = $hassub? intval($subparent) : intval(0);
            
            $model = TbDivision::model()->findByPk(intval($id));
            if (count($model))
            {

                $oldname = $model->division_name;
                $oldpar = $model->parent_division;
                $doldlevel = $model->division_level;
                if (($oldname != $name || $parent != $oldpar))
                {
                    $result = TbDivision::model()->find("division_name = '$name' AND sub_parent=$subparent AND parent_division = $parent");
                    if (count($result))
                    {
                        echo 'dup';
                        return;
                    }
                }
                
                if($dlevel < $doldlevel){//เลื่อนระดับลง
                    //เช็คว่ามีลูกมั้ย
                    //ถ้ามีลูก ไม่อนุญาต
                    if(TbDivision::model()->find("parent_division = $id or sub_parent = $id")){
                        echo 'child';
                        return;
                    }
                }
                
                if($parent != 0){
                    $result = TbDivision::model()->find("division_id = $parent");
                    $section = $result->section;
                    if($result->division_level == 2){
                        $subparent = $parent;
                        $parent = $result->parent_division;
                    }
                }

                $model->division_name = $name;
                $model->erp_id = $haserp ? $erp : '';
                $model->office_id = $officeerp;
                $model->parent_division = $parent;
                $model->sub_parent = $subparent;
                $model->division_level = $dlevel;
                $model->section = $section;
                $saveResult =  $model->save() ? 1 : 0;
                if($saveResult)
                {
                    
                    $transaction = Yii::app()->db->beginTransaction();
                    try{
                        if($dlevel == 3 && $doldlevel == 2){ //เพิ่มระดับ จาก 2 ไป 3
                            $sql = "UPDATE tb_division SET section = $section, sub_parent = 0, parent_division = $id WHERE sub_parent = $id";
                        }else if($dlevel == 3){//กรณีของฝ่ายเปลี่ยนข้อมูล section อาจเปลี่ยนแปลงได้
                            $sql = "UPDATE tb_division SET section = $section WHERE parent_division = $id";
                        }else if($dlevel == 2){
                            $sql = "UPDATE tb_division SET section = $section AND parent_division = $parent WHERE sub_parent = $id";
                        }else{
                            $sql = "UPDATE tb_division SET section = $section, parent_division = $parent WHERE parent_division = $id";
                        }
                        Yii::app()->db->createCommand($sql)->execute();
                        $transaction->commit();
                        echo 1;
                    } catch (Exception $ex) {
                        $transaction->rollback();
                        echo 0;
                    }
                }
            }
            else
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
                    'dlevel' => $result[0]['division_level'],
                    'section' => $result[0]['section'],
                    'sub'=>$result[0]['sub_parent']
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
                    . "ON tb_division.parent_division = p.par_id ORDER BY tb_division.erp_id";
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($result as $row)
            {
                ?><option value="<?= $row['div_id'] ?>"><?= $row['div_par_name'] . " -- " .  $row['div_name'] ?></option><?php
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
                    . "WHERE division_id NOT IN (SELECT division_id FROM tb_profile_fill) ORDER BY tb_division.erp_id";
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($result as $row)
            {
                ?><option value="<?= $row['div_id'] ?>"><?= $row['div_par_name'] . " -- " .  $row['div_name'] ?></option><?php
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
                ?><option value="<?= $row['div_id'] ?>"><?= $row['div_par_name'] . " -- " .  $row['div_name'] ?></option><?php
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
                $pk1 = intval($_POST['pk1']);
                $pk2 = intval($_POST['pk2']);

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
        }
        else
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
    public function actionAccSib(){
        if(isset($_POST['pid'])){
            $pid = $_POST['pid'];
            $aid = intval(0);
            if(isset($_POST['aid']))$aid = $_POST['aid'];
            $where = "= $pid";
            if($pid == intval(0)){
                $where = "IS NULL";
            }
            $model = TbAccount::model()->findAll("parent_acc_id $where AND acc_id != $aid ORDER BY `order`, acc_name");
            ?><option value="0">อยู่บนสุด</option><?php
            foreach ($model as $row){
                ?><option value="<?= $row['acc_id'] ?>"><?= $row['acc_name'] ?></option><?php
            }
        }
    }
    //delete
    public function actionAccountDel()
    {
        if (isset($_POST['ajax']) && isset($_POST['id']))
        {
            $am = TbAccount::model()->findByPk(intval($_POST['id']));
            if(!$am){echo 0; return; }
            $par = $am->parent_acc_id;
            $transaction = Yii::app()->db->beginTransaction();
            try{
                Yii::app()->db->createCommand("UPDATE tb_account SET `order` = `order` - 1 WHERE `order` > $am->order")->execute();
                TbAccount::model()->deleteByPk(intval($_POST['id']));
                $transaction->commit();
            echo '1';
            }catch(Exception $ex){
                $transaction->rollback();
                echo '2';
            }
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
            $haspar = $d['haspar']=='true'?TRUE:FALSE;
            $hassum = $d['hassum']=='true'?TRUE:FALSE;
            $order = intval($d['order']);

            $parent = TbAccount::model()->findByPk(intval($par));

            if (!count($parent) && $haspar)
            {
                echo 'invalid parent id';
                return;
            }

            $transaction = Yii::app()->db->beginTransaction();
            try{
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
                $model->hasSum = intval($hassum);
                //จัดลำดับ 
                //ตรวจสอบพี่น้อง
                
                $pid = $par;
                $where = '='.$pid;
                if (!$haspar) {
                    $where = "IS NULL";
                }
                $sib = TbAccount::model()->findAll("parent_acc_id $where ORDER BY `order`, acc_name");
                if(!count($sib)){//ไม่มีพี่น้อง
                    $model->order = intval(0);
                }else{//มีพี่น้อง
                    if($order == 0){
                        $sql="UPDATE tb_account SET `order` = `order`+1 WHERE `order` > $order; ";
                        Yii::app()->db->createCommand($sql)->execute();
                        $model->order = 1;
                    }else{
                    //หาลำดับ
                        $order = TbAccount::model()->findByPk($order)->order;
                        $sql="UPDATE tb_account SET `order` = `order`+1 WHERE `order` > $order; ";
                        Yii::app()->db->createCommand($sql)->execute();
                        $model->order =$order + 1;
                        
                    }
                    
                }
                $model->save();
                $transaction->commit();
                echo 'ok';
            }
            }catch(Exception $ex){
                $transaction->rollback();
                echo 'not';
            }
        }
    }

    public function actionAskAccInfo()
    {
        if (isset($_POST['id']))
        {
            $id = $_POST['id'];
            $model = TbAccount::model()->findByPk(intval($id));
            $orderid = intval(0);
            
            if (count($model))
            {
                $x = $model->order;
                $where = $model->parent_acc_id == NULL?"IS NULL":'='.$model->parent_acc_id;
                $ooo = Yii::app()->db->createCommand("SELECT * FROM tb_account WHERE parent_acc_id $where  AND `order` < $x ORDER BY `order` DESC")->queryRow();
                if(($ooo)) $orderid = $ooo['acc_id'];
                $result = array(
                    "name" => $model->acc_name,
                    "erp" => $model->acc_erp,
                    "par" => $model->parent_acc_id,
                    "group" => $model->group_id,
                    "hassum" => $model->hasSum,
                    "order" =>$orderid,
                    //'sql'=>"SELECT * FROM tb_account WHERE parent_acc_id $where  AND `order` < $x ORDER BY `order`",
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
            $haserp = $d['haserp']=='true'?TRUE:FALSE;
            $haspar = $d['haspar']=='true'?TRUE:FALSE;
            $hassum = $d['hassum']=='true'?TRUE:FALSE;
            $order = intval($d['order']);
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
                $model->hasSum = intval($hassum);
                
                $pid = $par;
                $where = '='.$pid;
                if (!$haspar) {
                    $where = "IS NULL";
                }
                $sib = TbAccount::model()->findAll("parent_acc_id $where ORDER BY `order`, acc_name");
                if(!count($sib)){//ไม่มีพี่น้อง
                    $model->order = intval(0);
                }else{//มีพี่น้อง
                    if($order == 0){
                        $sql="UPDATE tb_account SET `order` = `order`+1 WHERE `order` > $order; ";
                        Yii::app()->db->createCommand($sql)->execute();
                        $model->order = 1;
                    }else{
                    //หาลำดับ
                        $order = TbAccount::model()->findByPk($order)->order;
                        $sql="UPDATE tb_account SET `order` = `order`+1 WHERE `order` > $order; ";
                        Yii::app()->db->createCommand($sql)->execute();
                        $model->order =$order + 1;
                        
                    }
                    
                }
                $result = $model->save(false) ? "ok" : "not";
                //echo $result;
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
                echo $result;
            }
            else
                echo 'invalid id';
        }
    }

    // AccountYearAssign
    public function actionFillAccYearEmpty()
    {
        ?><div class="swMain2"><?php
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
                    <h2 class="StepTitle">บัญชีในประเภท<?= $g->group_name ?></h2>
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
                }
                else
                {
                    $year += 543;
                    echo "ไม่พบบัญชีในปีที่ $year";
                }
            }
            else
            {
                echo 'variable year not available';
            }
        }

        public function actionFillAccYear_Year()
        {
            $sql = "SELECT DISTINCT(`year`) FROM `tb_acc_year` ORDER BY `Year` ASC";
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            ?><option value=0>เลือกปี</option><?php
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
            $transaction = Yii::app()->db->beginTransaction();
            try
            {
                //write acc_year
                foreach ($form as $row)
                {
                    $model = new TbAccYear;
                    if ($model->isNewRecord)
                    {
                        $model->year = $year;
                        $model->acc_id = intval($row);
                        $model->save(true);
                    }else{
                        throw new Exception("Error while save acc_year");
                    }
                }
                //write approve
                //กำหนดapproveให้ทุกdivisionที่เปิดใช้งาน
                $divs = TbDivision::model()->findAll("division_level < 3 AND enable = 1");
                foreach($divs as $div){
                    $amodel = new TbApprove;
                    if($amodel->isNewRecord){
                        /* @var $div TbDivision */
                        $amodel->division_id = $div->division_id;
                        $amodel->year = $year;
                        $amodel->approve_lv = intval(0);
                        $amodel->save(true);
                    }else{
                        throw new Exception("Error while set approve");
                    }
                }
                $transaction->commit();
                echo 'ok';
            } catch (Exception $ex)
            {
                $transaction->rollback();
                echo 'การบันทึกล้มเหลว';
                //print_r($ex->getTraceAsString());
            }
        }
        else
            echo 'year or fdata is not available';
    }

    public function actionEditAccYear() {
        if (isset($_POST['year'])) {
            $year = $_POST['year'] - 543;
            $transaction = Yii::app()->db->beginTransaction();
            try {
                //about acc_year
                //delete old data from acc_year at year
                TbAccYear::model()->deleteAll("year = $year");
                //insert new data if acc_id was sent; mean method is edit
                if (isset($_POST['fdata'])) {

                    //insert new data to acc_year
                    $accs = $_POST['fdata'];
                    foreach ($accs as $acc) {
                        $model = new TbAccYear;
                        $model->year = $year;
                        $model->acc_id = $acc;
                        $model->save(true);
                    }
                    //about approve
                    //update approve to 0
                    TbApprove::model()->updateAll(array("approve_lv" => intval(0)), "year = $year");
                } else {// mean method is delete
                    //delete approve from year
                    TbApprove::model()->deleteAll("year = $year");
                }
                
                $transaction->commit();
                echo 'ok';
            } catch (Exception $ex) {
                $transaction->rollback();
                echo isset($_POST['fdata']) ? 'การบันทึกข้อมูลล้มเหลว' : 'การลบข้อมูลล้มเหลว';
                print_r($ex);
            }
        } else
            echo 'year was not found';
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
            }
            else
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
            }
            else
                echo "การเปลี่ยนแปลงข้อมูลล้มเหลว: ข้อมูลเหมือนใหม่กับข้อมูลเดิม";
        }
        else
            echo 'การเปลี่ยนแปลงข้อมูลล้มเหลว';
    }

    //month goal
    public function actionFillVersionSelector()
    {
        if (isset($_POST['year']) && isset($_POST['div']))
        {
            $year = $_POST['year'] - 543;
            $user = Yii::app()->user->userId;
            $div = $_POST['div'];
            //$versions = Yii::app()->db->createCommand("SELECT `version` FROM (tb_acc_year NATURAL JOIN tb_month_goal g)")->queryAll();
            $versions = Yii::app()->db->createCommand()->selectDistinct("tb_version.version")
                            ->from("tb_month_goal")->join("tb_version", "tb_month_goal.month_goal_id = tb_version.month_goal_id")
                            ->where("user_id = $user AND division_id = $div AND year = $year")->queryAll();
            if (count($versions))
            {
                ?><option value="0">เลือกเวอร์ชั่น</option><?php
                foreach ($versions as $ver)
                {
                    ?><option value="<?= $ver['version'] ?>"><?= $ver['version'] ?></option><?php
                }
            }
            else
            {
                ?><option value="0">--ไม่มีเวอร์ชั่นสำหรับปีนี้--</option><?php
            }
        }
    }

    public function actionFillMonthGoalAccSelect()
    {
        if (!isset($_POST["year"]))
        {
            echo "error variable missing !";
            return FALSE;
        }
        $year = $_POST['year'] - 543;
        $accinyear = Yii::app()->db->createCommand()->select("acc_id")->from("tb_acc_year")->where("year = $year")->queryAll();
        if (empty($accinyear))
        {
            echo 'invalid parameter';
            return FALSE;
        }
        $in = "AND acc_id IN (";
        foreach ($accinyear AS $acc)
        {
            $in .= $acc['acc_id'] . ', ';
        }
        $in = substr($in, 0, -2);
        $in .= ")";
        ?><div class="swMain2"><?php
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
                    <h2 class="StepTitle">บัญชีในประเภท<?= $g->group_name ?></h2>
                    <ul class="checkbox-tree">
                        <li><label><input type="checkbox" name="selall"/>เลือกทั้งหมด</label>
                            <?php
                            $resultlv1 = TbAccount::model()->findAll("parent_acc_id IS NULL AND group_id = $g->group_id $in");
                            if (count($resultlv1))
                            {
                                ?><ul><?php
                                        foreach ($resultlv1 as $lv1)//level 1
                                        {
                                            ?><li><?php
                                            ?><label><input type="checkbox" name="<?= $lv1->acc_id ?>"/><?= $lv1->acc_name ?></label><?php
                                            $resultlv2 = TbAccount::model()->findAll(array('condition' => "parent_acc_id = $lv1->acc_id $in", 'order' => "acc_name ASC"));
                                            if (count($resultlv2))
                                            {
                                                ?><ul><?php
                                                        foreach ($resultlv2 as $lv2)//level 2
                                                        {
                                                            ?><li><?php
                                                            ?><label><input type="checkbox" name="<?= $lv2->acc_id ?>"/><?= $lv2->acc_name ?></label><?php
                                                            $resultlv3 = TbAccount::model()->findAll(array('condition' => "parent_acc_id = $lv2->acc_id $in", 'order' => "acc_name ASC"));
                                                            if (count($resultlv3))
                                                            {
                                                                ?><ul><?php
                                                                        foreach ($resultlv3 as $lv3)//level 3
                                                                        {
                                                                            ?><li><?php
                                                                            ?><label><input type="checkbox" name="<?= $lv3->acc_id ?>"/><?= $lv3->acc_name ?></label><?php
                                                                            $resultlv4 = TbAccount::model()->findAll(array('condition' => "parent_acc_id = $lv3->acc_id $in", 'order' => "acc_name ASC"));
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

        public function actionFillMonthGoalEmpty()
        {
            if (!isset($_POST['accarr']) || !is_array($_POST['accarr']))
            {
                echo 'parameter invalid';
                return FALSE;
            }
            $checkbox = $_POST['accarr'];
            $in = "acc_id IN(";
            foreach ($checkbox as $ch)
            {
                $in .= "$ch, ";
            }
            $in = substr($in, 0, -2);
            $in .= ")";
            //echo $in; return;
            //$resultlv1 = TbAccount::model()->findAllSql("parent_acc_id IS NULL ORDER BY `acc_number1` ASC,`acc_number2` ASC,`acc_number3` ASC,`acc_number4` ASC ");
            //print_r($resultlv1);return;
            ?>


        <div class="swMain wizard-2"><?php
            $month = TbMonth::model()->findAll(array('order' => "`quarter` ASC,`month_id` ASC"));
            $i = 1;
            ?><ul><!--Stepbar--><?php
                foreach ($month as $m)
                {
                    ?><li><a href="#step-<?= $i++ ?>">
                            <span class="stepDesc">
                                <?= $m->month_name ?><br />
                            </span>
                        </a>
                    </li><?php
                }
                ?></ul><?php
            $i = 1;
            //main
            foreach ($month as $m)//group
            {
                ?><div id="step-<?= $i++ ?>">
                    <h3 class="StepTitle">กรอกงบประมาณสำหรับเดือน<?= $m->month_name ?></h3>
                    <?php
                    $resultlv1 = TbAccount::model()->findAll("parent_acc_id IS NULL AND $in ORDER BY `acc_number1` ASC,`acc_number2` ASC,`acc_number3` ASC,`acc_number4` ASC ");
                    if (count($resultlv1))
                    {
                        ?><ul class="checkbox-tree"><?php
                            foreach ($resultlv1 as $lv1)//level 1
                            {
                                ?><li><?php
                                ?><label><?= $lv1->acc_name ?> </label><?php if (!$this->hasChild($lv1->acc_id)): ?>:&nbsp;<input type="text" name="acc-<?= $lv1->acc_id ?>" month="<?= $m->month_id ?>" /><span class="text-danger err"></span><?php else: ?>&nbsp;<a href="#" class="sh" tabindex="-1"><i class="glyphicon glyphicon-minus"></i></a><?php
                                    endif;
                                    $resultlv2 = TbAccount::model()->findAll(array('condition' => "parent_acc_id = $lv1->acc_id AND $in", 'order' => "`acc_number1` ASC,`acc_number2` ASC,`acc_number3` ASC,`acc_number4` ASC "));
                                    if (count($resultlv2))
                                    {
                                        ?><ul><?php
                                                foreach ($resultlv2 as $lv2)//level 2
                                                {
                                                    ?><li><?php
                                                    ?><label><?= $lv2->acc_name ?> </label><?php if (!$this->hasChild($lv2->acc_id)): ?>:&nbsp;<input type="text" name="acc-<?= $lv2->acc_id ?>" month="<?= $m->month_id ?>" /><span class="text-danger err"></span><?php else: ?>&nbsp;<a href="#" class="sh" tabindex="-1"><i class="glyphicon glyphicon-minus"></i></a><?php
                                                    endif;
                                                    $resultlv3 = TbAccount::model()->findAll(array('condition' => "parent_acc_id = $lv2->acc_id AND $in", 'order' => "`acc_number1` ASC,`acc_number2` ASC,`acc_number3` ASC,`acc_number4` ASC "));
                                                    if (count($resultlv3))
                                                    {
                                                        ?><ul><?php
                                                                foreach ($resultlv3 as $lv3)//level 3
                                                                {
                                                                    ?><li><?php
                                                                    ?><label><?= $lv3->acc_name ?> </label><?php if (!$this->hasChild($lv3->acc_id)): ?>:&nbsp;<input type="text" name="acc-<?= $lv3->acc_id ?>" month="<?= $m->month_id ?>" /><span class="text-danger err"></span><?php else: ?>&nbsp;<a class="sh"><a href="#" class="sh" tabindex="-1"><i class="glyphicon glyphicon-minus"></i></a><?php
                                                                    endif;
                                                                    $resultlv4 = TbAccount::model()->findAll(array('condition' => "parent_acc_id = $lv3->acc_id AND $in", 'order' => "`acc_number1` ASC,`acc_number2` ASC,`acc_number3` ASC,`acc_number4` ASC "));
                                                                    if (count($resultlv4))
                                                                    {
                                                                        ?><ul><?php
                                                                                    foreach ($resultlv4 as $lv4)
                                                                                    {
                                                                                        ?><li><?php
                                                                                        ?><label><?= $lv4->acc_name ?> </label><?php if (!$this->hasChild($lv4->acc_id)): ?>:&nbsp;<input type="text" name="acc-<?= $lv4->acc_id ?>" month="<?= $m->month_id ?>" /><span class="text-danger err"></span><?php else: ?>&nbsp;<a href="#" class="sh" tabindex="-1"><i class="glyphicon glyphicon-minus"></i></a><?php endif;
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
                        ?>
                        <?php ?></div><?php
                }//foreach group end
                ?></div>

        <?php
    }

    private function hasChild($me)
    {
        $result = TbAccount::model()->findAll("`parent_acc_id` = $me");
        return (count($result)) != 0;
    }

    public function actionAddMonthGoal()
    {
        if (!isset($_POST['year']) || !isset($_POST['fdata']) || in_array(NULL, $_POST['fdata']) || !isset($_POST['target']) || empty($_POST['target']))
        {
            echo 'invalid parameter';
            return fasle;
        }
        $year = $_POST['year'] - 543;
        $form = $_POST['fdata'];

        $user = Yii::app()->user->userId;
        $div = $_POST['target'];

        //find max versions
        //"SELECT MAX(version) AS mversion FROM `tb_month_goal` NATURAL JOIN `tb_acc_year` WHERE `tb_acc_year`.`year` = $year ";
        //if $max is empty : first
        $resultResource = Yii::app()->db->createCommand()
                        ->select("MAX(version) AS mversion, MAX(approve1_lv) AS mapprove1, MAX(approve2_lv) AS mapprove2")
                        ->from("tb_month_goal")->join("tb_acc_year", "tb_month_goal.year = tb_acc_year.year AND tb_month_goal.acc_id = tb_acc_year.acc_id")
                        ->where("tb_acc_year.`year` = $year AND `user_id` = $user AND `division_id` = $div ")->queryRow();
        //print_r($resultResource);
        $approve1 = 0;
        $approve2 = 0;
        $approve1 = intval($approve1);
        $approve2 = intval($approve2);
        $max = 1;

        //echo in_array(NULL,$resultResource)?"TRUE":"FALSE";return;
        if (!in_array(NULL, $resultResource))//if exist go backup
        {// has been completed : backup
            //echo 'Resource not empty';
            $approve1 = intval($resultResource['mapprove1']);
            $approve2 = intval($resultResource['mapprove2']);
            $max = intval($resultResource['mversion']) + 1;
            //echo!$this->backupmonthGoal($year, $user, $div, $max) ? "first backup fail" : "";
        }
        //echo ($approve1) .' xx '.($approve2). ' xx '.($max);
        //print_r($form);return;
        //update or insert data
        $sqlBackup = "";
        $transaction = Yii::app()->db->beginTransaction();
        try
        {
            foreach ($form AS $row)
            {
                //check old record
                $accid = intval($row['accid']);
                $value = floatval($row['value']);
                $month = intval($row['month']);
                $sqlInsert = "INSERT INTO tb_month_goal (acc_id, `value`, month_id, `year`, user_id, division_id, `version`, approve1_lv, approve2_lv) "
                        . "VALUES ('$accid', $value, $month, $year, $user, $div, $max, $approve1, $approve2) "
                        . "ON DUPLICATE KEY UPDATE `value` = $value, version = $max; ";
                $resultInsert = Yii::app()->db->createCommand($sqlInsert)->execute();
            }
            $sqlBackup = "UPDATE tb_month_goal SET version = $max WHERE `year` = $year AND user_id = $user AND division_id = $div;"//update version
                    . "INSERT INTO tb_version (month_goal_id, `value`, version) "
                    . "SELECT month_goal_id, `value`, version FROM tb_month_goal "
                    . "WHERE `year` = $year AND `user_id` = $user AND `division_id` = $div AND `version` = $max"; //เติม รหัสผู้ใช้แล้วก็ฝ่าย
            $resultBackup = Yii::app()->db->createCommand($sqlBackup)->execute();
            $transaction->commit();
            echo 'OK';
            return true;
        } catch (Exception $e)
        {
            $transaction->rollback();
            echo 'FAIL';
        }
    }

    private function backupMonthGoal($year, $user, $div, $ver)
    {
        $sqlBackup = "UPDATE tb_month_goal SET version = $ver WHERE `year` = $year AND user_id = $user AND division_id = $div;"//update version
                . "INSERT INTO tb_version (month_goal_id, `value`, version) "
                . "SELECT month_goal_id, `value`, version FROM tb_month_goal "
                . "WHERE `year` = $year AND `user_id` = $user AND `division_id` = $div AND `version` = $ver"; //เติม รหัสผู้ใช้แล้วก็ฝ่าย
        $resultBackup = Yii::app()->db->createCommand($sqlBackup)->execute();
        return $resultBackup;
    }
    
    public function actionAccVersion(){
        if(!(isset($_POST['year']) && isset($_POST['ver']) && isset($_POST['div'])))
        {
            echo 'invalid parameter';
            return false;
        }
        //cast
        $year = $_POST['year']-543;
        $ver = $_POST['ver'];
        $div = $_POST['div'];
        $user = Yii::app()->user->UserId;
        
        $result = Yii::app()->db->createCommand()->select("acc_id, tb_version.value")
                ->from("tb_version")->join("tb_month_goal", "tb_version.month_goal_id = tb_month_goal.month_goal_id")
                ->where("acc_id in (SELECT acc_id FROM tb_acc_year WHERE tb_acc_year.year = $year) AND user_id = $user AND division_id = $div")
                ->queryAll();
        echo json_encode($result);
    }
    
    //approve
    public function actionFillApprove(){//admin
        $user = Yii::app()->user->UserId;
        $userdiv = Yii::app()->user->UserDiv;
        $sql = "SELECT * FROM tb_division d INNER JOIN (SELECT division_id as par_id, division_name as par_name FROM tb_division) dd "
                    . "ON d.parent_division = dd.division_id "
                    . "WHERE par_id = $userdiv";
        $result2 = Yii::app()->db->createCommand()
                ->select("*")->from("tb_division")
                ->where("parent_division = $userdiv")
                ->queryAll();
        $result = Yii::app()->db->createCommand()
                ->selectDistinct("mg.division_id, d.division_name, par.division_id AS parid, par.division_name AS parname")
                ->from("tb_division d")
                ->join("tb_month_goal mg", "d.division_id = mg.division_id")
                ->join("tb_division AS par", "d.parent_division = par.division_id")
                ->where("d.parent_division = $userdiv ")
                ->queryAll();
                //->queryAll();
        //$result = Yii::app()->db->createCommand($sql)->queryAll();
        //echo $sql.'<br/>';
        print_r($result);
        echo !empty($result)?"NOT EMPTY":"EMPTY";
    }
    
    public function actionFillApproveDiv(){
        $userdiv = Yii::app()->user->UserDiv;
        /*$dep = Yii::app()->db->createCommand()
                                ->select("dc.division_id as cid, dc.division_name as cname, dp.division_id as pid, dp.division_name as pname, "
                                        . "mg.approve1_lv as state1, mg.approve2_lv as state2")
                                ->from("tb_division dc")
                                ->join("tb_division dp", "dc.parent_division = dp.division_id")//for parent
                                ->leftJoin("tb_month_goal mg", "mg.division_id = dc.division_id")//ใช้สำหรับ ดูว่ามีการกรอกข้อมูลหรือยัง
                                ->where("dp.division_id = $userdiv AND dc.enable = 1 AND dc.division_level < 3")
                                ->group("dc.division_id")->order("dc.erp_id ASC")->queryAll();*/
        $resource = Yii::app()->Resource->getYearResource();
        if(empty($resource)){
            $this->renderPartial("Bud/error",array('error'=>'ขณะนี้ยังไม่เปิดให้กรอกข้อมูล <a href="#" onclick="window.history.back();">ย้อนกลับ</a>'));
            return false;
        }
        $year = $resource['year'];unset($resource);
        $sql = "SELECT ay.`year`, dc.division_id as cid, dc.division_name as cname, approve_lv as approve, MAX(month_goal_id) as monthgoal, erp_id 
FROM tb_division dc  
JOIN tb_approve ap ON ap.division_id = dc.division_id 
JOIN tb_acc_year ay ON ay.`year` = ap.`year` 
LEFT JOIN tb_month_goal mg ON mg.division_id = dc.division_id AND mg.acc_id = ay.acc_id 
WHERE ay.`year` = $year AND dc.parent_division = $userdiv 
GROUP BY dc.division_id 
ORDER BY erp_id ASC;";
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        if(empty($result)){
            $this->renderPartial("Bud/error",array('error'=>'ฝ่ายนี้ยังไม่ได้มีการกำหนดข้อมูลสำหรับการกรอกข้อมูล กรุณาติดต่อ Admin <a href="#" onclick="window.history.back();">ย้อนกลับ</a>'));
            return;
        }else{
            ?>
                <table class='table table-bordered'>
                    <thead>
                        <tr>
                            <th>ชื่อตำแหน่งพิเศษ/แผนก/กอง</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $rowdep) {
                            //approve ได้ก็ต่อเมื่อ
                            $approve = $rowdep['approve'];
                            $mg = $rowdep['monthgoal'];
                            $w_error = $mg==NULL?"ยังไม่มีการกรอกข้อมูล":($approve==5?"ยังไม่มีการแก้ไขข้อมูลจากรอบก่อนการประชุม":"");
                            
                            /*$check = $rowdep['state1']==0||($rowdep['state1']==3&&$rowdep['state2']==0)?intval(0):intval(1);
                            $isnull = $rowdep['state1']==NULL?"disabled":"";
                            $disable = $rowdep['state1']>1||($rowdep['state1']==3&&$rowdep['state2']>1)||$rowdep['state1']==NULL?'disabled':'';*/
                            ?>
                        <tr>
                            <td style='width:75%; vertical-align: middle'><?=$rowdep['cname']?>&nbsp;&nbsp;&nbsp;<span class="text-danger"><?=$w_error?></span></td>
                            <td style='width:25%;'>
                                <div class='btn-group btn-group-sm' style='width:100%'>
                                <?php if($mg!=NULL): ?>
                                <input type='button' class='btn btn-warning view' value='เรียกดู' cid='<?=$rowdep['cid']?>' style="width:50%"/>
                                <?php else:?>
                                <span class="text-danger">ยังไม่มีการกรอกข้อมูล</span>
                                <?php endif;?>
                                <?php if(($approve == 1 && $mg != NULL) || ($approve == 5) ) {?>    
                                <input type='button' class='btn btn-primary confirm' value='ยืนยัน' cid='<?=$rowdep['cid']?>' style="width:50%"/>
                                <?php } else if((($approve == 2) || ($approve == 6))){?>
                                <input type='button' class='btn btn-danger unconfirm' value='ยกเลิกการยืนยัน' cid='<?=$rowdep['cid']?>' style="width:50%"/>
                                <?php }?>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php
        }
    }
    public function actionFillApproveDivConfirm(){
        if(isset($_POST['divid'])&&isset($_POST['round'])&&isset($_POST['year'])){
            $divtar = $_POST['divid'];
            $round = $_POST['round'];
            $year = $_POST['year'];
            $value = 2;
            if($round == 2){
                $value = 7;
            }
            
            $sqlupdate2 = "UPDATE tb_approve ap INNER JOIN tb_division d ON ap.division_id = d.division_id SET approve_lv = $value WHERE d.division_id = $divtar AND `year` = $year";
            $result = Yii::app()->db->createCommand($sqlupdate2)->execute();
            if(count($result)>0)
                echo 'ok';
            else echo ($result);
        }else{
            echo 'parameter error';
            print_r($_POST);
        }
    }
    public function actionFillApproveDivUnconfirm(){
        if(isset($_POST['divid'])&&isset($_POST['round'])&&isset($_POST['year'])){
            $divtar = $_POST['divid'];
            $round = $_POST['round'];
            $year = $_POST['year'];
            $value = 1;
            if($round == 2){
                $value = 7;
            }

            $sqlupdate2 = "UPDATE tb_approve ap INNER JOIN tb_division d ON ap.division_id = d.division_id SET approve_lv = $value WHERE d.division_id = $divtar AND `year` = $year";
            $result = Yii::app()->db->createCommand($sqlupdate2)->execute();
            if(count($result)>0)
                echo 'ok';
            else echo ($result);
        }else{
            echo 'parameter error';
            print_r($_POST);
        }
    }
    //admin
    public function actionFillApproveAdmin(){
        if(!isset($_POST['year'])){
            echo "parameter fault";
            return;
        }
        $year = $_POST['year'];
        $yearcheck = Yii::app()->db->createCommand("SELECT COUNT(acc_id) FROM tb_acc_year WHERE `year` = $year")->queryScalar();
        if($yearcheck == 0){
            echo "กรุณากำหนดบัญชีที่ใช้ในแต่ละปีก่อน";
            return;
        }
        
        $divsql = "SELECT  `year`, dp.division_id as pid, dp.division_name as pname, AVG(IFNULL(approve_lv,0)) as approve, dp.erp_id
	FROM tb_division dp  
	INNER JOIN tb_division dc ON dp.division_id = dc.parent_division AND dc.division_level < 3 
	INNER JOIN tb_approve ap ON dc.division_id = ap.division_id
	WHERE `year` = $year  
	GROUP BY dp.division_id 
        ORDER BY erp_id ";
        $div = Yii::app()->db->createCommand($divsql)->queryAll();
        if(empty($div)){
            echo 'ยังไม่ได้กำหนดข้อมูล รายละเอียดสังกัด <a href="#" onclick="window.history.back();">ย้อนกลับ</a>';
            return;
        }else{ 
            ?>
                <table class='table table-bordered'>
                    <thead>
                        <tr>
                            <th>ชื่อฝ่าย</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($div as $rowdiv) {
                            $check = $rowdiv['approve']==3 || $rowdiv['approve']==8?intval(0):intval(1);
                            $disable = $rowdiv['approve']<2 || ($rowdiv['approve']>3 && $rowdiv['approve']<7)?"disabled":"";
                            ?>
                        <tr>
                            <td style='width:75%; vertical-align: middle;' ><?=$rowdiv['pname']?>&nbsp;&nbsp;&nbsp;<span class="text-danger"><?=$disable=='disabled'?'ฝ่ายยังไม่ได้ยืนยัน':''?></span></td>
                            <td style='width:25%;'>
                                <div class='btn-group' style='display:inline;'>
                                <input <?=$disable?> type='button' class='btn btn-warning view ' style="width:40%" value='เรียกดู' cid='<?=$rowdiv['pid']?>'/>
                                <?php if($check == 1) {?>    
                                <input <?=$disable?> type='button' class='btn btn-primary confirm ' style="width:60%" value='ยืนยัน' cid='<?=$rowdiv['pid']?>'/>
                                <?php } else {?>
                                <input <?=$disable?> type='button' class='btn btn-danger unconfirm ' style="width:60%" value='ยกเลิกการยืนยัน' cid='<?=$rowdiv['pid']?>'/>
                                <?php }?>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
               
         <?php $yearresource = Yii::app()->Resource->getResourceOfYear($year);
            if($yearresource != NULL && $yearresource['approve'] == 8):
         ?>
        <div id='mfinalapprove' class="modal fade" role="dialog">
            <div class="modal-dialog modal-sm" style="width:330px">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">การยืนยันการสิ้นสุดการแก้ไขข้อมูล</h4>
                    </div>
                    <div class="modal-body" >
                        <form id='finalapprove' class='form-horizontal'>
                            <div class='form-group' style="text-align: center">
                                <label><input type="checkbox" id="chkfinal" /> ยืนยันการสิ้นสุดการแก้ไขงบประมาณ</label>
                            </div>
                            <div class="form-group">
                                <a class="btn btn-primary btn-block" id="btnfinal" disabled>ยืนยัน</a>
                            </div>
                            <div class="form-group">
                                <span class="text-danger">การยืนยันครั้งนี้หากยืนยันไปแล้ว จะไม่สามารถยกเลิกหรือเปลี่ยนแปลงได้อีกในภายหลัง</span>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer"  style="margin-top: -20px; padding: 5px">
                        <button type="button" class="btn btn-default btn-block" data-dismiss="modal">ปิด</button>
                    </div>
                </div>
        </div>
            <?php endif; 
        }
    }
    public function actionApproveAdminFinal(){
        if(isset($_POST['year'])){
            $year = $_POST['year'];
            $resource = Yii::app()->Resource->getYearResource();
            if($resource){
                if($year == $resource['year']){
                    //ok
                    $value = 9;
                    $transaction = Yii::app()->db->beginTransaction();
                    try{
                        $result = TbApprove::model()->updateAll(array('approve_lv'=>$value), "year = $year");
                        $transaction->commit();
                        echo 'ok';
                    }catch(Exception $ex){
                        $transaction->rollback();
                        echo 'fail';
                        echo $ex->getCode();
                    }
                }else{
                    echo 'parameter fault';
                }
            }
        }else{
            echo 'parameter error';
        }
    }
    public function actionApproveAdminConfirm(){
        if(isset($_POST['divid'])&&isset($_POST['round'])&&isset($_POST['year'])){
            $divtar = $_POST['divid'];
            $round = $_POST['round'];
            $year = $_POST['year'];
            $value = 3;
            if($round == 2){
                $value = 8;
            }
            
            $sqlupdate2 = "UPDATE tb_approve ap INNER JOIN tb_division d ON ap.division_id = d.division_id SET approve_lv = $value WHERE d.parent_division = $divtar AND `year` = $year";
            $result = Yii::app()->db->createCommand($sqlupdate2)->execute();
            if(count($result)>0)
                echo 'ok';
            else echo ($result);
        }else{
            echo 'parameter error';
            print_r($_POST);
        }
    }
    public function actionApproveAdminUnconfirm(){
        if(isset($_POST['divid'])&&isset($_POST['round'])&&isset($_POST['year'])){
            $divtar = $_POST['divid'];
            $round = $_POST['round'];
            $year = $_POST['year'];
            $value = 2;
            if($round == 2 || $round == 3){
                $value = 7;
            }
            
            $sqlupdate2 = "UPDATE tb_approve ap INNER JOIN tb_division d ON ap.division_id = d.division_id SET approve_lv = $value WHERE d.parent_division = $divtar AND `year` = $year";
            $result = Yii::app()->db->createCommand($sqlupdate2)->execute();
            if(count($result)>0)
                echo 'ok';
            else echo ($result);
        }else{
            echo 'parameter error';
            print_r($_POST);
        }
    }
    
    //YearGoal
    public function actionFillYearGoalAdmin(){
        if(!isset($_POST['year'])){
            echo 'parametet fault';
            return;
        }
        $f = Yii::app()->Format;
        $year = $_POST['year'];
        $div = Yii::app()->db->createCommand()
                ->select("dp.division_id as pid, dp.division_name as pname, ygl.income, ygl.expend")
                ->from("tb_division dp")
                //กำหนดกรอบรายได้-รายจ่ายรวมด้วย mg limit ณ ปีนั้น ๆ
                ->leftJoin("tb_yg_limit ygl", "dp.division_id = ygl.division AND ygl.year = $year")
                ->where("dp.division_level = 3")
                ->queryAll();
        ?>
             <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="vertical-align:middle;text-align: center" rowspan="2">ชื่อฝ่าย</th>
                            <th style="text-align: center" colspan="2">กรอบงบประมาณรายได้ - รายจ่ายรวม</th>
                            <th style="vertical-align:middle;text-align: center" rowspan="2">จัดการ</th>
                        </tr>
                        <tr><th style="text-align: center">รายได้รวม(บาท)</th><th style="text-align: center">รายจ่ายรวม(บาท)</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($div as $rd){?>
                        <tr>
                            <td style="vertical-align:middle"><?=$rd['pname']?></td>
                            <td style="vertical-align:middle" class="income">
                                <?php if($rd['income']==NULL){
                                    ?><div class='text-danger sum'>ยังไม่ได้กำหนด</div><?php
                                }else{
                                    ?><div class="sum" ><?=$rd['income']?></div><?php
                                }
                                ?>
                                    <div class="tassign" style="display:none;">
                                        <input value="<?=  $f->FixNumDown($rd['income'])?>" type="text" class="incomet" old="<?=$rd['income']?>" style="text-align: right;"/>
                                        <br/><span class="text-danger"></span>
                                    </div>
                            </td>
                            <td style="vertical-align:middle" class="expend">
                                <?php if($rd['expend']==NULL){
                                    ?><div class='text-danger sum'>ยังไม่ได้กำหนด</div><?php
                                }else{
                                    ?><div class="sum" ><?=$f->FixNumDown($rd['expend'])?></div><?php
                                }
                                ?>
                                    <div class="tassign" style="display:none;">
                                        <input value="<?=$rd['expend']?>" type="text" class="expendt" old="<?=$rd['expend']?>" style="text-align: right;"/>
                                        <br/><span class="text-danger"></span>
                                    </div>
                            </td>
                            <td style="vertical-align:middle; width:200px">
                                <div class="btn-group " style="width:100%" did="<?=$rd['pid']?>">
                                    <?php if($rd['income']==NULL || $rd['expend']==NULL) { ?>
                                    <input type="button" class="btn btn-primary assign" style="width:100%" value="กำหนด"/>
                                    <?php }else{ ?>
                                    <input type="button" class="btn btn-warning assign" style="width:100%" value="แก้ไข"/>
                                    <?php } ?>
                                    <input type="button" class="btn btn-success save" style="width:50%; display:none" value="บันทึก"/>
                                    <input type="button" class="btn btn-default cancel" style="width:50%; display:none" value="กลับ"/>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>      
                        
                    </tbody>
             </table>
        <?php
    }

    public function actionYearGoalSave() {
        if (!(isset($_POST['year']) && isset($_POST['did']) && isset($_POST['income']) && isset($_POST['expend']))) {
            echo 'missing parameter';
            return;
        }

        $year = $_POST['year'];
        $divid = $_POST['did'];
        $income = $_POST['income'];
        $expend = $_POST['expend'];
        $isdelete = FALSE;
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $sql = "INSERT INTO tb_yg_limit (`year`, division, income, expend) "
                    . "VALUES ($year, $divid, $income, $expend) "
                    . "ON DUPLICATE KEY UPDATE income = $income, expend = $expend";

            if (intval($income) == 0 && intval($expend) == 0) {
                $sql = "DELETE FROM tb_yg_limit WHERE `year` = $year AND division = $divid";
                $isdelete = TRUE;
            }
            $result = Yii::app()->db->createCommand($sql)->execute();
            $value = 4;
            if ($isdelete) $value = 3;
            //update approve
            $sqlupdate2 = "UPDATE tb_approve ap INNER JOIN tb_division d ON ap.division_id = d.division_id SET approve_lv = $value WHERE d.parent_division = $divid AND `year` = $year";
            $result_approve = Yii::app()->db->createCommand($sqlupdate2)->execute();
            $transaction->commit();
            echo 1;
        } catch (Exception $ex) {
            $transaction->rollback();
            echo "ผิดพลาดขณะอัพเดตข้อมูล";
            print_r($ex);
        }
    }

    public function actionFillYearGoalDiv(){
        if(!(isset($_POST['year'])&&isset($_POST['round']))){
            echo 'parametet fault';
            return;
        }
        $year = $_POST['year'];
        $round = $_POST['round'];
        $userdiv = Yii::app()->user->UserDiv;
        $depsql = "SELECT dc.division_id as cid, dc.division_name as cname, income, expend
                    FROM tb_division dc
                    LEFT JOIN (SELECT dc.division_id as cid, SUM((mgl.year_target)) as income 
                        FROM `tb_division` `dc` 
                        JOIN `tb_mg_limit` `mgl` ON mgl.division = dc.division_id AND mgl.`year` = $year AND round = $round 
                        JOIN `tb_account` `ac` ON ac.acc_id = mgl.acc_id AND group_id = 1 
                        JOIN `tb_acc_year` `ay` ON mgl.acc_id = ay.acc_id AND ay.`year` = $year 
                        WHERE dc.division_level < 3 AND dc.parent_division = $userdiv 
                        GROUP BY dc.division_id) tbi ON tbi.cid = dc.division_id
                    LEFT JOIN (SELECT dc.division_id as cid, dc.division_name as cname, SUM((mgl.year_target)) as expend 
                        FROM `tb_division` `dc` 
                        JOIN `tb_mg_limit` `mgl` ON mgl.division = dc.division_id AND mgl.`year` = $year AND round = $round 
                        JOIN `tb_account` `ac` ON ac.acc_id = mgl.acc_id AND group_id > 1
                        JOIN `tb_acc_year` `ay` ON mgl.acc_id = ay.acc_id AND ay.`year` = $year 
                        WHERE dc.division_level < 3 AND dc.parent_division = $userdiv 
                        GROUP BY dc.division_id) tbe ON tbe.cid = dc.division_id
                    WHERE dc.division_level < 3 and dc.enable = 1 and dc.parent_division = $userdiv";//กำหนดไปแล้ว
        //echo $depsql.'<br/>';
        $dep = Yii::app()->db->createCommand($depsql)->queryAll();
        $divlimit = Yii::app()->db->createCommand()->select("division, IFNULL(income,0.00) as income, IFNULL(expend,0.00) as expend")->from("tb_yg_limit")
                ->where("division = $userdiv AND `year` = $year")->queryRow();
        $deplimitsql = "SELECT SUM(income) as income, SUM(expend) as expend
                    FROM tb_division dc
                    LEFT JOIN (SELECT dc.division_id as cid, SUM((mgl.year_target)) as income 
                        FROM `tb_division` `dc` 
                        JOIN `tb_mg_limit` `mgl` ON mgl.division = dc.division_id AND mgl.`year` = $year AND round = $round 
                        JOIN `tb_account` `ac` ON ac.acc_id = mgl.acc_id AND group_id = 1 
                        JOIN `tb_acc_year` `ay` ON mgl.acc_id = ay.acc_id AND ay.`year` = $year 
                        WHERE dc.division_level < 3 AND dc.parent_division = $userdiv 
                        GROUP BY dc.division_id) tbi ON tbi.cid = dc.division_id
                    LEFT JOIN (SELECT dc.division_id as cid, dc.division_name as cname, SUM((mgl.year_target)) as expend 
                        FROM `tb_division` `dc` 
                        JOIN `tb_mg_limit` `mgl` ON mgl.division = dc.division_id AND mgl.`year` = $year AND round = $round 
                        JOIN `tb_account` `ac` ON ac.acc_id = mgl.acc_id AND group_id > 1
                        JOIN `tb_acc_year` `ay` ON mgl.acc_id = ay.acc_id AND ay.`year` = $year 
                        WHERE dc.division_level < 3 AND dc.parent_division = $userdiv 
                        GROUP BY dc.division_id) tbe ON tbe.cid = dc.division_id
                    WHERE dc.division_level < 3 and dc.enable = 1 and dc.parent_division = $userdiv";
        //echo $deplimitsql;return;
        $deplimit = Yii::app()->db->createCommand($deplimitsql)->queryRow();
//        print_r($dep);
//        echo '<hr/>';
//        print_r($divlimit);
//        echo '<hr/>';
//        print_r($deplimit);
//        echo '<hr/>';
//        return;
        ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="vertical-align:middle;text-align: center" rowspan="3">ชื่อฝ่าย</th>
                            <th style="text-align: center" colspan="2">กรอบงบประมาณรายได้ - รายจ่ายรวม</th>
                            <th style="vertical-align:middle;text-align: center" rowspan="3">จัดการ</th>
                        </tr>
                        <tr><th style="text-align: left">รายได้รวม <?=Yii::app()->Format->FixNumDown($divlimit['income'])?> บาท</th><th style="text-align: left">รายจ่ายรวม <?=Yii::app()->Format->FixNumDown($divlimit['expend'])?> บาท</th></tr>
                        <tr><th style="text-align: left">กำหนดแล้ว <?=Yii::app()->Format->FixNumDown($deplimit['income'])?> บาท</th><th style="text-align: left">กำหนดแล้ว <?=Yii::app()->Format->FixNumDown($deplimit['expend'])?> บาท</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($dep as $rd){?>
                        <tr>
                            <td style="vertical-align:middle"><?=$rd['cname']?></td>
                            <td style="vertical-align:middle" class="income">
                                <?php if($rd['income']==NULL){
                                    ?><div class='text-danger sum'>ยังไม่ได้กำหนดรายรหัส</div><?php
                                }else{
                                    ?><div class="sum" ><?=Yii::app()->Format->FixNumDown($rd['income'])?></div><?php
                                }
                                ?>
                            </td>
                            <td style="vertical-align:middle" class="expend">
                                <?php if($rd['expend']==NULL){
                                    ?><div class='text-danger sum'>ยังไม่ได้กำหนดรายรหัส</div><?php
                                }else{
                                    ?><div class="sum" ><?=Yii::app()->Format->FixNumDown($rd['expend'])?></div><?php
                                }
                                ?>
                            </td>
                            <td style="vertical-align:middle; width:200px">
                                <div class="btn-group " style="width:100%" cid="<?=$rd['cid']?>">
                                    <?php if($rd['income']==0 && $rd['expend']==0) { ?>
                                    <input type="button" class="btn btn-primary assign" style="width:100%" value="กำหนด"/>
                                    <?php }else{ ?>
                                    <input type="button" class="btn btn-warning edit" style="width:50%" value="แก้ไข"/>
                                    <input type="button" class="btn btn-default cancel" style="width:50%;" value="ยกเลิก"/>
                                    <?php } ?>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>      
                        
                    </tbody>
             </table>
        <?php
    }
    public function actionFillYgInput(){
        if (!isset($_POST["year"]))
        {
            echo "error variable missing !";
            return FALSE;
        }
        $year = $_POST['year'];
        $accinyear = Yii::app()->db->createCommand()->select("acc_id")->from("tb_acc_year")->where("year = $year")->queryAll();
        if (empty($accinyear))
        {
            echo 'invalid parameter';
            return FALSE;
        }
        $in = "AND acc_id IN (";
        foreach ($accinyear AS $acc)
        {
            $in .= $acc['acc_id'] . ', ';
        }
        $in = substr($in, 0, -2);
        $in .= ")";
        ?><div class="swMain2"><?php
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
                    <h2 class="StepTitle">บัญชีในประเภท<?= $g->group_name ?></h2>
                    <ul class="checkbox-tree">
                        <li><label><input type="checkbox" name="selall"/>เลือกทั้งหมด</label>
                            <?php
                            $resultlv1 = TbAccount::model()->findAll("parent_acc_id IS NULL AND group_id = $g->group_id $in");
                            if (count($resultlv1))
                            {
                                ?><ul><?php
                                        foreach ($resultlv1 as $lv1)//level 1
                                        {
                                            ?><li><?php
                                            ?><label><input type="checkbox" name="<?= $lv1->acc_id ?>" /><?= $lv1->acc_name ?></label><?php
                                            if (!$this->hasChild($lv1->acc_id)){ 
                                                                                                ?>:&nbsp;
                                                                                                <div style="display:none" class="txtacc">
                                                                                                    <input type="text" name="acc-<?= $lv1->acc_id ?>" >
                                                                                                    <span class="text-danger err"></span>
                                                                                                </div>
                                                                                                <?php }else{ ?>
                                                                                                &nbsp;<a href="#" class="sh" tabindex="-1"><i class="glyphicon glyphicon-minus"></i></a>
                                                                                                <?php }
                                            $resultlv2 = TbAccount::model()->findAll(array('condition' => "parent_acc_id = $lv1->acc_id $in", 'order' => "acc_name ASC"));
                                            if (count($resultlv2))
                                            {
                                                ?><ul><?php
                                                        foreach ($resultlv2 as $lv2)//level 2
                                                        {
                                                            ?><li><?php
                                                            ?><label><input type="checkbox" name="<?= $lv2->acc_id ?>"  ><?= $lv2->acc_name ?></label><?php
                                                            if (!$this->hasChild($lv2->acc_id)){ 
                                                                                                ?>:&nbsp;
                                                                                                <div style="display:none"  class="txtacc">
                                                                                                    <input type="text" name="acc-<?= $lv2->acc_id ?>" g="<?=$lv2->group_id?>" >
                                                                                                    <span class="text-danger err"></span>
                                                                                                </div>
                                                                                                <?php }else{ ?>
                                                                                                &nbsp;<a href="#" class="sh" tabindex="-1"><i class="glyphicon glyphicon-minus"></i></a>
                                                                                                <?php }
                                                            $resultlv3 = TbAccount::model()->findAll(array('condition' => "parent_acc_id = $lv2->acc_id $in", 'order' => "acc_name ASC"));
                                                            if (count($resultlv3))
                                                            {
                                                                ?><ul><?php
                                                                        foreach ($resultlv3 as $lv3)//level 3
                                                                        {
                                                                            ?><li><?php
                                                                            ?><label><input type="checkbox" name="<?= $lv3->acc_id ?>" /><?= $lv3->acc_name ?></label><?php
                                                                            if (!$this->hasChild($lv3->acc_id)){ 
                                                                                                ?>:&nbsp;
                                                                                                <div style="display:none" class="txtacc">
                                                                                                    <input type="text" name="acc-<?= $lv3->acc_id ?>" g="<?=$lv3->group_id?>"  />
                                                                                                    <span class="text-danger err"></span>
                                                                                                </div>
                                                                                                <?php }else{ ?>
                                                                                                &nbsp;<a href="#" class="sh" tabindex="-1"><i class="glyphicon glyphicon-minus"></i></a>
                                                                                                <?php }
                                                                            $resultlv4 = TbAccount::model()->findAll(array('condition' => "parent_acc_id = $lv3->acc_id $in", 'order' => "acc_name ASC"));
                                                                            if (count($resultlv4))
                                                                            {
                                                                                ?><ul><?php
                                                                                        foreach ($resultlv4 as $lv4)
                                                                                        {
                                                                                            ?><li><?php
                                                                                            ?><label><input type="checkbox" name="<?= $lv4->acc_id ?>" /><?= $lv4->acc_name  ?></label><?php
                                                                                            if (!$this->hasChild($lv4->acc_id)){ 
                                                                                                ?>:&nbsp;
                                                                                                <div style="display:none" class="txtacc">
                                                                                                    <input type="text" name="acc-<?= $lv4->acc_id ?>" g="<?=$lv4->group_id?>"  >
                                                                                                    <span class="text-danger err"></span>
                                                                                                </div>
                                                                                                <?php }else{ ?>
                                                                                                &nbsp;<a href="#" class="sh" tabindex="-1"><i class="glyphicon glyphicon-minus"></i></a>
                                                                                                <?php }
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
    public function actionFillYgCan(){
        //create structure
        $data = array();
        $data['error'] = 0;
        $data['msg'] = "none";
        $data['canincome'] = "";
        $data['canexpend'] = "";
        //variable checking
        if(!(isset($_POST['year']))){
            $data['error'] = 1;
            $data['msg'] = "ERROR: Missing parameter";
            echo json_encode($data);
            return false;
        }
        //dump variable
        $year = $_POST['year'];
        //find parent of div from userdiv
        $userdiv = Yii::app()->user->UserDiv;
        
        $resource = TbYgLimit::model()->findByPk(array('year'=>$year, 'division'=>$userdiv));
        if(!empty($resource)){
            $data['error']=0;
            $data['msg']="none";
            $data['canincome'] = $resource->income;
            $data['canexpend'] = $resource->expend;
        }else{
            $data['error']=2;
            $data['msg']="ERROR: Parameter fault";
            $data['canincome'] = intval(0);
            $data['canexpend'] = intval(0);
        }
        echo json_encode($data);
    }
    public function actionYgSave(){
        if(!(isset($_POST['year'])&&isset($_POST['round'])&&isset($_POST['cid'])&&isset($_POST['detail'])&&isset($_POST['method']))){
            echo 'Error: Missing parameter';
            return false;
        }
        //dump var
        $year = $_POST['year'];
        $round = $_POST['round'];
        $cid = $_POST['cid'];
        $data = $_POST['detail'];
        $method = $_POST['method'];
        $newapprove = $round == 1?intval(1):intval(5);
        
        if($method == "assign"){
            $resultcheck = TbMgLimit::model()->findAll("year = $year AND division = $cid AND round = $round");
            if(count($resultcheck)){
                echo "Error: แผนก/กอง นี้ได้รับการกำหนดกรอบไปแล้ว กรุณาใช้ตัวเลือก แก้ไข แทน กำหนด";
            }else{
                $transaction = Yii::app()->db->beginTransaction();
                try{
                    foreach($data as $d){
                        $modelLimit = new TbMgLimit();
                        if($modelLimit->isNewRecord){
                            $modelLimit->year = $year;
                            $modelLimit->round = intval($round);
                            $modelLimit->division = intval($cid);
                            $modelLimit->acc_id = intval($d['accid']);
                            $modelLimit->year_target = floatval($d['value']);
                            $modelLimit->save();
                        }
                    }
                    $modelApprove = TbApprove::model()->find("year = $year AND division_id = $cid");
                        if(!$modelApprove->isNewRecord){
                            $modelApprove->year = $year;
                            $modelApprove->division_id = intval($cid);
                            $modelApprove->approve_lv = $newapprove;
                            $modelApprove->save();
                        }else{
                            throw new Exception("cannot find approve");
                        }
                    $transaction->commit();
                    echo '1';
                } catch (Exception $ex) {
                    $transaction->rollback();
                    echo "Error: Data inserting fault";
                    print_r($ex);
                }
            }
        }else if($method == "edit"){
            $transaction = Yii::app()->db->beginTransaction();
            try{
                //delete old data
                TbMgLimit::model()->deleteAll("year = $year AND round = $round AND division = $cid");
                //add agian
                foreach($data as $d){
                    $modelLimit = new TbMgLimit();
                    if($modelLimit->isNewRecord){
                        $modelLimit->year = $year;
                        $modelLimit->round = intval($round);
                        $modelLimit->division = intval($cid);
                        $modelLimit->acc_id = intval($d['accid']);
                        $modelLimit->year_target = floatval($d['value']);
                        $modelLimit->save();
                    }
                }
                $modelApprove = TbApprove::model()->findByPk(array("year"=>$year, "division_id"=>$cid));
                //$modelApprove = new TbApprove();
                        if(!$modelApprove->isNewRecord){
                            $modelApprove->year = $year;
                            $modelApprove->division_id = intval($cid);
                            $modelApprove->approve_lv = $newapprove;
                            $modelApprove->save();
                        }
                        
                $transaction->commit();
                echo '1';
            } catch (Exception $ex) {
                $transaction->rollback();
                echo "Error: Data editing fault";
                print_r($ex);
            }
            
        }else{
            echo "Error: Parameter fault";
            return false;
        }
    }
    public function actionYgInfo(){
        if(!(isset($_POST['year'])&&isset($_POST['round'])&&isset($_POST['cid']))){
            echo 'Error: Missing parameter';
            return false;
        }
        //var dump
        $year = $_POST['year'];
        $round = $_POST['round'];
        $cid = $_POST['cid'];
        
        $predata = array();
        $result = TbMgLimit::model()->findAll("year = $year AND round = $round AND division = $cid");
        if(!empty($result)){
            $i = intval(0);
            foreach($result as $row){
                $predata[$i]['accid'] = $row['acc_id'];
                $predata[$i++]['value'] = $row['year_target']; 
            }
            echo json_encode($predata);
        }else{
            echo 'error';
        }
    }
    public function actionYgDivDel(){
        if(!(isset($_POST['year'])&&isset($_POST['round'])&&isset($_POST['cid']))){
            echo 'Error: Missing parameter';
            return false;
        }
        //var dump
        $year = $_POST['year'];
        $round = $_POST['round'];
        $cid = $_POST['cid'];
        $transaction = Yii::app()->db->beginTransaction();
        try{
            TbMgLimit::model()->deleteAll("year = $year AND round = $round AND division = $cid");
            TbApprove::model()->deleteAll("Year = $year AND division_id = $cid");
            $transaction->commit();
            echo "การยกเลิกกรอบงบประมาณสำเร็จ";
        } catch (Exception $ex) {
            $transaction->rollback();
            echo "การยกเลิกกรอบงบประมาณล้มเหลว ";
            print_r($ex);
        }
    }
    public function actionFillMonthGoalDiv(){
        if(!(isset($_POST['year']) && isset($_POST['round']))){
            echo 'Error: Missing parameter';
            return false;
        }
        //dump
        $year = $_POST['year'];
        $round = $_POST['round'];
        //cus
        $userdiv = Yii::app()->user->UserDiv;

        //หาว่าผู้ใช้คนนี้ดูแลแผนก/กองไหนบ้าง
        /*$sql = "SELECT ay.`year`, dc.division_id as cid, dc.division_name as cname, approve_lv as approve \n"
                . "FROM tb_division dc \n"
                . "JOIN tb_profile_fill pf ON pf.division_id = dc.division_id AND pf.owner_div_id = 69 \n"
                . "LEFT JOIN tb_approve ap ON ap.division_id = dc.division_id \n"
                . "JOIN tb_acc_year ay ON ay.`year` = IFNULL(ap.`year`, 2015) \n"
                . "WHERE ay.`year` = 2015 \n"
                . "GROUP BY pf.division_id \n"
                . "ORDER BY cname ASC";*/
        $sql = "SELECT ay.`year`, dc.division_id as cid, dc.division_name as cname, approve_lv as approve, MAX(month_goal_id) as monthgoal, erp_id
FROM tb_division dc  
JOIN tb_profile_fill pf ON pf.division_id = dc.division_id AND pf.owner_div_id = $userdiv 
JOIN tb_approve ap ON ap.division_id = dc.division_id 
JOIN tb_acc_year ay ON ay.`year` = ap.`year` 
LEFT JOIN tb_month_goal mg ON mg.division_id = dc.division_id AND mg.acc_id = ay.acc_id 
WHERE ay.`year` = $year  
GROUP BY pf.division_id 
ORDER BY erp_id ASC;";
        //echo '<pre>'.$sql.'</pre>';
        $resource = Yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($resource)){
            ?>
        <table class="table table-bordered tbcenter" style="min-width: 300px; width: 800px">
            <thead>
                <tr>
                    <th class="thcenter">ชื่อแผนก/กองที่ต้องกรอก</th>
                    <th class="thcenter">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($resource as $row){
                    $cid = $row['cid'];
                    $cname = $row['cname'];
                    $approve = $row['approve'];
                    $mg = $row['monthgoal'];
                    ?>
                <tr>
                    <td class="tdcenter"><?php 
                    echo $cname;
                        if(($round == 1 && $approve == 0)||($round == 2 && $approve == 4)):?>
                        <span class="text-danger">ฝ่ายยังไม่ได้กำหนดเป้าหมายรายปี</span>
                        <?php endif; ?>
                    </td>
                    <td class="tdcenter" style="max-width: 200px;width: 25%">
                        <div class="btn-group" cid="<?=$cid?>" style="width: 100%">
                            <?php if(($round == 1 && $approve == 0)||($round == 2 && $approve == 4)): ?>
                            <span class="text-danger">ไม่มีเป้าหมายรายปี</span>
                            <?php elseif(($round == 1 && $approve == 1 && $mg == NULL)||($round == 2 && $approve == 5)):?>
                            <a class="btn btn-primary assign" href="<?=Yii::app()->createAbsoluteUrl("Bud/MonthGoal/assign/$cid")?>" style="width:100%">กำหนด</a>
                            <?php elseif(($round == 1 && $approve == 1 && $mg != NULL) || ($round == 2 && $approve == 6)):?>
                            <a class="btn btn-info view" style="width:50%">เรียกดู</a>
                            <a class="btn btn-warning assign" href="<?=Yii::app()->createAbsoluteUrl("Bud/MonthGoal/edit/$cid")?>" style="width:50%">แก้ไข</a>
                            <?php else: ?>
                            <span class="text-success">ได้รับการยืนยันแล้ว</span>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr><?php
                }?>
            </tbody>
        </table>


            <?php
        }
    }
    public function actionFillMonthGoalInput(){
        if (!(isset($_POST["year"])&&isset($_POST['round'])&&isset($_POST['cid'])))
        {
            echo "error variable missing !";
            return FALSE;
        }
        $year = $_POST['year'];
        $round = $_POST['round'];
        $cid = $_POST['cid'];
        
        $parentaccsql = "SELECT acc_id, year_target as `limit` \n"
                . "FROM tb_division dc \n"
                . "JOIN tb_division dp ON dp.division_id = dc.parent_division \n"
                . "JOIN tb_mg_limit mgl ON mgl.division = dc.division_id AND `year` = $year AND round = $round \n"
                . "WHERE dc.division_id = $cid ";
        $parentaccsql = "SELECT  IFNULL(ac1.acc_id,0) as ac1, IFNULL(ac2.acc_id,0) as ac2, IFNULL(ac3.acc_id,0) as ac3, IFNULL(ac4.acc_id,0) as ac4
                            FROM tb_division dc 
                            JOIN tb_division dp ON dp.division_id = dc.parent_division 
                            JOIN tb_mg_limit mgl ON mgl.division = dc.division_id AND `year` = $year AND round = $round
                            JOIN tb_account ac1 ON ac1.acc_id = mgl.acc_id
                            LEFT JOIN tb_account ac2 ON ac2.acc_id = ac1.parent_acc_id 
                            LEFT JOIN tb_account ac3 ON ac3.acc_id = ac2.parent_acc_id
                            LEFT JOIN tb_account ac4 ON ac4.acc_id = ac3.parent_acc_id
                            WHERE dc.division_id = $cid";
        
        //$accs = Yii::app()->db->createCommand()->select("acc_id")->from("tb_acc_year")->where("year = $year")->queryAll();
        $accs = Yii::app()->db->createCommand($parentaccsql)->queryAll();
        if (empty($accs))
        {
            echo 'invalid parameter';
            return FALSE;
        }
        $in = " acc_id IN (";
        foreach ($accs AS $acc)
        {
            //$in .= $acc['acc_id'] . ', ';
            $in .= $acc['ac1'] . ', ';
            $in .= $acc['ac2'] . ', ';
            $in .= $acc['ac3'] . ', ';
            $in .= $acc['ac4'] . ', ';
        }
        $in = substr($in, 0, -2);
        $in .= ")";
        
        ?>
        <div class="swMain2"><?php
            $month = TbMonth::model()->findAll(array('order' => "`quarter` ASC,`month_id` ASC"));
            $i = 1;
            ?><ul><!--Stepbar--><?php
                foreach ($month as $m)
                {
                    ?><li><a href="#step-<?= $i++ ?>">
                            <span class="stepDesc">
                                <?= $m->month_name ?><br />
                            </span>
                        </a>
                    </li><?php
                }
                ?></ul><?php
            $i = 1;
            //main
            foreach ($month as $m)//group
            {
                ?><div id="step-<?= $i++ ?>">
                    <div class="StepTitle">
                        <div style="float:left">กรอกงบประมาณสำหรับเดือน<?= $m->month_name ?></div>
                        <div style="text-align: right">
                            <label><input type="checkbox" class="chkother" m="<?=$m->month_id?>"/> นำข้อมูลมาจากเดือนอื่น </label>
                            <select class="fmonth" style="display:none">
                                <option selected value="0">เลือกเดือน</option>
                                <option value="10">ตุลาคม</option>
                                <option value="11">พฤศจิกายน</option>
                                <option value="12">ธันวาคม</option>
                                <option value="1">มกราคม</option>
                                <option value="2">กุมภาพันธ์</option>
                                <option value="3">มีนาคม</option>
                                <option value="4">เมษายน</option>
                                <option value="5">พฤษภาคม</option>
                                <option value="6">มิถุนายน</option>
                                <option value="7">กรกฎาคม</option>
                                <option value="8">สิงหาคม</option>
                                <option value="9">กันยายน</option>
                            </select>
                        </div>
                    </div>
                    <?php
                    $resultlv1 = TbAccount::model()->findAll("parent_acc_id IS NULL AND $in ORDER BY `acc_number1` ASC,`acc_number2` ASC,`acc_number3` ASC,`acc_number4` ASC ");
                    if (count($resultlv1))
                    {
                        ?><ul class="checkbox-tree"><?php
                            foreach ($resultlv1 as $lv1)//level 1
                            {
                                if($this->hasChild($lv1->acc_id)){
                                ?><li><?php
                                ?><label><?= $lv1->acc_name ?> </label>&nbsp;<a href="#" class="sh" tabindex="-1"><i class="glyphicon glyphicon-minus"></i></a>
                                    <?php 
                                    $resultlv2 = TbAccount::model()->findAll(array('condition' => "parent_acc_id = $lv1->acc_id AND $in", 'order' => "`acc_number1` ASC,`acc_number2` ASC,`acc_number3` ASC,`acc_number4` ASC "));
                                    if (count($resultlv2))
                                    {
                                        ?><ul><?php
                                                foreach ($resultlv2 as $lv2)//level 2
                                                {
                                                    if($this->hasChild($lv2->acc_id)){
                                                    ?><li><?php
                                                    ?><label><?= $lv2->acc_name ?> </label>&nbsp;<a href="#" class="sh" tabindex="-1"><i class="glyphicon glyphicon-minus"></i></a>
                                                    <?php
                                                    $resultlv3 = TbAccount::model()->findAll(array('condition' => "parent_acc_id = $lv2->acc_id AND $in", 'order' => "`acc_number1` ASC,`acc_number2` ASC,`acc_number3` ASC,`acc_number4` ASC "));
                                                    if (count($resultlv3))
                                                    {
                                                        ?><ul><?php
                                                                foreach ($resultlv3 as $lv3)//level 3
                                                                {
                                                                    if($this->hasChild($lv3->acc_id)){
                                                                    ?><li><?php
                                                                    ?><label><?= $lv3->acc_name ?> </label>&nbsp;<a href="#" class="sh" tabindex="-1"><i class="glyphicon glyphicon-minus"></i></a>
                                                                    <?php
                                                                    $resultlv4 = TbAccount::model()->findAll(array('condition' => "parent_acc_id = $lv3->acc_id AND $in", 'order' => "`acc_number1` ASC,`acc_number2` ASC,`acc_number3` ASC,`acc_number4` ASC "));
                                                                    if (count($resultlv4))
                                                                    {
                                                                        ?><ul><?php
                                                                        foreach ($resultlv4 as $lv4)
                                                                        {
                                                                            if($this->caninput($year, $round, $cid, $lv4->acc_id)){
                                                                            $limit = TbMgLimit::model()->findByPk(array("year"=>$year, "round"=>$round, "division"=>$cid, "acc_id"=>$lv4->acc_id))->year_target; ?>
                                                                            <li>
                                                                                <label><?= $lv4->acc_name ?> </label><br/><span class="limit" aid="<?=$lv4->acc_id?>"><เป้ารายปีที่กำหนดไว้>: <input type="text" readonly="readonly" tabindex="-1" value="<?=Yii::app()->Format->FixNumDown($limit)?>"></span></span>:&nbsp<span class="current" month="<?= $m->month_id ?>" aid="<?=$lv4->acc_id?>"><เป้ารายปีปัจจุบัน>: <input type="text" readonly="readonly" tabindex="-1" value="0"></span>
                                                                                <input type="text" name="acc-<?= $lv4->acc_id ?>" month="<?= $m->month_id ?>" /><span class="text-danger err"></span>
                                                                            </li>
                                                                            <?php }
                                                                        }
                                                                        ?></ul><?php
                                                                    }
                                                                    ?></li><?php
                                                                    }else if(!$this->hasChild($lv3->acc_id) && $this->caninput($year, $round, $cid, $lv3->acc_id)){?>
                                                                    <?php $limit = TbMgLimit::model()->findByPk(array("year"=>$year, "round"=>$round, "division"=>$cid, "acc_id"=>$lv3->acc_id))->year_target; ?>
                                                                    <li><label><?= $lv3->acc_name ?> </label><br/><span class="limit" aid="<?=$lv3->acc_id?>"><เป้ารายปีที่กำหนดไว้>: <input type="text" readonly="readonly" tabindex="-1" value="<?=Yii::app()->Format->FixNumDown($limit)?>"></span></span>:&nbsp<span class="current" month="<?= $m->month_id ?>" aid="<?=$lv3->acc_id?>"><เป้ารายปีปัจจุบัน>: <input type="text" readonly="readonly" value="0"></span>
                                                                        <input type="text" name="acc-<?= $lv3->acc_id ?>" month="<?= $m->month_id ?>" /><span class="text-danger err"></span></li>
                                                                    <?php }//endif;
                                                                }
                                                        ?></ul><?php
                                                    }
                                                    ?></li><?php
                                                    }else if(!$this->hasChild($lv2->acc_id) && $this->caninput($year, $round, $cid, $lv2->acc_id)){?>
                                                    <?php $limit = TbMgLimit::model()->findByPk(array("year"=>$year, "round"=>$round, "division"=>$cid, "acc_id"=>$lv2->acc_id))->year_target; ?>
                                                    <li><label><?= $lv2->acc_name ?> </label><br/><span class="limit" aid="<?=$lv2->acc_id?>"><เป้ารายปีที่กำหนดไว้>: <input type="text" readonly="readonly" tabindex="-1" value="<?=Yii::app()->Format->FixNumDown($limit)?>"></span></span>:&nbsp<span class="current" month="<?= $m->month_id ?>" aid="<?=$lv2->acc_id?>"><เป้ารายปีปัจจุบัน>: <input type="text" readonly="readonly" tabindex="-1" value="0"></span>
                                                        <input type="text" name="acc-<?= $lv2->acc_id ?>" month="<?= $m->month_id ?>" /><span class="text-danger err"></span></li>
                                                    <?php }//endif;
                                                }
                                                ?></ul><?php
                                        }
                                
                                ?></li>
                                <?php }else if(!$this->hasChild($lv1->acc_id) && $this->caninput($year, $round, $cid, $lv1->acc_id)){?>
                                <li><label><?= $lv1->acc_name ?> </label>:&nbsp;<input type="text" name="acc-<?= $lv1->acc_id ?>" month="<?= $m->month_id ?>" /><span class="text-danger err"></span></li>
                                <?php }//endif;
                            }
                        ?></ul><?php
                    }
                ?></div><?php
                }//foreach group end
        ?></div><?php
    }
    public function caninput($year, $round, $cid, $acc){

        $resource = TbMgLimit::model()->findByPk(array("year"=>$year, "round"=>$round, "division"=>$cid, "acc_id"=>$acc));
        if(!empty($resource)){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    public function actionMgSave(){
        if(!(isset($_POST['year'])&&isset($_POST['round'])&&isset($_POST['cid'])&&isset($_POST['detail'])&&isset($_POST['method']))){
            echo 'Error: Missing parameter';
            return false;
        }
        $year = $_POST['year'];
        $round = $_POST['round'];
        $cid = $_POST['cid'];
        $data = $_POST['detail'];
        $method = $_POST['method'];
        $userdiv = Yii::app()->user->UserDiv;
        $version = Yii::app()->Resource->getVersionOfDep($cid, $year);
        $approve = Yii::app()->Resource->getApproveOfDep($cid, $year);
        if($approve == NULL){
            echo 'Error: Parameter fault';
            return FALSE;
        }/*else{
            if(intval($approve) != intval(0)){
                $version = "version + 1";
            }
        }*/
        if($version == NULL) $version = 1;
        else $version += 1;
        if($round == 1)
            $approve = 1;
        else if($round == 2)
            $approve = 6;
        
        $transaction = Yii::app()->db->beginTransaction();
        try{
            //$result = false;
            foreach($data as $row){
                $mid = $row['month'];
                $val = $row['value'];
                $acc = $row['accid'];
                $sql = "INSERT INTO tb_month_goal (`year`, month_id, division_id, acc_id, user_id, `value`) "
                        . "VALUES($year, $mid, $cid, $acc, $userdiv, $val) "
                        . "ON DUPLICATE KEY UPDATE `value` = $val ";
                $result = Yii::app()->db->createCommand($sql)->execute();
            }
            $sqlupdate = "UPDATE tb_month_goal SET version = $version WHERE `year` = $year AND division_id = $cid ";
            $sqlversion = "INSERT INTO tb_version (month_goal_id, `value`, version) "
                    . "(SELECT month_goal_id, `value`, version FROM tb_month_goal "
                    . "WHERE `year` = $year AND `division_id` = $cid )";
            $sqlapprove = "UPDATE tb_approve SET approve_lv = $approve WHERE `year` = $year AND division_id = $cid";
            Yii::app()->db->createCommand($sqlupdate)->execute();
            Yii::app()->db->createCommand($sqlversion)->execute();
            Yii::app()->db->createCommand($sqlapprove)->execute();
            /*if($result)
                $transaction->commit();
            else
                $transaction->rollback();*/
            $transaction->commit();
            echo 1;
        } catch (Exception $ex) {
            $transaction->rollback();
            print_r($ex);
        }
    }
    public function actionMgInfo(){
        if(!(isset($_POST['year'])&&isset($_POST['cid']))){
            echo 'Error: Missing parameter';
            return false;
        }
        //var dump
        $year = $_POST['year'];
        $cid = $_POST['cid'];
        
        $predata = array();
        $result = TbMonthGoal::model()->findAll("year = $year AND division_id = $cid");
        if(!empty($result)){
            $i = intval(0);
            foreach($result as $row){
                $predata[$i]['accid'] = $row['acc_id'];
                $predata[$i]['month'] = $row["month_id"];
                $predata[$i++]['value'] = $row['value']; 
            }
            echo json_encode($predata);
        }else{
            echo 'error';
        }
    }
    public function actionMgFillVersionSelector(){
        @$cid = $_POST['div'];
        @$year = $_POST['year'];
        $versions = Yii::app()->Resource->getAllVersionOfDep($cid, $year);
        if (count($versions))
        {
            ?><option value="0">เลือกเวอร์ชั่น</option><?php
            foreach ($versions as $ver)
            {
                ?><option value="<?= $ver['version'] ?>"><?= $ver['version'] ?></option><?php
            }
        }
        else
        {
            ?><option value="0">--ไม่มีเวอร์ชั่นสำหรับปีนี้--</option><?php
        }
    }
    
    public function actionMgFillValueFromVersion(){
        if(!(isset($_POST['year'])&&isset($_POST['cid'])&&isset($_POST['ver']))){
            echo 'Error: Missing parameter';
            return false;
        }
        //var dump
        $year = $_POST['year'];
        $cid = $_POST['cid'];
        $ver = $_POST['ver'];
        $predata = array();
        //$result = TbMonthGoal::model()->findAll("year = $year AND division_id = $cid");
        $sql = "SELECT acc_id, month_id, v.`value` \n
                FROM tb_month_goal mg \n
                INNER JOIN tb_version v ON mg.month_goal_id = v.month_goal_id \n
                WHERE division_id = $cid AND `year` = $year AND v.version = $ver ";
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($result)){
            $i = intval(0);
            foreach($result as $row){
                $predata[$i]['accid'] = $row['acc_id'];
                $predata[$i]['month'] = $row["month_id"];
                $predata[$i++]['value'] = $row['value']; 
            }
            echo json_encode($predata);
        }else{
            echo 'error';
        }
    }

}
