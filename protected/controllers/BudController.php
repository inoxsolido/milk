<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of budController
 *
 * @author s5602041620019
 */
class BudController extends Controller
{

    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules()
    {
        $admin = Yii::app()->db->createCommand('SELECT username FROM tb_user WHERE position_id = 3 ')->queryAll();
        $div = Yii::app()->db->createCommand('SELECT username FROM tb_user WHERE position_id = 2 ')->queryAll();
        $dep = Yii::app()->db->createCommand('SELECT username FROM tb_user WHERE position_id = 1 ')->queryAll();
        $user = $dep = Yii::app()->db->createCommand('SELECT username FROM tb_user ')->queryAll();
        return array(
            array('deny', // deny all users
                'actions' => array('MonthGoal'),
                'users' => array('admin')
            ),
        );
    }

    public function actionIndex()
    {
        if (!Yii::app()->user->isGuest)
        {
            $this->redirect(Yii::app()->createUrl('./Bud/main'));
        }
        else
        {
            $url = Yii::app()->createUrl('./Bud/faq');
            $this->redirect($url);
        }
    }

    public function actionFaq()
    {
        $this->render('faq');
    }

    public function actionLogin()
    {
        if (!Yii::app()->user->isGuest)
        {
            //logged in
            $this->redirect(Yii::app()->createAbsoluteUrl('./Bud/main'));
        }
        else
            $this->render('login');
    }

