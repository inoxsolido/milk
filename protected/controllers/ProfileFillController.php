<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProfileFillController
 *
 * @author Ball
 */
class ProfileFillController extends Controller{
    //put your code here
    public function actionFillFilling()
    {
        if (isset($_POST['ajax']))
        {
//            $sql = "SELECT tb_profile_fill.Operator as pk1, tb_profile_fill.Destination as pk2, ow.own_name "
//                    . ",tp.tar_name "
//                    . "FROM tb_profile_fill "
//                    . "INNER JOIN (SELECT division_id as div_id, division_name as own_name FROM tb_division d "
//                    //. "LEFT JOIN (SELECT division_id as o_div_id, division_name as own_par_name FROM tb_division) oo ON d.parent_division = oo.o_div_id "
//                    . ") ow ON tb_profile_fill.owner_div_id = ow.div_id "
//                    . "INNER JOIN (SELECT division_id as div_id, division_name as tar_name FROM tb_division d "
//                    //. "LEFT JOIN (SELECT division_id as t_div_id, division_name as tar_par_name FROM tb_division) tt ON tt.t_div_id = d.parent_division "
//                    . ") tp ON tb_profile_fill.division_id = tp.div_id";
            $sql = "SELECT pf.operator as pk1, pf.destination as pk2, do.division_name AS operator_name, dd.division_name AS destination_name
                FROM tb_profile_fill pf 
                INNER JOIN tb_division do ON pf.operator = do.division_id 
                INNER JOIN tb_division dd ON pf.destination = dd.division_id ";
            if (!empty($_POST['searchtxt']['owner']) || !empty($_POST['searchtxt']['target'])){
                    $sql .= " WHERE";
                    $s = $_POST['searchtxt'];
                    if (!empty($s['owner']))
                        $sql .= " do.division_name LIKE '%" . $s['owner'] . "%' AND";
//                    if (!empty($s['ownerpar']))
//                        $sql .= " own_par_name LIKE '%" . $s['ownerpar'] . "%' AND";
                    if (!empty($s['target']))
                        $sql .= " dd.division_name LIKE '%" . $s['target'] . "%' AND";
//                    if (!empty($s['targetpar']))
//                        $sql .= " tar_par_name LIKE '%" . $s['targetpar'] . "%' AND";
                    $sql = substr($sql, 0, -3);
                }
            
            $sql .= " ORDER BY do.division_name ASC, dd.division_name ASC";
            $result = Yii::app()->db->createCommand($sql)->queryAll();
//            print_r($sql);
//            print_r($_POST);
//            print_r($result);

            foreach ($result as $row)
            {
                ?><tr>
                    <td style="width:40%"><?= $row['operator_name'] . " " ?></td>
                    <td style="width:40%"><?= $row['destination_name'] . " " ?></td>
                    <td style="width:20%"><div class="btn-group-sm" style="width:100%"><button class='btn btn-sm btn-warning edit' style="width:50%" data-id1="<?= $row['pk1'] ?>" data-id2="<?= $row['pk2'] ?>">แก้ไข <span class='glyphicon glyphicon-wrench'></span></button><?php
                    ?><button class="btn btn-sm btn-danger delete" style="width:50%"data-id1="<?= $row['pk1'] ?>" data-id2="<?= $row['pk2'] ?>">ลบ <span class="glyphicon glyphicon-trash"></span></button>
                        </div>
                    </td>
                </tr><?php
            }
        }
    }

    public function actionFillFillingOwner()
    {
        if (isset($_POST['ajax']))
        {
            $sql = "SELECT division_id as div_id, division_name as div_name "
                    . "FROM tb_division "
                    . "WHERE division_level <= 3"
                    . " ORDER BY tb_division.division_level DESC, tb_division.erp_id";
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($result as $row)
            {
                ?><option value="<?= $row['div_id'] ?>"><?= $row['div_name'] ?></option><?php
            }
        }
    }

    public function actionFillFillingTarget()
    {
        if (isset($_POST['ajax']))
        {
            $sql = "SELECT division_id as div_id, division_name as div_name "
                    . "FROM tb_division "
                    . "WHERE division_level <= 3 AND division_id NOT IN (SELECT destination FROM tb_profile_fill) 
                        ORDER BY tb_division.division_level DESC, tb_division.erp_id";
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($result as $row)
            {
                ?><option value="<?= $row['div_id'] ?>"><?= $row['div_name'] ?></option><?php
            }
        }
    }

    public function actionFillFillingTargetEdit()
    {
        if (isset($_POST['ajax']) && isset($_POST['pk1']) && isset($_POST['pk2']))
        {
            $pk1 = $_POST['pk1'];
            $pk2 = $_POST['pk2'];
            $sql = "SELECT division_id as div_id, division_name as div_name "
                    . "FROM tb_division "
                    . "WHERE division_id NOT IN (SELECT destination FROM tb_profile_fill) OR division_id IN (SELECT destination FROM tb_profile_fill WHERE operator = $pk1 AND destination = $pk2)";
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($result as $row)
            {
                ?><option value="<?= $row['div_id'] ?>"><?= $row['div_name'] ?></option><?php
                }
            }
        }

        public function actionFillingDel()
        {
            if (isset($_POST['ajax']) && isset($_POST['id']) && !empty($_POST['id']['id1']) && !empty($_POST['id']['id2']))
            {
                echo TbProfileFill::model()->deleteByPk(array('operator' => $_POST['id']['id1'], 'destination' => $_POST['id']['id2']));
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

                $model = TbProfileFill::model()->findByPk(array("operator" => $pk1, "destination" => $pk2));

                $model->owner_div_id = $val1;
                $model->division_id = $val2;
                echo $model->save() ? 1 : 0;
            }
        }
}
