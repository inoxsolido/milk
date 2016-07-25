<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MonthGoalController
 *
 * @author Ball
 */
class MonthGoalController extends Controller {
    //put your code here
    public function actionFillYear(){
        //หาปีที่สามารถใช้กรอกข้อมูลได้
        $sql = "SELECT ap.year, ap.round, os.child_division_id,  ap.approve_lv
            FROM tb_approve ap 
            INNER JOIN tb_acc_year ay ON ap.year = ay.year 
            INNER JOIN tb_org_struct os ON ap.year = os.year  
            WHERE (ap.year, ap.round) in (SELECT `year`, MAX(round) FROM tb_approve GROUP BY `year`) 
            GROUP BY ap.year, ap.round 
            HAVING approve_lv < 9
            ORDER BY ap.year
            ";
        $yearlist = Yii::app()->db->createCommand($sql)->queryAll();
        
        if(!empty($yearlist)){
            $output['error']="none";
            $output['data'] = $yearlist;
            echo json_encode($output);
        }else{
            $output['error']="empty";
            echo json_encode($output);
        }
    }
    
    public function actionFillJobList(){
        if(isset($_POST['year'])&&isset($_POST['round'])){
            $year = $_POST['year'];
            $round = $_POST['round'];
            //get last version
            $version = Yii::app()->db->createCommand("SELECT MAX(version) as version FROM tb_version WHERE `year` = $year AND round = $round")->queryScalar();
            $user = Yii::app()->user->UserId;
            $userdiv = Yii::app()->user->UserDiv;
            //getJob
            $sql = "
                SELECT pf.destination, dd.division_name, month_goal_id
                FROM tb_profile_fill pf
                INNER JOIN tb_division dd ON pf.destination = dd.division_id 
                LEFT JOIN tb_month_goal mg ON pf.destination = mg.division_id AND mg.year = $year AND mg.round = $round AND mg.version = $version 
                WHERE pf.Operator = $userdiv 
                GROUP BY pf.destination 
                ";
            $Joblist = Yii::app()->db->createCommand($sql)->queryAll();
            if(!empty($Joblist)){
                ?> 
<table class="table table-bordered table-condensed">
    <thead>
        <tr>
            <th>ภาระงาน</th>
            <th>จัดการ</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($Joblist as $job): ?>
        <tr destination='<?=$job['destination']?>'>
            <td><?=$job['division_name']?></td>
            <td>
                <div class="btn-group-sm" style="width:100%">
                        <?php if(!$job['month_goal_id']): ?>
                        <button class="btn btn-success assign" style="width:100%; float:left;">กำหนด <i class="glyphicon glyphicon-plus"></i></button>
                        <?php else: ?>
                        <button class="btn btn-warning edit" style="width:100%; float:left;">แก้ไข <i class="glyphicon glyphicon-edit"></i></button>
                        <?php endif; ?>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
                    <?php
            }else{
                echo 'empty';
                echo $sql;
            }
        }else{
            echo 'parameter';
            print_r($_POST);
        }
    }
    
    public function actionMonthGoalForm(){
        if(isset($_POST['cid']) && isset($_POST['cname'])&& isset($_POST['year']) && isset($_POST['round']) && isset($_POST['method'])){
            $cid = $_POST['cid'];
            $cname = $_POST['cname'];
            $year = $_POST['year'];
            $round = $_POST['round'];
            $method = $_POST['method'];
            //print_r($_POST);
            $this->renderPartial("../Bud/MonthGoalInput", ["cid"=>$cid, "cname"=>$cname, "year"=>$year, "round"=>$round, "method"=>$method]);
        }
    }
    