    public function actionMain()
    {
        if (Yii::app()->user->isGuest)
        {
            $this->redirect(Yii::app()->createAbsoluteUrl('./Bud/login'));
        }
        else
        {
            $this->redirect('faq');
        }
    }

    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect('../Bud/index');
    }

    public function actionUserManager()
    {
        if (!Yii::app()->user->isGuest)
        {
            if (Yii::app()->user->isAdmin)
            {

                $sql = "SELECT division_id, d.division_name, parent_division, p.par_name FROM tb_division d "
                        . "LEFT JOIN (SELECT division_id as ppar_id, division_name as par_name FROM tb_division) p ON d.parent_division = p.ppar_id ";
                $sqldiv = $sql . " WHERE division_level = 3 ORDER BY d.erp_id ASC ";
                $sqldep = $sql . " WHERE division_level < 3 ORDER BY d.erp_id ASC ";
                $div = Yii::app()->db->createCommand($sqldiv)->queryAll();
                $dep = Yii::app()->db->createCommand($sqldep)->queryAll();

                $position = TbPosition::model()->findAll();

                $this->render('usermanager', array(
                    'dep' => $dep,
                    'div' => $div,
                    'pos' => $position,
                ));
            }
            else
            {
                echo 'You have not permission to access this pages';
                echo '<br>';
                ?><a href="<?= Yii::app()->createAbsoluteUrl('./Bud/main') ?>">Back to main</a><?php
            }
        }
        else
        {
            $this->redirect('../Bud/index');
        }
    }

    public function actionDivManager()
    {
        if (Yii::app()->user->isAdmin)
        {

            $model = TbDivision::model()->findAll();

            $this->render('divManager', array('par_model' => $model));
        }
        else
        {
            echo 'You have not permission to access this pages';
            echo '<br>';
            ?><a href="<?= Yii::app()->createAbsoluteUrl('./Bud/main') ?>">Back to main</a><?php
        }
    }

    public function actionFillingManager()
    {
        if (Yii::app()->user->isAdmin)
        {
            $this->render('fillingManager');
        }
        else
        {
            echo 'You have not permission to access this pages';
            echo '<br>';
            ?><a href="<?= Yii::app()->createAbsoluteUrl('./Bud/main') ?>">Back to main</a><?php
        }
    }

    public function actionAccountManager()
    {
        if (Yii::app()->user->isAdmin)
        {
            $group = TbGroup::model()->findAll();
            $this->render('AccountManager', array('group' => $group));
        }
        else
        {
            echo 'You have not permission to access this pages';
            echo '<br>';
            ?><a href="<?= Yii::app()->createAbsoluteUrl('./Bud/main') ?>">Back to main</a><?php
        }
    }

    public function actionAccountYearAssign()
    {
        if (Yii::app()->user->isAdmin)
        {
            $acc = TbAccount::model()->findAll(array('order' => "group_id ASC, acc_name ASC"));
            $group = TbGroup::model()->findAll();
            $this->render("AccountYearAssign", array('acc' => $acc, 'group' => $group));
        }
        else
        {
            echo 'You have not permission to access this pages';
            echo '<br>';
            ?><a href="<?= Yii::app()->createAbsoluteUrl('./Bud/main') ?>">Back to main</a><?php
        }
    }

    public function actionMonthGoal()
    {
        $userid = Yii::app()->user->UserId;
        $userdiv = Yii::app()->user->UserDiv;
        $resource = TbApprove::model()->findAll("division_id = $userdiv");
        
        $yearresource = Yii::app()->Resource->YearResource;
        if(empty($yearresource)){
            echo 'ขณะนี้ระบบยังไม่เปิดให้กรอกข้อมูล <a href="#" onclick="window.history.back();">ย้อนกลับ</a>';
        }else{
            $method = Yii::app()->request->getParam("m");
            $cid = Yii::app()->request->getParam("cid");
            
            if(empty($method) || empty($cid)){
                //normal request
                $this->render("MonthGoalSel", array("round" => $yearresource['round'], "year" => $yearresource['year']));
            }else{
                //assign/edit request
                $cname = TbDivision::model()->findByPk($cid)->division_name;
                $this->render("MonthGoalInput", array("round" => $yearresource['round'], "year" => $yearresource['year'], 
                    "method"=>$method, "cid"=>$cid, "cname"=>$cname));
            }
        }
        
        return;
        //ของเก่า ที่ยังไม่ใช้ตาราง tb_approve
        $user = Yii::app()->user->UserId;
        $userdiv = Yii::app()->user->UserDiv;
        //find max approve of the year
        $resource = Yii::app()->db->createCommand()->select("ay.`year`, AVG(IFNULL(approve1_lv, 0)) AS approve1, AVG(IFNULL(approve2_lv, 0)) AS approve2")
                        ->from("tb_acc_year ay")->leftJoin("tb_month_goal mg", "ay.year = mg.year AND ay.acc_id = mg.acc_id")
                        ->group("ay.year")->order("year ASC")->queryAll();
        $txt = Yii::app()->db->createCommand()->select("ay.`year`, AVG(approve1_lv) AS approve1, AVG(approve2_lv) AS approve2")
                        ->from("tb_acc_year ay")->leftJoin("tb_month_goal mg", "ay.year = mg.year AND ay.acc_id = mg.acc_id")
                        ->group("ay.year")->order("year ASC")->text;
        $year = intval(0);
        $approve1_lv = intval(0);
        $approve2_lv = intval(0);
        if (!count($resource))
        {
            echo 'ขณะนี้ระบบยังไม่เปิดให้กรอกข้อมูล <a href="#" onclick="window.history.back();">ย้อนกลับ</a>';
        }
        else
        {
            foreach ($resource as $row)
            {
                $approve1_lv = $row['approve1'];
                $approve2_lv = $row['approve2'];
                if($approve1_lv < 1 || ($approve2_lv < 1 && $approve1_lv == 3)){
                    $year = $row['year'];
                    break;
                }
            }
        }
        
        if ($year != 0)
        {
            /*$divusertakecare = Yii::app()->db->createCommand()->select("tb_division.division_id, division_name")
                            ->from("tb_division")->join("tb_profile_fill", "tb_division.division_id = tb_profile_fill.division_id")
                            ->where("tb_profile_fill.owner_div_id = $userdiv")->queryAll();*/
            $depusertakecare = Yii::app()->db->createCommand()->select("d.division_id, division_name,"
                    . "ay.`year`, MAX(IFNULL(approve1_lv, 0)) AS approve1, MAX(IFNULL(approve2_lv, 0)) AS approve2")
                        ->from("tb_acc_year ay")
                        ->leftJoin("tb_month_goal mg", "ay.year = mg.year AND ay.acc_id = mg.acc_id")
                        ->leftJoin("tb_division d", "mg.division_id = d.division_id")
                        ->join("tb_profile_fill pf", "d.division_id = pf.division_id")
                        ->join("tb_mg_limit mgl", "d.division_id = mgl.division")
                        ->where("pf.owner_div_id = $userdiv AND ay.year = $year")
                        ->group("ay.year, d.division_id")->order("year ASC")
                        ->having("approve1 < 1 OR (approve2 < 1 AND approve1 = 3)")
                        ->queryAll();
            $this->render("MonthGoal", array("year" => $year + 543, 'targets' => $depusertakecare));
        }
        else
        {
            echo 'ขณะนี้ระบบยังไม่เปิดให้กรอกหรือแก้ไขข้อมูล <a href="#" onclick="window.history.back();">ย้อนกลับ</a>';
        }



        return;
        if (!empty($maxapprove))
            if ($maxapprove['approve1'] < 3 || $maxapprove['approve2'] < 3)
                $firstcheck = Yii::app()->db->createCommand()->selectDistinct("a.year")
                                ->from("tb_acc_year a")->naturalJoin("tb_month_goal");


        return true;
        //ล้มเหลว
        //ตรวจสอบว่ามันเป็นครั้งแรกมั้ย ครั้งแรกจะเป็น NUL
        $resultFirst = Yii::app()->db->createCommand()->selectDistinct("tb_acc_year.year")->from("tb_acc_year")->naturalJoin("tb_month_goal")
                ->where("approve1_lv < 3 AND approve2_lv < 3 AND user_id = $user AND division_id = $div")->order("tb_acc_year.year ASC")
                ->queryScalar();
        print_r($resultFirst);
        return;
        $sql = "SELECT DISTINCT(a.`year`) FROM tb_acc_year a LEFT JOIN tb_month_goal m ON a.`year` = m.`year` AND a.`acc_id` = m.`acc_id` "
                . "WHERE (approve1_lv < 3 AND approve2_lv < 3 AND user_id = $user AND division_id = $div ) ORDER BY `year` ASC ";
        $result = Yii::app()->db->createCommand($sql)->queryScalar();
        $year = 543;
        if (!empty($result))
            $year += $result;
        else
            echo $year;return;
    }

    public function actionChkState()
    {
        echo 'isDiv:' . Yii::app()->user->isDivision . '<br/>';
        echo 'isDep:' . Yii::app()->user->isDepartment . '<br/>';
        echo 'isAdmin:' . Yii::app()->user->isAdmin . '<br/>';
        echo 'user_id:' . Yii::app()->user->UserId . '<br/>';
        echo '<hr/><hr/>';
        $userlv = Yii::app()->user->isAdmin ? 3 : Yii::app()->user->isDivision ? 2 : Yii::app()->user->isDepartment ? 1 : 0;
        echo $userlv;
        echo '<hr/>';
        echo Yii::app()->user->UserPosition;
    }
    
    //approve
    public function actionApproveoldold()
    {
        $div = Yii::app()->user->UserDiv;
        //$devindiv = Yii::app()->db->createCommand()->select()->from("tb_");
        $user = Yii::app()->user->UserId;
        $userdiv = Yii::app()->user->UserDiv;
        //find max approve of the year
        $resource = Yii::app()->db->createCommand()->select("`year`, AVG(approve1_lv) AS approve1, AVG(approve2_lv) AS approve2, COUNT(month_goal_id) AS number")
                        ->from("tb_acc_year")->naturalLeftJoin("tb_month_goal")
                        ->group("tb_acc_year.year")->order("year ASC")->queryAll();
        $year = intval(0);
        $approve1_lv = intval(0);
        $approve2_lv = intval(0);
        $userlv = Yii::app()->user->UserPosition;
        if (!count($resource))
        {
            echo 'ขณะนี้ระบบยังไม่เปิดให้กรอกข้อมูล <a href="#" onclick="window.history.back();">ย้อนกลับ</a>';
        }
        else
        {
            foreach ($resource as $row)
            {
                $approve1_lv = $row['approve1'];
                $approve2_lv = $row['approve2'];
                if($approve1_lv == 0 || ($approve2_lv == 0 && $approve1_lv == 3)){
                    $year = $row['year'];
                    break;
                }
            }
        }
        if ($year != 0)
        {//ในปีนี้มีการกรอกข้อมูลมาแล้ว
            //เป็นการยืนยันข้อมูลรอบที่
            $round = intval(1);
            if($approve1_lv == 2)
            {
                //เช็คว่าทุกแผนกเป็น 2 มั้ย โดยการเทียบจำนวนแผนกทีได้รับการกรอก กับ จำนวนแผนกที่ได้รับการยืนยัน
                $countdep = Yii::app()->db->createCommand()->select("COUNT(*)")
                        ->from("tb_division d")->join("tb_profile_fill pf", "d.division_id = pf.division_id")->queryScalar();
                $countapprove2 = Yii::app()->db->createCommand()->select("COUNT(DISTINCT d.division_id)")
                        ->from("tb_division d")->join("tb_profile_fill pf", "d.division_id = pf.division_id")
                        ->join("tb_month_goal mg", "d.division_id = mg.division_id")
                        ->where("mg.approve1_lv = 2")
                        ->group("d.division_id")
                        ->queryScalar();
                $countapprove3 = Yii::app()->db->createCommand()->select("COUNT(DISTINCT d.division_id)")
                        ->from("tb_division d")->join("tb_profile_fill pf", "d.division_id = pf.division_id")
                        ->join("tb_month_goal mg", "d.division_id = mg.division_id")
                        ->where("mg.approve1_lv = 3")
                        ->group("d.division_id")
                        ->queryScalar();
                if($countdep == $countapprove2)
                    $round = 2;
                else if($countdep == $countapprove3)
                    $round = 3;
            }
            if($approve1_lv < 3){//ตรวจว่าเป็นรอบก่อนการประชุมหรือเปล่า
                
                if($approve1_lv > 2)//ตรวจว่าการยืนยันอยู่ระดับไหน div หรือ admin
                {//ระดับ div
                    
                    if($userlv == 2)
                    {
                        
                        $dep = Yii::app()->db->createCommand()
                                ->select("dc.division_id as cid, dc.division_name as cname, dp.division_id as pid, dp.division_name as pname, "
                                        . "mg.approve1_lv as state1, mg.approve2_lv as state2")
                                ->from("tb_division dc")
                                ->join("tb_division dp", "dc.parent_division = dp.division_id")//for parent
                                ->join("tb_profile_fill pf", "pf.division_id = dc.division_id")//for checking the department must been input
                                ->leftJoin("tb_month_goal mg", "mg.division_id = dc.division_id")//ใช้สำหรับ ดูว่ามีการกรอกข้อมูลหรือยัง
                                ->where("dp.division_id = $userdiv AND dc.enable = 1")
                                ->group("dc.division_id")->queryAll();
                        
                        if(empty($dep)){
                            echo 'ฝ่ายนี้ยังไม่ได้มีการกำหนดข้อมูลสำหรับการกรอกข้อมูล กรุณาติดต่อ Admin <a href="#" onclick="window.history.back();">ย้อนกลับ</a>';
                            return false;
                        }
                        $div = Yii::app()->db->createCommand()->select("division_id as id, division_name as name")->from("tb_division")->where("parent_division = 0")->queryAll();
                        $this->render("approve", array("dep" => $dep, 'round' => $round, 'roundword'=>'ก่อนการประชุม', 'year' => $year + 543, 'div' => $div));
                    }
                    else if($userlv == 3)
                    {
                        $dep = Yii::app()->db->createCommand()
                                ->select("dc.division_id as cid, dc.division_name as cname, dp.division_id as pid, dp.division_name as pname, "
                                        . "mg.approve1_lv as state1, mg.approve2_lv as state2")
                                ->from("tb_division dc")
                                ->join("tb_division dp", "dc.parent_division = dp.division_id")//for parent
                                ->join("tb_profile_fill pf", "pf.division_id = dc.division_id")//for checking the department must been input
                                ->leftJoin("tb_month_goal mg", "mg.division_id = dc.division_id")//ใช้สำหรับ ดูว่ามีการกรอกข้อมูลหรือยัง
                                ->where("dc.enable = 1")
                                ->group("dc.division_id")->queryAll();
                        if(empty($dep)){
                            echo 'ยังไม่ได้มีการกำหนดข้อมูลสำหรับการกรอกข้อมูล กรุณาไปยังหน้าจัดการจัดการการกรอกของสังกัด<a href="'
                            .Yii::app()->createAbsoluteUrl("bud/fillingManager").'">คลิ๊ก</a>';
                            return false;
                        }
                        $div = Yii::app()->db->createCommand()->select("division_id as id, division_name as name")->from("tb_division")->where("parent_division = 0")->queryAll();
                        $this->render("approve", array("dep" => $dep, 'round' => $round, 'roundword'=>'ก่อนการประชุม', 'year' => $year + 543, 'div' => $div));
                    }
                }else //admin หลังการประชุม และ อนุญาตให้แก้ไขข้อมูลได้
                {
                    if($userlv == 3)//ใช้เปลี่ยน approve1_lv ให้เป็น 3
                    {
                        $this->render("approve2");
                    }
                }
            }
            else
                if($approve2_lv < 1)//div ยืนยัน
                {
                    if($userlv == 2)
                    {
                        
                    }
                }else if($approve2_lv)//admin ยืนยัน และ ปิดการส่งงบประมาณ
                {
                    if($userlv == 3)
                    {
                        
                    }
                }
            /*
            $divusertakecare = Yii::app()->db->createCommand()->select("tb_division.division_id, division_name")
                            ->from("tb_division")->join("tb_profile_fill", "tb_division.division_id = tb_profile_fill.division_id")
                            ->where("tb_profile_fill.owner_div_id = $userdiv")->queryAll();
            $this->render("MonthGoal", array("year" => $year + 543, 'targets' => $divusertakecare));
             * 
             */
        }
        else
        {
            echo 'ขณะนี้ระบบยังไม่เปิดให้กรอกหรือแก้ไขข้อมูล <a href="#" onclick="window.history.back();">ย้อนกลับ</a>';
        }
        
        
        //$this->render("approve");
    }
    public function actionApproveold()
    {
        $user = Yii::app()->user->UserId;
        $userdiv = Yii::app()->user->UserDiv;
        //find max approve of the year
        $resource = Yii::app()->db->createCommand()
                ->select("tb_acc_year.`year`, MAX(IFNULL(approve1_lv,0)) AS approve1, MAX(IFNULL(approve2_lv,0)) AS approve2, COUNT(month_goal_id) AS number")
                        ->from("tb_acc_year")->leftjoin("tb_month_goal", "tb_acc_year.year = tb_month_goal.year")
                        ->group("tb_acc_year.year")->order("year ASC")->queryAll();
        $year = intval(0);
        $approve1_lv = intval(0);
        $approve2_lv = intval(0);
        
        if (!count($resource))
        {
            echo 'ขณะนี้ระบบยังไม่เปิดให้กรอกข้อมูล <a href="#" onclick="window.history.back();">ย้อนกลับ</a>';
        }
        else
        {
            foreach ($resource as $row)
            {
                $approve1_lv = $row['approve1'];
                $approve2_lv = $row['approve2'];
                if($approve1_lv != 3 || $approve2_lv != 2){
                    $year = $row['year'];
                    break;
                }
            }
        }
        
        if ($year != 0)
        {//ในปีนี้มีการกรอกข้อมูลมาแล้ว
            //เป็นการยืนยันข้อมูลรอบที่
            $round = intval(1);
            if($approve1_lv == 2)
            {
                //เช็คว่าทุกแผนกเป็น 2 มั้ย โดยการเทียบจำนวนแผนกทีได้รับการกรอก กับ จำนวนแผนกที่ได้รับการยืนยัน
                $countdep = Yii::app()->db->createCommand()->select("COUNT(*)")
                        ->from("tb_division d")->where("d.enable = 1 and d.division_level < 3")->queryScalar();
                $countapprove2 = Yii::app()->db->createCommand()->select("COUNT(DISTINCT d.division_id)")
                        ->from("tb_division d")->join("tb_profile_fill pf", "d.division_id = pf.division_id")
                        ->join("tb_month_goal mg", "d.division_id = mg.division_id")
                        ->where("mg.approve1_lv = 2")
                        ->group("d.division_id")
                        ->queryScalar();
                $countapprove3 = Yii::app()->db->createCommand()->select("COUNT(DISTINCT d.division_id)")
                        ->from("tb_division d")->join("tb_profile_fill pf", "d.division_id = pf.division_id")
                        ->join("tb_month_goal mg", "d.division_id = mg.division_id")
                        ->where("mg.approve1_lv = 3")
                        ->group("d.division_id")
                        ->queryScalar();
                if($countdep == $countapprove2)
                    $round = 2;
                else if($countdep == $countapprove3)
                    $round = 3;
            }
            //ส่วนนี้สามารถเพิ่มการ redirect ไปยัง YearGoal ได้
            $userlv = Yii::app()->user->UserPosition;
            //bypass
            if($userlv == 3){
                $this->render("approve_admin", array("round"=>$round, 'year'=>$year));
            }else if($userlv == 2){
                if($round == 3)
                    echo 'ขณะนี้ไม่สามารถยกเลิกหรือยืนยันข้อมูลได้เนื่องจาก Admin ได้ยืนยันข้อมูลแล้ว <a href="#" onclick="window.history.back();">ย้อนกลับ</a>';
                else
                    $this->render("approve_div", array("round"=>$round, 'year'=>$year));
            }
            
            return;
            
            if($userlv == 2){
                if($round == 2)
                    echo 'ขณะนี้ไม่สามารถยกเลิกหรือยืนยันข้อมูลได้เนื่องจาก Admin ได้ยืนยันข้อมูลแล้ว <a href="#" onclick="window.history.back();">ย้อนกลับ</a>';
                else
                    $this->render("approve_div", array("round"=>$round, 'year'=>$year));
            }else if($userlv == 3){
                if($round == 2)
                    $this->render("approve2");
                $this->render("approve_admin", array("round"=>$round, 'year'=>$year));
            }
        }else
        {
            echo 'ขณะนี้ระบบยังไม่เปิดให้กรอกหรือแก้ไขข้อมูล <a href="#" onclick="window.history.back();">ย้อนกลับ</a>';
        }
    }
    public function actionApprove()
    {
        $user = Yii::app()->user->UserId;
        $userdiv = Yii::app()->user->UserDiv;

        //find year to use to approve
        $resource = Yii::app()->Resource->getYearResource();
        if(empty($resource)){
            $this->render("error",array('error'=>'ขณะนี้ระบบยังไม่เปิดให้กรอกข้อมูล <a href="#" onclick="window.history.back();">ย้อนกลับ</a>'));
            return false;
        }
        $year = $resource['year'];
        $approve_lv = intval($resource['approve']);
        $round = intval($resource['round']);
        
        //ส่วนนี้สามารถเพิ่มการ redirect ไปยัง YearGoal ได้
        $userlv = Yii::app()->user->UserPosition;
        //bypass
        if($userlv == 3){
            if($round == 3) $round -= 1;
            $this->render("approve_admin", array("round"=>$round, 'year'=>$year));
        }else if($userlv == 2){
            if($round == 3 || $approve_lv == 3 || $approve_lv >= 8 || $round == 4)
                echo 'ขณะนี้ไม่สามารถยกเลิกหรือยืนยันข้อมูลได้เนื่องจาก Admin ได้ยืนยันข้อมูลแล้ว <a href="#" onclick="window.history.back();">ย้อนกลับ</a>';
            else
                $this->render("approve_div", array("round"=>$round, 'year'=>$year));
        }
    }
    
    public function actionYearGoal(){
        $resource = Yii::app()->Resource->YearResource;
        $year = intval(0);
        $approve = intval(0);
        $round = intval(0);
        
        if (!empty($resource))
        {
            $year = $resource['year'];
            $approve = $resource['approve'];
            $round = $resource['round'];
            unset($resource);
        }
        
        if ($year != 0)
        {
            //bypass
                //$round = 2;
            //endbypass
            $yearw = $year + 543;
            $userlv = Yii::app()->user->UserPosition;
           
            if($userlv == 3){
                if($round == 1 && $approve < 3){
                    $url = Yii::app()->createAbsoluteUrl("Bud/Approve");
                    echo 'กรอบงบประมาณสามารถกำหนดได้หลังจากการ <a href="'.$url.'">ยืนยัน</a>ฝ่ายทุกฝ่าย แล้ว';
                }else if($round == 3){
                    echo "ไม่สามารถกำหนดกรอบงบประมาณสำหรับปี $yearw ได้ เนื่องจากปี $yearw ได้ทำการยืนยันข้อมูลแล้ว แต่ยังไม่ได้ยืนยันการสิ้นสุดการแก้ไข  "
                            . "<br/><a href='#' onclick='window.history.back();'>ย้อนกลับ</a>";
                }else if($round == 4){
                    echo "ไม่สามารถกำหนดกรอบงบประมาณสำหรับปี $yearw ได้อีก เนื่องจากปี $yearw ได้ทำการยืนยันข้อมูลแล้วสำเร็จเสร็จสิ้นแล้ว "
                            . "<br/><a href='#' onclick='window.history.back();'>ย้อนกลับ</a>";
                }else{
                    $this->render("yearGoalAdmin", array("round"=>$round, 'year'=>$year));
                }
            }else if($userlv == 2){
                if($round >= 3){
                    echo "สำหรับปี $yearw ได้ทำการยืนยันข้อมูลเสร็จสิ้นแล้ว ไม่สามารถกำหนดกรอบงบประมาณสำหรับปี $yearw ได้อีก "
                            . "<br/><a href='#' onclick='window.history.back();'>ย้อนกลับ</a>";
                }else if(!($approve <= 1 || ($approve >=4 && $approve <=6))){
                    echo "ไม่สามารถกำหนดกรอบงบประมาณสำหรับปี $yearw ได้เนื่องจาก Admin ได้ทำการยืนยันข้อมูลแล้ว "
                            . "<br/><a href='#' onclick='window.history.back();'>ย้อนกลับ</a>";
                }else{
                    $method = Yii::app()->request->getParam("m",NULL);
                    $cid = Yii::app()->request->getParam("cid",NULL);
                    

                    if(($method == "assign" || $method == "edit")&&$cid != NULL){
                        $cname = TbDivision::model()->findByPk(intval($cid))->division_name;
                        $this->render("YearGoalDivInput", array("round"=>$round, "year"=>$year, "method"=>$method, "cid"=>$cid, "cname"=>$cname));
                    }else{
                        $this->render("YearGoalDiv", array("round"=>$round, 'year'=>$year));
                    }
                }
            }else{
            }
        }else
        {
            echo 'ขณะนี้ระบบยังไม่เปิดให้กรอกหรือแก้ไขข้อมูล <a href="#" onclick="window.history.back();">ย้อนกลับ</a>';
        }
        
        
    }
    public function actionSummary(){
        echo 'สรุปผล';
    }
    public function actionTestSql(){
        echo '<pre>'."SELECT ay.`year`, IFNULL(approve_lv,0) as approve "
                . "FROM tb_acc_year ay "
                . "JOIN tb_approve ap ON ay.`year` = ap.`year` "
                . "JOIN tb_division dp ON ap.division_id = dp.division_id AND dp.division_level < 3 dp.division_id = 71 "
                . "WHERE ay.`year` = 2015 AND approve != 9 "
                . "ORDER BY ay.`year` ASC".'</pre>';
    }
    public function actionTestGet(){
        print_r(Yii::app()->request->getParam("m",NULL));
        print_r(Yii::app()->request->getParam("cid",NULL));
        
    }
    public function actionTestPK(){
        $year = 1;
        $div = 2;
        $resource = TbYgLimit::model()->findByPk(['year'=>$year, 'division'=>$div]);
        if(empty($resource))echo 'empty';
        else echo 'not empty';
    }
    public function actionTestAutoArr(){
        $word = "1234567";
        echo $word.'<br/>';
        echo "len: ".strlen($word).'<br/>';
        echo '$word[2]: '.$word[2].'<hr/>';
        echo Yii::app()->Format->NumToDec("12,3,4,5,67,8,9,0.05");
        echo '<br/>';
        echo Yii::app()->Format->NumToDec("123.456");
        echo '<br/>';
        echo Yii::app()->Format->NumToDec("123456");
        echo '<br/>';
        echo "1,234,567,890.05";
        
    }
    public function actionTransferParentToSub(){
        $divlv2 = TbDivision::model()->findAll("division_level = 2");
        $transaction = Yii::app()->db->beginTransaction();
        try{
            foreach($divlv2 as $d2){
                $div1 = TbDivision::model()->findAll("parent_division = $d2->division_id");
                foreach($div1 as $d1){
                    $sql = "UPDATE tb_division SET sub_parent = $d2->division_id, parent_division = $d2->parent_division WHERE division_id = $d1->division_id";
                    /*
                    $d1->sub_parent = $d2->division_id;
                    $d1->parent_division = $d2->parent_division;
                    $d1->save();*/
                    Yii::app()->db->createCommand($sql)->execute();
                }
            }
            $transaction->commit();
            echo 'work';
        } catch (Exception $ex) {
            $transaction->rollback();
            echo 'bad';
        }
    }
    
    public function actionTestPhpExcel(){
        Yii::import('application.extensions.PHPExcel');
        
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->removeSheetByIndex(0);
        $worksheet1 = $objPHPExcel->createSheet();
        $worksheet2 = $objPHPExcel->createSheet();
        $worksheet1->setTitle("Sheet1 hola");
        $worksheet2->setTitle("Sheet2 holahola");
        /* @var $worksheet2 PHPExcel_Worksheet */
        
        
        ob_end_clean();
        ob_start();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="test.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        
        return FALSE;
        
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(43.25);
        
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'Hello')
        ->setCellValue('B2', 'world!')
        ->setCellValue('C1', 'Hello')
        ->setCellValue('D2', 'world!');

        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A4', 'Miscellaneous glyphs')
        ->setCellValue('A5', 'eaeuaeioueiuyaouc');

        $objPHPExcel->getActiveSheet()->setTitle('Simple');

        $objPHPExcel->setActiveSheetIndex(0);

        ob_end_clean();
        ob_start();

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="test.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

        return FALSE;
    }
}
