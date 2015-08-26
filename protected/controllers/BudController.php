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
                $sqldiv = $sql . " WHERE isposition = 0 AND parent_division = 0 ORDER BY d.erp_id ASC ";
                $sqldep = $sql . " WHERE parent_division != 0 ORDER BY d.erp_id ASC ";
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
        $user = Yii::app()->user->UserId;
        $userdiv = Yii::app()->user->UserDiv;
        //find max approve of the year
        $resource = Yii::app()->db->createCommand()->select("`year`, MAX(approve1_lv) AS approve1, MAX(approve2_lv) AS approve2, COUNT(month_goal_id) AS number")
                        ->from("tb_acc_year")->naturalLeftJoin("tb_month_goal")
                        ->group("tb_acc_year.year")->order("year ASC")->queryAll();
        $year = intval(0);
        $approve1_lv = intval(0);
        $approve2_lv = intval(0);
        $userlv = Yii::app()->user->isAdmin ? 3 : Yii::app()->user->isDivision ? 2 : Yii::app()->user->isDepartment ? 1 : 0;
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
        {
            $divusertakecare = Yii::app()->db->createCommand()->select("tb_division.division_id, division_name")
                            ->from("tb_division")->join("tb_profile_fill", "tb_division.division_id = tb_profile_fill.division_id")
                            ->where("tb_profile_fill.owner_div_id = $userdiv")->queryAll();
            $this->render("MonthGoal", array("year" => $year + 543, 'targets' => $divusertakecare));
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
        echo 'user_id:' . Yii::app()->user->user_id . '<br/>';
        echo '<hr/><hr/>';
        $userlv = Yii::app()->user->isAdmin ? 3 : Yii::app()->user->isDivision ? 2 : Yii::app()->user->isDepartment ? 1 : 0;
        echo $userlv;
        echo '<hr/>';
        echo Yii::app()->user->UserPosition;
    }
    
    //approve
    public function actionApprove()
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

}
