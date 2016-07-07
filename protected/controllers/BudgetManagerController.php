<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BudgetManager
 *
 * @author Ball
 */
class BudgetManagerController extends Controller {

    public function actionFillTable() {

        $result = Yii::app()->db->createCommand(
                        "
SELECT ap.year,ap.round, AVG(IFNULL(ap.approve_lv,0)) as approve_lv, ay.acc_id, org.parent_division_id, MAX(vs.version) as version  
FROM tb_approve ap 
LEFT JOIN tb_acc_year ay ON ap.year = ay.year 
LEFT JOIN tb_org_struct org ON ap.year = org.year 
LEFT JOIN tb_version vs ON ap.year = vs.year and ap.round = vs.round 
WHERE (ap.year, ap.round) in (SELECT `year`, MAX(round) FROM tb_approve GROUP BY `year`) 
GROUP BY ap.year, ap.round"
                )->queryAll();
        if (!count($result)) {
            echo "<tr><td colspan = '5' style='text-align:center'><span class='text-danger'>ยังไม่ได้เพิ่มปีงบประมาณ</span></td></tr>";
        } else {
            foreach ($result as $row)
            {
                ?>
                <tr year="<?= $row['year'] ?>" round="<?= $row['round'] ?>" version="<?= $row['version'] ?>">
                <td><?= $row['year']+543 ?></td>
                <td><?= $row['round'], $row['round'] == 0 ? 'ปกติ' : '' ?></td>
                <td><?= $row['version'] ?></td>
                <td><?= $row['approve_lv'] == 9 ? "อนุมัติแล้ว" : 'ยังไม่อนุมัติ' ?></td>
                <td style='text-align:left;'>
                    <?php if(($row['approve_lv']==9 || $row['round'] != 1)): ?>
                    <div class="dropdown" style="display:inline-block">
                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">จัดการงบประมาณ (Adjust) <i class="glyphicon glyphicon-edit"></i>
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu dropdown-menu-close">
                            <?php if($row['approve_lv']==9):?><li class="adjust-add"><a href="#" class='btn btn-sm btn-success '><span class="text-white">ปรับปรุง <i class="glyphicon glyphicon-upload"></i></span> </a></li><?php endif; ?>
                            <?php if($row['round'] != 1):?><li class="adjust-cancel"><a href="#" class='btn btn-sm btn-danger '><span class="text-white">ยกเลิก <i class="glyphicon glyphicon-remove-circle"></i></span></a></li><?php endif; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    <?php if($row['approve_lv'] < 9 || $row['version'] > 1): ?>
                    <div class="dropdown" style="display:inline-block">
                        <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown">จัดการเวอร์ชั่น <i class="glyphicon glyphicon-book"></i>
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu dropdown-menu-close">
                            <?php if($row['approve_lv'] < 9): ?><li class='version-add'><a href="#" class='btn btn-sm btn-success '><span class="text-white">เพิ่ม <i class="glyphicon glyphicon-duplicate"></i></span> </a></li><?php endif; ?>
                            <?php if($row['version'] > 1): ?><li class='version-cancel'><a href="#" class='btn btn-sm btn-danger '><span class="text-white">ลบ <i class="glyphicon glyphicon-remove"></i></span></a></li><?php endif; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    <div class="dropdown" style="display:inline-block">
                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">บัญชีที่ใช้ <i class="glyphicon glyphicon-edit"></i>
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu dropdown-menu-close">
                            <?php if($row['acc_id'] == NULL): ?>
                            <li><a href="<?=Yii::app()->createAbsoluteUrl("Bud/AccountYearAssign", ["method"=>"assign", "year"=>$row['year']])?>" class="btn btn-sm btn-success accyear-add text-white">กำหนดบัญชีที่ใช้ <i class="glyphicon glyphicon-plus"></i></a></li>
                            <?php else: ?>
                            <li><a href="<?=Yii::app()->createAbsoluteUrl("Bud/AccountYearAssign", ["method"=>"edit", "year"=>$row['year']])?>?>" class="btn btn-sm btn-warning accyear-edit text-white">แก้ไขบัญชีที่ใช้ <i class="glyphicon glyphicon-edit"></i></a></li>
                            <li><a href="#" class="btn btn-sm btn-danger accyear-cancel text-white">ยกเลิกการกำหนด <i class="glyphicon glyphicon-trash"></i></a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <div class="dropdown" style="display:inline-block">
                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">โครงสร้างองค์กร <i class="glyphicon glyphicon-list-alt"></i>
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu dropdown-menu-close">
                            <?php if($row['parent_division_id'] == NULL): ?>
                            <li><a href="<?=Yii::app()->createAbsoluteUrl("Bud/OrgChart", ["method"=>"assign", "year"=>$row['year']])?>" class="btn btn-sm btn-success org-add text-white">กำหนดโครงสร้างองค์กร <i class="glyphicon glyphicon-plus"></i></a></li>
                            <?php else: ?>
                            <li><a href="<?=Yii::app()->createAbsoluteUrl("Bud/OrgChart", ["method"=>"edit", "year"=>$row['year']])?>" class="btn btn-sm btn-warning org-edit text-white">แก้ไขโครงสร้างองค์กร <i class="glyphicon glyphicon-edit"></i></a></li>
                            <li><a href="#" class="btn btn-sm btn-danger org-cancel text-white">ลบโครงสร้างองค์กร <i class="glyphicon glyphicon-trash"></i></a></li>
                            <?php endif; ?>
                        </ul>
                    </div>


                    <button class="btn btn-sm btn-danger budgetyear-cancel">ลบปีงบปรมาณ <span class="glyphicon glyphicon-trash"></span></button>
                </td>
            </tr>    
            <?php
            }
        }
    }