    private function hasChild($me)
    {
        $result = TbAccount::model()->findAll("`parent_acc_id` = $me");
        return (count($result)) != 0;
    }
    
    
    public function caninput($year, $round, $cid, $acc){

        $resource = TbMgLimit::model()->findByPk(array("year"=>$year, "round"=>$round, "division"=>$cid, "acc_id"=>$acc));
        if(!empty($resource)){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    
    
    public function actionFillMonthGoalInputNewNew(){
        if (!(isset($_POST["year"])&&isset($_POST['round'])&&isset($_POST['cid'])))
        {
            echo "error variable missing !";
            return FALSE;
        }
        $year = $_POST['year'];
        $round = $_POST['round'];
        $cid = $_POST['cid'];
        $section = TbDivision::model()->findByPk($cid)->section;
        
        
        $accs = Yii::app()->db->createCommand()->select("acc_id")->from("tb_acc_year")->where("year = $year")->queryAll();
        //$accs = Yii::app()->db->createCommand($parentaccsql)->queryAll();
        if (empty($accs))
        {
            echo 'invalid parameter';
            return FALSE;
        }
        $in = " AND acc_id IN (";
        foreach ($accs AS $acc)
        {
            $in .= $acc['acc_id'] . ', ';
//            $in .= $acc['ac1'] . ', ';
//            $in .= $acc['ac2'] . ', ';
//            $in .= $acc['ac3'] . ', ';
//            $in .= $acc['ac4'] . ', ';
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
                /* @var $m TbMonth */
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
                    $resultlv1 = TbAccount::model()->findAll("parent_acc_id IS NULL $in ORDER BY `order` ASC ");
                    if (count($resultlv1))
                    {
                        ?><ul class='checkbox-tree'><?php
                                        foreach ($resultlv1 as $lv1)//level 1
                                        {
                                            ?><li><?php
                                            ?><label><input type="checkbox" class="chkacc" name="<?= $lv1->acc_id ?>" /><?= $lv1->acc_name ?></label><?php
                                            if (!$this->hasChild($lv1->acc_id)){ 
                                                                                                ?>:&nbsp;
                                                                                                <div style="display:none" class="txtacc">
                                                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                                                    <?php if($section == 4 && $lv1->group_id): ?>
                                                                                                    <label>ผลิตภัณฑ์: <input type="text" name="acc-<?= $lv1->acc_id ?>" month="<?= $m->month_id ?>" cat='p' /> ตัน</label>
                                                                                                    <label>จำหน่ายสุทธิ: <input type="text" name="acc-<?= $lv1->acc_id ?>" month="<?= $m->month_id ?>" cat='n' /> ตัน</label>
                                                                                                    <?php endif; ?>
                                                                                                    <label>มูลค่า: <input type="text" name="acc-<?= $lv1->acc_id ?>" month="<?= $m->month_id ?>" cat='v' /><span class="text-danger err"></span>บาท</label>
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
                                                            ?><label><input type="checkbox" class="chkacc" name="<?= $lv2->acc_id ?>"  ><?= $lv2->acc_name ?></label><?php
                                                            if (!$this->hasChild($lv2->acc_id)){ 
                                                                                                ?>:&nbsp;
                                                                                                <div style="display:none" class="txtacc">
                                                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                                                    <?php if($section == 4 && $lv2->group_id): ?>
                                                                                                    <label>ผลิตภัณฑ์: <input type="text" name="acc-<?= $lv2->acc_id ?>" month="<?= $m->month_id ?>" cat='p' /> ตัน</label>
                                                                                                    <label>จำหน่ายสุทธิ: <input type="text" name="acc-<?= $lv2->acc_id ?>" month="<?= $m->month_id ?>" cat='n' /> ตัน</label>
                                                                                                    <?php endif; ?>
                                                                                                    <label>มูลค่า: <input type="text" name="acc-<?= $lv2->acc_id ?>" month="<?= $m->month_id ?>" cat='v' /><span class="text-danger err"></span>บาท</label>
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
                                                                            ?><label><input type="checkbox" class="chkacc" name="<?= $lv3->acc_id ?>" /><?= $lv3->acc_name ?></label><?php
                                                                            if (!$this->hasChild($lv3->acc_id)){ 
                                                                                                ?>:&nbsp;
                                                                                                <div style="display:none" class="txtacc">
                                                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                                                    <?php if($section == 4 && $lv3->group_id): ?>
                                                                                                    <label>ผลิตภัณฑ์: <input type="text" name="acc-<?= $lv3->acc_id ?>" month="<?= $m->month_id ?>" cat='p' /> ตัน</label>
                                                                                                    <label>จำหน่ายสุทธิ: <input type="text" name="acc-<?= $lv3->acc_id ?>" month="<?= $m->month_id ?>" cat='n' /> ตัน</label>
                                                                                                    <?php endif; ?>
                                                                                                    <label>มูลค่า: <input type="text" name="acc-<?= $lv3->acc_id ?>" month="<?= $m->month_id ?>" cat='v' /><span class="text-danger err"></span>บาท</label>
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
                                                                                        ?><label><input type="checkbox" class="chkacc" name="<?= $lv4->acc_id ?>" /><?= $lv4->acc_name  ?></label><?php
                                                                                        if (!$this->hasChild($lv4->acc_id)){ 
                                                                                            ?>:&nbsp;
                                                                                                <div style="display:none" class="txtacc">
                                                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                                                    <?php if($section == 4 && $lv4->group_id): ?>
                                                                                                    <label>ผลิตภัณฑ์: <input type="text" name="acc-<?= $lv4->acc_id ?>" month="<?= $m->month_id ?>" cat='p' /> ตัน</label>
                                                                                                    <label>จำหน่ายสุทธิ: <input type="text" name="acc-<?= $lv4->acc_id ?>" month="<?= $m->month_id ?>" cat='n' /> ตัน</label>
                                                                                                    <?php endif; ?>
                                                                                                    <label>มูลค่า: <input type="text" name="acc-<?= $lv4->acc_id ?>" month="<?= $m->month_id ?>" cat='v' /><span class="text-danger err"></span>บาท</label>
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
                    
                ?></div><?php
                }//foreach group end
        ?></div><?php
    }
    
    public function actionMgSave(){
        if(isset($_POST['year'])&&isset($_POST['round'])&&isset($_POST['method'])&&isset($_POST['cid'])&&isset($_POST['detail'])){
            $year = $_POST['year'];
            $round = $_POST['round'];
            $method = $_POST['method'];
            $cid = $_POST['cid'];
            $detail = $_POST['detail'];
            
            //get last version of year and round
            $lastversion = Yii::app()->db->createCommand("
                SELECT MAX(version) AS version 
                FROM tb_version 
                WHERE `year` = $year AND `round` = $round")->queryScalar();
            
            if(!$lastversion) {echo 'no lastversion'; return FALSE;}
            
            //get last subversion of year round and version
            $lastsubversion = Yii::app()->db->createCommand("
                SELECT MAX(subversion) AS subversion 
                FROM tb_month_goal 
                WHERE `year` = $year AND `round` = $round AND `version` = $lastversion AND division_id = $cid;
                ")->queryScalar();

            $transaction = Yii::app()->db->beginTransaction();
            try{
                if($method == 'edit' && FALSE){
                    if(!$lastsubversion) {echo 'no lastsubversion'; return FALSE;}

                //dump month_goal to subversion by year round version
                    Yii::app()->db->createCommand("
                    INSERT INTO tb_subversion(month_goal_id, subversion, product_quantity, product_quantity_net, `value`) 
                    SELECT month_goal_id, (subversion + 1), product_quantity, product_quantity_net,  `value` FROM tb_month_goal WHERE `year` = $year AND `round` = $round AND `version` = $lastversion AND division_id = $cid
                    ")->execute();
                }
                $lastsubversion = $lastsubversion?intval($lastsubversion)+1:1 ;
                foreach($detail as $row){
                    $acc_id = $row['acc_id'];
                    $month_id = $row['month'];
                    $value = $row['value'];
                    $quantity = !@$row['quantity']?"NULL":@$row['quantity'];
                    $quantity_net = !@$row['quantity_net']?"NULL":@$row['quantity_net'];
                    Yii::app()->db->createCommand("
                    INSERT INTO tb_month_goal(`year`, `round`, `division_id`, `acc_id`, `month_id`, `version`, subversion, product_quantity, product_quantity_net, `value`) 
                    VALUES($year, $round, $cid, $acc_id, $month_id, $lastversion, $lastsubversion, $quantity, $quantity_net, $value) 
                        ON DUPLICATE KEY UPDATE `year` = $year, `round` = $round, division_id = $cid, `acc_id` = $acc_id, `month_id` = $month_id, `version` = $lastversion, subversion = $lastsubversion, product_quantity = $quantity, product_quantity_net = $quantity_net , `value` = $value
                    ")->execute();
                }
                //backup newdata to subversion
                Yii::app()->db->createCommand("
                    INSERT INTO tb_subversion(month_goal_id, subversion, product_quantity, product_quantity_net, `value`) 
                    SELECT month_goal_id, subversion, product_quantity, product_quantity_net,  `value` FROM tb_month_goal WHERE `year` = $year AND `round` = $round AND `version` = $lastversion AND division_id = $cid
                    ")->execute();
                $transaction->commit();
                echo 'ok';
            } catch (Exception $ex) {
                $transaction->rollback();
                echo die($ex->getMessage());
            }
                
        }else echo 'parameter fail';
    }
    public function actionMgInfo(){
        if(!(isset($_POST['year'])&&isset($_POST['cid'])&&isset($_POST['round']))){
            echo 'Error: Missing parameter';
            return false;
        }
        //var dump
        $year = $_POST['year'];
        $cid = $_POST['cid'];
        $round = $_POST['round'];
        $version = Yii::app()->db->createCommand("
            SELECT MAX(version) as version 
            FROM tb_version 
            WHERE `year` = $year AND `round` = $round"
                )->queryScalar();
        if(!$version) {echo 'no version'; return FALSE;}
        
        $predata = array();
        $result = Yii::app()->db->createCommand("
            SELECT * 
            FROM tb_month_goal 
            WHERE `year` = $year AND `round` = $round 
                AND division_id = $cid AND version = $version")->queryAll();
        if(!empty($result)){
            $i = intval(0);
            foreach($result as $row){
                $predata[$i]['accid'] = $row['acc_id'];
                $predata[$i]['month'] = $row["month_id"];
                $predata[$i]['quantity'] = $row['product_quantity'];
                $predata[$i]['quantity_net'] = $row['product_quantity_net'];
                $predata[$i]['value'] = $row['value']; 
                $i += 1;
            }
            echo json_encode($predata);
        }else{
            echo 'error query';
        }
    }
    public function actionMgFillVersionSelector(){
        if(!isset($_POST['cid'])){
            echo 'Error: Missing parameter';
            return false;
        }
        $cid = $_POST['cid'];
        $result = Yii::app()->db->createCommand("
            SELECT ap.year, ap.round, v.version, (sv.subversion) AS subversion    
FROM tb_approve ap 
INNER JOIN tb_version v ON ap.year = v.year AND ap.round = v.round 
INNER JOIN tb_month_goal mg ON ap.year = mg.year AND ap.round = mg.round AND v.version = mg.version 
INNER JOIN tb_subversion sv ON mg.month_goal_id = sv.month_goal_id
WHERE mg.division_id = $cid 
GROUP BY ap.year, ap.round, v.version, sv.subversion")->queryAll();
        
        $versionInfo = [];
        $versionInfo['error'] = 'none';
        $info = [];
        foreach($result as $row){
            $year = $row['year'];
            $round = $row['round'];
            $version = $row['version'];
            $subversion = $row['subversion'];
            $info[$year][$round][$version][]=$subversion;
        }
        if(empty($result)){
            $versionInfo['error']='empty';
        }
        $versionInfo['info']=$info;
        echo json_encode($versionInfo);
        
    }
    public function actionMgVersionInfo(){
        if(isset($_POST['year'])&&isset($_POST['round'])&&isset($_POST['version'])&&isset($_POST['subversion'])){
            $year = $_POST['year'];
            $round = $_POST['round'];
            $cid = $_POST['cid'];
            $version = $_POST['version'];
            $subversion = $_POST['subversion'];
            //$result = TbMonthGoal::model()->findAll("year = $year AND division_id = $cid");
            $sql = "
SELECT mg.acc_id, mg.month_id, sv.product_quantity, sv.product_quantity_net, sv.`value`
FROM tb_month_goal mg 
INNER JOIN tb_subversion sv ON mg.month_goal_id = sv.month_goal_id
WHERE mg.year = $year AND mg.round = $round AND mg.version = $version AND mg.division_id = $cid AND sv.subversion = $subversion";
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            $data['error'] = "empty";
            if(!empty($result)){
                $data['error'] = "none";
                $data['info'] = $result;
            }
            echo json_encode($data);
        }else{
            echo 'parameter error';
        }
    }
}