    public function actionAddYear() {
        if (isset($_POST['year'])) {
            $year = $_POST['year'];
            $year -= 543;
            $dup = TbApprove::model()->find("year = $year");
            if(count($dup)){
                print_r($dup);
                return FALSE;
            }
            $max_round = Yii::app()->db->createCommand("SELECT MAX(Round) AS Round FROM tb_approve WHERE `year` = $year")->queryScalar();
            $max_round = $max_round?$max_round:intval(1);
            $max_version = Yii::app()->db->createCommand("SELECT MAX(version) AS version FROM tb_version WHERE `year` = $year AND round = $max_round")->queryScalar();
            $max_version = $max_version?$max_version:intval(1);
            $transaction = Yii::app()->db->beginTransaction();
            try {
                //approve
                $divs = TbDivision::model()->findAll("division_level < 3 AND enable = 1");
                foreach ($divs as $div) {
                    $amodel = new TbApprove;
                    if ($amodel->isNewRecord) {
                        /* @var $div TbDivision */
                        $amodel->division_id = $div->division_id;
                        $amodel->year = $year;
                        $amodel->round = $max_round;
                        $amodel->save(true);
                    } else {
                        throw new Exception("Error while set approve");
                    }
                }
                $vmodel = new TbVersion();
                if($vmodel->isNewRecord){
                    $vmodel->year = $year;
                    $vmodel->round = $max_round;
                    $vmodel->version = $max_version;
                    $vmodel->save(true);
                }
                $transaction->commit();
                echo 'ok';
            } catch (Exception $ex) {
                $transaction->rollback();
                echo $ex->getMessage();
            }
        }else
            echo 'no year';
    }
    public function actionDeleteYear(){
        if (isset($_POST['year'])){
            $year = $_POST['year'];
            $transaction = Yii::app()->db->beginTransaction();
            try{
                TbApprove::model()->deleteAll("year = $year");
                TbVersion::model()->deleteAll("year = $year");
                $transaction->commit();
                echo 'ok';
            } catch (Exception $ex) {
                $transaction->rollback();
                echo $ex->getMessage();
            }
        }else{
            echo 'no year';
        }
    }
    
    public function actionAddAdjust(){
        if (isset($_POST['year'])){
            $year = $_POST['year'];
            //get adjust of year
            $nowAdjust = TbApprove::model()->find(array('condition'=>"year = $year", 'order'=>'round DESC'));
            if(count($nowAdjust)){
                $nowAdjust = $nowAdjust->round;
            }else{
                echo 'year is not exist';
                return FALSE;
            }
            //get version of year
            $version = TbVersion::model()->findByPk(array("year"=>$year,"round"=>$nowAdjust),array('order'=>'version DESC'));
            if(count($version)){
                $version = $version->version;
            }else{
                echo 'year is not exist';
                return FALSE;
            }
            //clone month_goal
            $newAdjust = $nowAdjust + 1;
            $tranaction = Yii::app()->db->beginTransaction();
            try{
                //copy
                Yii::app()->db->createCommand(
"INSERT INTO tb_month_goal(acc_id, quantity, value, month_id, year, round, division_id, version, subversion) "
. "SELECT acc_id, quantity, value, month_id, year, $newAdjust, division_id, 1, 1 FROM tb_month_goal WHERE year = $year AND round = $nowAdjust AND version = $version")->execute();
                //subversion
                Yii::app()->db->createCommand(
"INSERT INTO tb_subversion(month_goal_id, quantity, value, subversion) "
. "SELECT month_goal_id, quantity, value, subversion FROM tb_month_goal WHERE year = $year AND round = $newAdjust")->execute();
                //tb_approve
                Yii::app()->db->createCommand("
INSERT INTO tb_approve(year, round, division) SELECT year, $newAdjust, division FROM tb_approve WHERE year = $year AND round=$nowAdjust
                    ")->execute();
                //version
                Yii::app()->db->createCommand("
INSERT INTO tb_version(year, round, version) VALUES($year, $newAdjust, 1)
                    ")->execute();
                $tranaction->commit();
                echo 'ok';
            } catch (Exception $ex) {
                $tranaction->rollback();
                echo 'error';
            }
            
        }else echo 'year is not available';
    }
    
    public function actionDeleteAdjust(){
        //Delete Max Round
        if(isset($_POST['year'])){
            $year = $_POST['year'];
            //get Round
            $nowAdjust = TbApprove::model()->find(array('condition'=>"year = $year", 'order'=>'round DESC'));
            if(count($nowAdjust)){
                $nowAdjust = $nowAdjust->round;
            }else{
                echo 'year is not exist';
                return FALSE;
            }
            $transaction = Yii::app()->db->beginTransaction();
            try{
                //delete from subversion
                Yii::app()->db->createCommand("
DELETE FROM tb_subversion WHERE month_goal_id IN (SELECT month_goal_id FROM tb_month_goal WHERE year = $year AND round = $nowAdjust)
                    ")->execute();
                
                //delete from monthgoal
                Yii::app()->db->createCommand("
DELETE FROM tb_month_goal WHERE year = $year AND round = $nowAdjust
                    ")->execute();
                //delete from version
                Yii::app()->db->createCommand("
DELETE FROM tb_version WHERE year = $year AND round = $nowAdjust
                    ")->execute();
                //delete from approve
                Yii::app()->db->createCommand("
DELETE FROM tb_approve WHERE year = $year AND round = $nowAdjust
                    ")->execute();
                $transaction->commit();
                echo 'ok';
            } catch (Exception $ex) {
                $transaction->rollback();
                echo 'error';
            }
        }
    }
    
    public function actionAddVersion(){
        if(isset($_POST['year'])){
            $year = $_POST['year'];
            $max_round = Yii::app()->db->createCommand("SELECT MAX(Round) AS Round FROM tb_approve WHERE `year` = $year")->queryScalar();
            if($max_round == NULL){
                echo 'round null';
                return FALSE;
            }else{
                $max_version = Yii::app()->db->createCommand("SELECT MAX(version) AS version FROM tb_version WHERE `year` = $year AND round = $max_round")->queryScalar();
                $model = new TbVersion();
                if($model->isNewRecord){
                    $model->year = $year;
                    $model->round = $max_round;
                    $model->version = $max_version?$max_version+1:intval(1);
                    $result = $model->save(true);
                }
                echo $result?"ok":"not";
            }
        }else{
            echo'no year';
        }
    }
    
    public function actionDeleteVersion(){
        if(isset($_POST['year'])){
            $year = $_POST['year'];
            $max_round = Yii::app()->db->createCommand("SELECT MAX(Round) AS Round FROM tb_approve WHERE `year` = $year")->queryScalar();
            $max_version = Yii::app()->db->createCommand("SELECT MAX(version) AS version FROM tb_version WHERE `year` = $year AND round = $max_round")->queryScalar();
            
            if($max_round == NULL || $max_version == NULL){
                if($max_round == NULL) echo 'r';
                if($max_version == NULL) echo 'v';
                return FALSE;
            }else{
                $result = TbVersion::model()->deleteByPk(["year" => $year, "round" => $max_round, "version" => $max_version]);
                echo $result?"ok":"not";
            }
        }
    }
    

}
